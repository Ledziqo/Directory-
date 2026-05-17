<?php

/**
 * PreviewPDO - A session-backed mock database for local development.
 * This makes the entire site functional without requiring MySQL.
 */

class PreviewStatement {
    private array $rows = [];
    private int $cursor = 0;
    private string $sql;
    private array $lastParams = [];
    private string $table = '';
    private string $operation = 'SELECT';

    public function __construct(string $sql, array &$dataStore, string $table = '') {
        $this->sql = strtolower(trim($sql));
        $this->table = $table;
        $this->rows = $dataStore;
        
        // Detect operation
        if (str_starts_with($this->sql, 'insert')) $this->operation = 'INSERT';
        elseif (str_starts_with($this->sql, 'update')) $this->operation = 'UPDATE';
        elseif (str_starts_with($this->sql, 'delete')) $this->operation = 'DELETE';
        elseif (str_starts_with($this->sql, 'select count(*)')) $this->operation = 'COUNT';
    }

    public function execute(array $params = []): bool {
        $this->lastParams = $params;
        $db = &PreviewPDO::getStore();
        $table = $this->table;

        if ($this->operation === 'INSERT') {
            $newId = 1;
            if (!empty($db[$table])) {
                $ids = array_column($db[$table], 'id');
                $newId = !empty($ids) ? (max($ids) + 1) : 1;
            }

            // Parse column names from INSERT INTO table (col1, col2) VALUES (?, ?)
            preg_match('/\(([^)]+)\)/', $this->sql, $colsMatch);
            $columns = array_map('trim', explode(',', $colsMatch[1] ?? ''));
            $columns = array_filter($columns);

            $row = ['id' => $newId];
            foreach ($columns as $i => $col) {
                $row[$col] = $params[$i] ?? null;
            }

            // Set timestamps if not provided
            if (!isset($row['created_at'])) $row['created_at'] = date('Y-m-d H:i:s');
            if (!isset($row['updated_at'])) $row['updated_at'] = date('Y-m-d H:i:s');

            // Auto-generate slug if needed
            if (isset($row['name']) && empty($row['slug'])) {
                $row['slug'] = strtolower(preg_replace('/[^a-z0-9]+/', '-', trim($row['name'])));
            }

            $db[$table][] = $row;
            self::$lastInsertTable = $table;
            $this->rows = [['id' => $newId]];
            return true;
        }

        if ($this->operation === 'UPDATE') {
            if (empty($db[$table])) return true;
            
            // Parse WHERE conditions (simplified: only supports WHERE id = ?)
            $wherePos = strpos($this->sql, 'where');
            if ($wherePos !== false) {
                // Find the id value from params (last param usually for simple updates)
                $idVal = end($params);
                foreach ($db[$table] as &$row) {
                    if (isset($row['id']) && $row['id'] == $idVal) {
                        // Parse SET columns
                        preg_match('/set\s+(.+?)\s+where/i', $this->sql, $setMatch);
                        if ($setMatch) {
                            $setParts = explode(',', $setMatch[1]);
                            $colIdx = 0;
                            foreach ($setParts as $part) {
                                if (preg_match('/(\w+)\s*=\s*\?/', $part, $m)) {
                                    if (isset($params[$colIdx])) {
                                        $row[$m[1]] = $params[$colIdx];
                                    }
                                    $colIdx++;
                                }
                            }
                        }
                        $row['updated_at'] = date('Y-m-d H:i:s');
                    }
                }
            }
            return true;
        }

        if ($this->operation === 'DELETE') {
            if (empty($db[$table])) return true;
            $idVal = end($params);
            $db[$table] = array_values(array_filter($db[$table], fn($r) => ($r['id'] ?? null) != $idVal));
            return true;
        }

        // For SELECT - filter rows based on WHERE conditions
        if ($this->operation === 'COUNT') {
            $filtered = $this->filterRows($db[$table] ?? []);
            $this->rows = [['count' => count($filtered)]];
            return true;
        }

        $this->rows = $this->filterRows($db[$table] ?? []);
        return true;
    }

