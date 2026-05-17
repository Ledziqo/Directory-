/**
 * EthioMarket - Main JavaScript
 * Features: Toast, Dark Mode, Scroll Animations, Mobile Menu, Sticky Search, Lightbox
 */

// Toast Notifications
function showToast(message, type = 'info', title = '') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.setAttribute('role', 'alert');

    const icons = {
        success: '&#10003;',
        error: '&#10007;',
        info: '&#8505;'
    };

    const titles = {
        success: 'Success',
        error: 'Error',
        info: 'Info'
    };

    toast.innerHTML = `
        <div class="toast-icon" aria-hidden="true">${icons[type] || icons.info}</div>
        <div>
            <div class="toast-title">${title || titles[type] || titles.info}</div>
            <div class="toast-message">${message}</div>
        </div>
    `;

    container.appendChild(toast);

    // Auto-remove after 4 seconds
    setTimeout(() => {
        toast.classList.add('removing');
        toast.addEventListener('animationend', () => toast.remove());
    }, 4000);
}

// Dark Mode Toggle
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    
    // Update icons
    const lightIcon = document.querySelector('.theme-icon-light');
    const darkIcon = document.querySelector('.theme-icon-dark');
    if (lightIcon && darkIcon) {
        lightIcon.style.display = next === 'dark' ? 'none' : 'block';
        darkIcon.style.display = next === 'dark' ? 'block' : 'none';
    }
}

// Update theme icon on load
function updateThemeIcon() {
    const theme = document.documentElement.getAttribute('data-theme');
    const lightIcon = document.querySelector('.theme-icon-light');
    const darkIcon = document.querySelector('.theme-icon-dark');
    if (lightIcon && darkIcon) {
        lightIcon.style.display = theme === 'dark' ? 'none' : 'block';
        darkIcon.style.display = theme === 'dark' ? 'block' : 'none';
    }
}

// Mobile Menu Toggle
function toggleMobileMenu() {
    const navLinks = document.getElementById('nav-menu');
    const overlay = document.getElementById('menuOverlay');
    const toggle = document.querySelector('.mobile-toggle');
    
    if (!navLinks) return;
    
    const isOpen = navLinks.classList.contains('open');
    
    if (isOpen) {
        navLinks.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    } else {
        navLinks.classList.add('open');
        if (overlay) overlay.classList.add('active');
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
}

// Navbar scroll effect
function handleNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
}

// Back to Top button
function handleBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;
    
    if (window.scrollY > 400) {
        btn.classList.add('visible');
    } else {
        btn.classList.remove('visible');
    }
}

// Sticky Search Bar
function handleStickySearch() {
    const searchBar = document.querySelector('.search-bar');
    if (!searchBar) return;
    
    const hero = document.querySelector('.hero');
    if (!hero) return;
    
    const heroBottom = hero.getBoundingClientRect().bottom;
    
    if (heroBottom < 68) {
        searchBar.classList.add('sticky');
    } else {
        searchBar.classList.remove('sticky');
    }
}

// Scroll Animations (Intersection Observer)
function initScrollAnimations() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    
    if (!elements.length) return;
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    elements.forEach(el => observer.observe(el));
}

// Count Up Animation
function countUp(element, target, duration = 2000) {
    const start = 0;
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(start + (target - start) * easeOut);
        
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

// Initialize count-up animations
function initCountUp() {
    const elements = document.querySelectorAll('.count-up');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.target, 10);
                if (!isNaN(target)) {
                    countUp(entry.target, target);
                }
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    elements.forEach(el => observer.observe(el));
}