    private function filterRows(array $rows): array {
        $sql = $this->sql;
        $params = $this->lastParams;
        $filtered = $rows;
        $paramIdx = 0;

        // Handle WHERE id = ?
        if (preg_match('/where\s+(\w+)\s*=\s*\?/i', $sql, $m)) {
            $col = $m[1];
            if (isset($params[$paramIdx])) {
                $filtered = array_filter($filtered, fn($r) => ($r[$col] ?? null) == $params[$paramIdx]);
                $paramIdx++;
            }
        }

        // Handle LIKE queries
        if (preg_match_all('/(\w+)\s+like\s+\?/i', $sql, $m)) {
            foreach ($m[1] as $col) {
                if (isset($params[$paramIdx])) {
                    $like = trim($params[$paramIdx], '%');
                    $filtered = array_filter($filtered, fn($r) => stripos($r[$col] ?? '', $like) !== false);
                    $paramIdx++;
                }
            }
        }

        // Handle status filters
        if (preg_match('/status\s*=\s*["\']?(\w+)["\']?/i', $sql, $m)) {
            $filtered = array_filter($filtered, fn($r) => ($r['status'] ?? '') == $m[1]);
        }

        // Handle is_verified = 1
        if (str_contains($sql, 'is_verified') && str_contains($sql, '1')) {
            $filtered = array_filter($filtered, fn($r) => !empty($r['is_verified']));
        }

        // Handle delivery_available = 1
        if (str_contains($sql, 'delivery_available') && str_contains($sql, '= 1')) {
            $filtered = array_filter($filtered, fn($r) => !empty($r['delivery_available']));
        }

        return array_values($filtered);
    }

    public function fetchAll(): array {
        return $this->rows;
    }

    public function fetch() {
        if (!isset($this->rows[$this->cursor])) {
            return false;
        }
        return $this->rows[$this->cursor++];
    }

    public function fetchColumn(int $column = 0): mixed {
        if (!isset($this->rows[$this->cursor])) {
            return false;
        }
        $row = $this->rows[$this->cursor++];
        return is_array($row) ? array_values($row)[$column] ?? false : $row;
    }
}

class PreviewPDO {
    private static array $store = [];
    private static ?string $lastInsertTable = null;

    public static function &getStore(): array {
        if (empty(self::$store) && isset($_SESSION['preview_db'])) {
            self::$store = $_SESSION['preview_db'];
        }
        if (empty(self::$store)) {
            self::initData();
        }
        // Sync back to session
        $_SESSION['preview_db'] = &self::$store;
        return self::$store;
    }

    private static function initData(): void {
        self::$store = [
            'categories' => [
                ['id' => 1, 'name' => 'Car Parts & Accessories', 'slug' => 'car-parts', 'description' => '', 'icon' => 'Auto', 'sort_order' => 1, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 2, 'name' => 'Construction Materials', 'slug' => 'construction', 'description' => '', 'icon' => 'Build', 'sort_order' => 2, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 3, 'name' => 'Printing & Packaging', 'slug' => 'printing-packaging', 'description' => '', 'icon' => 'Print', 'sort_order' => 3, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 4, 'name' => 'Hotel & Restaurant Supplies', 'slug' => 'hotel-restaurant', 'description' => '', 'icon' => 'Hotel', 'sort_order' => 4, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 5, 'name' => 'Office Supplies', 'slug' => 'office-supplies', 'description' => '', 'icon' => 'Office', 'sort_order' => 5, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 6, 'name' => 'Furniture', 'slug' => 'furniture', 'description' => '', 'icon' => 'Home', 'sort_order' => 6, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 7, 'name' => 'Electronics', 'slug' => 'electronics', 'description' => '', 'icon' => 'Tech', 'sort_order' => 7, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 8, 'name' => 'Machinery & Tools', 'slug' => 'machinery-tools', 'description' => '', 'icon' => 'Tools', 'sort_order' => 8, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 9, 'name' => 'Importers & Wholesalers', 'slug' => 'importers-wholesalers', 'description' => '', 'icon' => 'Import', 'sort_order' => 9, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 10, 'name' => 'Professional Services', 'slug' => 'services', 'description' => '', 'icon' => 'Service', 'sort_order' => 10, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 11, 'name' => 'Agriculture & Farming', 'slug' => 'agriculture-farming', 'description' => '', 'icon' => 'Farm', 'sort_order' => 11, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 12, 'name' => 'Textiles & Garments', 'slug' => 'textiles-garments', 'description' => '', 'icon' => 'Textile', 'sort_order' => 12, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 13, 'name' => 'Cleaning & Sanitation', 'slug' => 'cleaning-sanitation', 'description' => '', 'icon' => 'Clean', 'sort_order' => 13, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 14, 'name' => 'Medical & Pharmacy Supplies', 'slug' => 'medical-pharmacy', 'description' => '', 'icon' => 'Health', 'sort_order' => 14, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 15, 'name' => 'Beauty & Salon Supplies', 'slug' => 'beauty-salon', 'description' => '', 'icon' => 'Beauty', 'sort_order' => 15, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 16, 'name' => 'Event & Catering Supplies', 'slug' => 'event-catering', 'description' => '', 'icon' => 'Event', 'sort_order' => 16, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 17, 'name' => 'Logistics & Delivery', 'slug' => 'logistics-delivery', 'description' => '', 'icon' => 'Delivery', 'sort_order' => 17, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
                ['id' => 18, 'name' => 'Solar & Electrical', 'slug' => 'solar-electrical', 'description' => '', 'icon' => 'Solar', 'sort_order' => 18, 'is_active' => 1, 'created_at' => '2025-01-01 00:00:00'],
            ],
            'locations' => [
                ['id' => 1, 'name' => 'Bole', 'slug' => 'bole', 'sort_order' => 1, 'is_active' => 1],
                ['id' => 2, 'name' => 'Merkato', 'slug' => 'merkato', 'sort_order' => 2, 'is_active' => 1],
                ['id' => 3, 'name' => 'Kazanchis', 'slug' => 'kazanchis', 'sort_order' => 3, 'is_active' => 1],
                ['id' => 4, 'name' => 'Megenagna', 'slug' => 'megenagna', 'sort_order' => 4, 'is_active' => 1],
                ['id' => 5, 'name' => 'CMC', 'slug' => 'cmc', 'sort_order' => 5, 'is_active' => 1],
                ['id' => 6, 'name' => 'Piassa', 'slug' => 'piassa', 'sort_order' => 6, 'is_active' => 1],
                ['id' => 7, 'name' => 'Sarbet', 'slug' => 'sarbet', 'sort_order' => 7, 'is_active' => 1],
            ],
            'users' => [
                ['id' => 1, 'full_name' => 'Preview Buyer', 'email' => 'buyer@example.com', 'phone' => '0911000000', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'buyer', 'email_verified' => 1, 'reset_token' => null, 'reset_expires' => null, 'created_at' => '2025-01-01 00:00:00', 'updated_at' => '2025-01-01 00:00:00', 'last_active' => '2025-01-01 00:00:00'],
                ['id' => 2, 'full_name' => 'Preview Supplier', 'email' => 'supplier@example.com', 'phone' => '0911222333', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'supplier', 'email_verified' => 1, 'reset_token' => null, 'reset_expires' => null, 'created_at' => '2025-01-01 00:00:00', 'updated_at' => '2025-01-01 00:00:00', 'last_active' => '2025-01-01 00:00:00'],
            ],
            'suppliers' => [
                [
                    'id' => 1, 'user_id' => 2, 'business_name' => 'Addis Auto Parts', 'slug' => 'addis-auto-parts',
                    'category_id' => 1, 'location_id' => 2, 'address' => 'Merkato, Addis Ababa',
                    'phone' => '0911222333', 'whatsapp' => '0911222333', 'telegram' => '@addisparts', 'email' => 'sales@addisparts.test',
                    'website' => '', 'description' => 'Wholesale and retail supplier for fast-moving vehicle parts, lights, bumpers, filters, and accessories.',
                    'opening_hours' => 'Mon-Sat: 8:30 - 6:00', 'delivery_available' => 1, 'bulk_available' => 1, 'logo' => '',
                    'status' => 'approved', 'is_verified' => 1, 'is_featured' => 1, 'is_premium' => 1, 'plan' => 'premium',
                    'view_count' => 248, 'contact_click_count' => 43, 'response_rate' => 92, 'response_time_hours' => 3,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
                ],
                [
                    'id' => 2, 'user_id' => 2, 'business_name' => 'Bole Office Furnishings', 'slug' => 'bole-office-furnishings',
                    'category_id' => 6, 'location_id' => 1, 'address' => 'Bole Medhanialem',
                    'phone' => '0911444555', 'whatsapp' => '0911444555', 'telegram' => '', 'email' => 'hello@boleoffice.test',
                    'website' => '', 'description' => 'Modern office chairs, desks, reception furniture, and installation for teams and new offices.',
                    'opening_hours' => 'Mon-Sat: 9:00 - 6:00', 'delivery_available' => 1, 'bulk_available' => 1, 'logo' => '',
                    'status' => 'approved', 'is_verified' => 1, 'is_featured' => 1, 'is_premium' => 0, 'plan' => 'verified',
                    'view_count' => 176, 'contact_click_count' => 31, 'response_rate' => 88, 'response_time_hours' => 5,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-11 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-11 days')),
                ],
                [
                    'id' => 3, 'user_id' => 2, 'business_name' => 'Kazanchis Print House', 'slug' => 'kazanchis-print-house',
                    'category_id' => 3, 'location_id' => 3, 'address' => 'Kazanchis',
                    'phone' => '0911666777', 'whatsapp' => '0911666777', 'telegram' => '@printkazanchis', 'email' => '',
                    'website' => '', 'description' => 'Packaging, stickers, flyers, restaurant menus, and branded print materials with quick turnaround.',
                    'opening_hours' => 'Mon-Fri: 8:30 - 5:30', 'delivery_available' => 1, 'bulk_available' => 1, 'logo' => '',
                    'status' => 'approved', 'is_verified' => 1, 'is_featured' => 1, 'is_premium' => 0, 'plan' => 'verified',
                    'view_count' => 132, 'contact_click_count' => 22, 'response_rate' => 81, 'response_time_hours' => 6,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                ],
            ],
            'buyer_requests' => [
                [
                    'id' => 1, 'user_id' => 1, 'title' => 'Need 40 ergonomic office chairs',
                    'category_id' => 6, 'location_id' => 1, 'quantity' => '40 chairs', 'budget' => 'Open to quotes', 'urgency' => 'this_week',
                    'description' => 'Looking for durable office chairs with delivery and installation for a new team space.',
                    'photo' => '', 'status' => 'open', 'contact_method' => 'whatsapp', 'contact_value' => '0911000000',
                    'privacy' => 'public', 'is_pinned' => 1, 'view_count' => 84, 'quote_count' => 5,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                ],
                [
                    'id' => 2, 'user_id' => 1, 'title' => 'Toyota bumper and headlight supplier',
                    'category_id' => 1, 'location_id' => 2, 'quantity' => '10 sets', 'budget' => 'Best wholesale price', 'urgency' => 'today',
                    'description' => 'Need reliable stock for Toyota bumpers and headlights. Delivery preferred.',
                    'photo' => '', 'status' => 'open', 'contact_method' => 'phone', 'contact_value' => '0911000000',
                    'privacy' => 'public', 'is_pinned' => 0, 'view_count' => 59, 'quote_count' => 3,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                ],
                [
                    'id' => 3, 'user_id' => 1, 'title' => 'Custom packaging for coffee bags',
                    'category_id' => 3, 'location_id' => 3, 'quantity' => '2,000 pieces', 'budget' => 'Send options', 'urgency' => 'flexible',
                    'description' => 'Need printed labels and packaging bags for roasted coffee, preferably with sample options.',
                    'photo' => '', 'status' => 'open', 'contact_method' => 'telegram', 'contact_value' => '@previewbuyer',
                    'privacy' => 'public', 'is_pinned' => 0, 'view_count' => 31, 'quote_count' => 2,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                ],
            ],
            'reviews' => [
                ['id' => 1, 'supplier_id' => 1, 'user_id' => 1, 'rating' => 5, 'comment' => 'Fast response and clear pricing.', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))],
                ['id' => 2, 'supplier_id' => 1, 'user_id' => 1, 'rating' => 4, 'comment' => 'Good quality parts.', 'is_verified' => 1, 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))],
            ],
            'quotes' => [],
            'saved_suppliers' => [],
            'admin_logs' => [],
            'payments' => [],
            'supplier_photos' => [],
            'popular_searches' => [],
            'reports' => [],
            'supplier_views' => [],
        ];
    }

    public function __construct() {
        // Ensure store is loaded
        self::getStore();
    }

    public function lastInsertId(): string {
        $store = self::getStore();
        // Return the ID from the most recently inserted table
        if (self::$lastInsertTable && isset($store[self::$lastInsertTable]) && !empty($store[self::$lastInsertTable])) {
            $lastRow = end($store[self::$lastInsertTable]);
            if (isset($lastRow['id'])) {
                return (string) $lastRow['id'];
            }
        }
        // Fallback
        $maxId = 0;
        foreach ($store as $table => $rows) {
            if (!empty($rows)) {
                $ids = array_column($rows, 'id');
                if (!empty($ids)) {
                    $tableMax = max($ids);
                    if ($tableMax > $maxId) $maxId = $tableMax;
                }
            }
        }
        return (string) $maxId;
    }

    public function query(string $sql): PreviewStatement {
        $table = $this->detectTable($sql);
        return new PreviewStatement($sql, self::$store[$table] ?? [], $table);
    }

    public function prepare(string $sql): PreviewStatement {
        $table = $this->detectTable($sql);
        return new PreviewStatement($sql, self::$store[$table] ?? [], $table);
    }

    private function detectTable(string $sql): string {
        $s = strtolower($sql);
        $tables = ['categories', 'locations', 'users', 'suppliers', 'buyer_requests', 'reviews', 'quotes', 'saved_suppliers', 'admin_logs', 'payments', 'supplier_photos', 'popular_searches', 'reports', 'supplier_views'];
        
        foreach ($tables as $t) {
            if (str_contains($s, $t)) return $t;
        }
        
        // Fallback for aliased tables
        if (str_contains($s, 'from suppliers') || str_contains($s, 'into suppliers') || str_contains($s, 'update suppliers')) return 'suppliers';
        if (str_contains($s, 'from buyer_requests') || str_contains($s, 'into buyer_requests') || str_contains($s, 'update buyer_requests')) return 'buyer_requests';
        if (str_contains($s, 'from users') || str_contains($s, 'into users') || str_contains($s, 'update users')) return 'users';
        
        return '';
    }
}