// Lightbox
function initLightbox() {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxClose = document.getElementById('lightboxClose');
    
    if (!lightbox || !lightboxImg) return;
    
    document.querySelectorAll('.photo-grid img').forEach(img => {
        img.addEventListener('click', () => {
            lightboxImg.src = img.src;
            lightboxImg.alt = img.alt || '';
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }
    
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && lightbox.classList.contains('active')) {
            closeLightbox();
        }
    });
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Search Autocomplete
let autocompleteTimeout = null;

function initSearchAutocomplete() {
    const searchInput = document.querySelector('input[name="q"]');
    const autocomplete = document.getElementById('searchAutocomplete');
    
    if (!searchInput || !autocomplete) return;
    
    searchInput.addEventListener('input', (e) => {
        const value = e.target.value.trim();
        
        if (value.length < 2) {
            autocomplete.classList.remove('active');
            return;
        }
        
        clearTimeout(autocompleteTimeout);
        autocompleteTimeout = setTimeout(() => {
            fetch(`/pages/api/autocomplete.php?q=${encodeURIComponent(value)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        autocomplete.classList.remove('active');
                        return;
                    }
                    
                    autocomplete.innerHTML = data.map(item => {
                        let url = '';
                        let icon = '';
                        if (item.type === 'supplier') {
                            url = `/pages/directory.php?q=${encodeURIComponent(item.name)}`;
                            icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>';
                        } else if (item.type === 'request') {
                            url = `/pages/request-detail.php?id=${item.slug}`;
                            icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
                        } else if (item.type === 'category') {
                            url = `/pages/directory.php?category=${item.slug}`;
                            icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>';
                        }
                        
                        return `
                            <a href="${url}" class="search-autocomplete-item" style="text-decoration:none; color:inherit;">
                                ${icon}
                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight:500;">${escapeHtml(item.name)}</div>
                                    <div style="font-size:0.75rem; color:var(--text-light); text-transform:capitalize;">${item.type}</div>
                                </div>
                            </a>
                        `;
                    }).join('');
                    autocomplete.classList.add('active');
                })
                .catch(() => {
                    autocomplete.classList.remove('active');
                });
        }, 150);
    });
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !autocomplete.contains(e.target)) {
            autocomplete.classList.remove('active');
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function selectAutocomplete(value) {
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        searchInput.value = value;
        document.getElementById('searchAutocomplete').classList.remove('active');
    }
}

// Mobile Filter Toggle
function initFilterToggle() {
    const toggle = document.getElementById('filterToggle');
    const filters = document.getElementById('filtersContainer');
    
    if (!toggle || !filters) return;
    
    toggle.addEventListener('click', () => {
        filters.classList.toggle('active');
        toggle.setAttribute('aria-expanded', filters.classList.contains('active'));
    });
}

// Character Counter
function initCharCounters() {
    document.querySelectorAll('[data-max-length]').forEach(input => {
        const max = parseInt(input.dataset.maxLength, 10);
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        input.parentNode.appendChild(counter);
        
        function update() {
            const current = input.value.length;
            counter.textContent = `${current}/${max} characters`;
            
            counter.classList.remove('warning', 'danger');
            if (current > max * 0.9) counter.classList.add('danger');
            else if (current > max * 0.75) counter.classList.add('warning');
        }
        
        input.addEventListener('input', update);
        update();
    });
}

// Form Validation
function initFormValidation() {
    document.querySelectorAll('form').forEach(form => {
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('invalid')) {
                    validateField(input);
                }
            });
        });
        
        form.addEventListener('submit', (e) => {
            let valid = true;
            inputs.forEach(input => {
                if (!validateField(input)) valid = false;
            });
            if (!valid) e.preventDefault();
        });
    });
}

function validateField(input) {
    const isValid = input.checkValidity();
    input.classList.remove('valid', 'invalid');
    input.classList.add(isValid ? 'valid' : 'invalid');
    return isValid;
}

// CSRF Token Helper
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}

// Add CSRF to all forms
function addCsrfToForms() {
    document.querySelectorAll('form').forEach(form => {
        if (form.method.toUpperCase() === 'POST' && !form.querySelector('input[name="csrf_token"]')) {
            const token = getCsrfToken();
            if (token) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'csrf_token';
                input.value = token;
                form.appendChild(input);
            }
        }
    });
}

// Navbar height offset for anchor links
function handleAnchorLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 68;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                window.scrollTo({ top: targetPosition, behavior: 'smooth' });
            }
        });
    });
}

// Initialize everything on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    updateThemeIcon();
    initScrollAnimations();
    initCountUp();
    initLightbox();
    initSearchAutocomplete();
    initFilterToggle();
    initCharCounters();
    initFormValidation();
    addCsrfToForms();
    handleAnchorLinks();
    
    // Scroll handlers
    window.addEventListener('scroll', () => {
        handleNavbarScroll();
        handleBackToTop();
        handleStickySearch();
    });
    
    // Close mobile menu on resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            const navLinks = document.getElementById('nav-menu');
            const overlay = document.getElementById('menuOverlay');
            if (navLinks) navLinks.classList.remove('open');
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        const navLinks = document.getElementById('nav-menu');
        const toggle = document.querySelector('.mobile-toggle');
        const overlay = document.getElementById('menuOverlay');
        
        if (navLinks && navLinks.classList.contains('open')) {
            if (!navLinks.contains(e.target) && !toggle?.contains(e.target)) {
                navLinks.classList.remove('open');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });
});