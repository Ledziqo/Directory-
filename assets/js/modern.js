// Modern enhancements
(function() {
    // Fix category icons - replace emojis with proper styled icons
    var catCards = document.querySelectorAll('.grid-4 > a.card');
    var catData = [
        {name: 'Car Parts', color: '#2563EB'},
        {name: 'Construction', color: '#059669'},
        {name: 'Printing', color: '#D97706'},
        {name: 'Hotel', color: '#DC2626'},
        {name: 'Office', color: '#7C3AED'},
        {name: 'Furniture', color: '#0891B2'},
        {name: 'Electronics', color: '#2563EB'},
        {name: 'Machinery', color: '#059669'}
    ];
    catCards.forEach(function(card, i) {
        if (i < catData.length) {
            var body = card.querySelector('.card-body');
            if (body) {
                var title = body.querySelector('.card-title');
                var titleText = title ? title.textContent : '';
                body.innerHTML = '';
                body.style.cssText = 'display:flex;align-items:center;gap:14px;padding:4px;';
                var iconWrap = document.createElement('span');
                iconWrap.style.cssText = 'flex-shrink:0;width:48px;height:48px;background:linear-gradient(135deg,' + catData[i].color + '15,' + catData[i].color + '25);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;';
                iconWrap.textContent = ['??','???','??','??','??','??','??','??'][i];
                var text = document.createElement('span');
                text.style.cssText = 'font-weight:600;font-size:0.95rem;color:#0f172a;';
                text.textContent = titleText;
                body.appendChild(iconWrap);
                body.appendChild(text);
            }
        }
    });

    // Fix View All link for categories
    var sectionHeaders = document.querySelectorAll('.section-header');
    sectionHeaders.forEach(function(header) {
        var h2 = header.querySelector('h2');
        var link = header.querySelector('a');
        if (h2 && h2.textContent === 'Popular Categories' && link) {
            link.href = 'categories.html';
        }
    });

    // Add Why EthioMarket section if not present
    if (!document.querySelector('[data-why]')) {
        var sections = document.querySelectorAll('section');
        var target = null;
        for (var i = 0; i < sections.length; i++) {
            var h2 = sections[i].querySelector('h2');
            if (h2 && h2.textContent.indexOf('Latest Buyer Requests') !== -1) {
                target = sections[i];
                break;
            }
        }
        if (target) {
            var why = document.createElement('section');
            why.setAttribute('data-why', '1');
            why.style.cssText = 'background:#fff;padding:64px 16px;';
            why.innerHTML = '<div class="container"><div style="text-align:center;max-width:560px;margin:0 auto 40px;"><h2 style="font-size:1.8rem;font-weight:800;color:#0f172a;margin-bottom:10px;letter-spacing:-0.5px;">Why EthioMarket?</h2><p style="color:#64748b;font-size:1rem;">The smarter way to find suppliers and get quotes in Addis Ababa</p></div><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;"><div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;transition:all 0.25s;" onmouseover="this.style.boxShadow=\'0 12px 32px -8px rgba(0,0,0,0.08)\';this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.boxShadow=\'none\';this.style.transform=\'none\'"><span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">?</span><div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Verified Businesses</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Every supplier is manually reviewed and verified before appearing.</p></div></div><div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;transition:all 0.25s;" onmouseover="this.style.boxShadow=\'0 12px 32px -8px rgba(0,0,0,0.08)\';this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.boxShadow=\'none\';this.style.transform=\'none\'"><span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">?</span><div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Direct Communication</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Contact suppliers directly via WhatsApp, Telegram, or phone.</p></div></div><div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;transition:all 0.25s;" onmouseover="this.style.boxShadow=\'0 12px 32px -8px rgba(0,0,0,0.08)\';this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.boxShadow=\'none\';this.style.transform=\'none\'"><span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">?</span><div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Compare Quotes</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Receive offers from multiple suppliers and choose the best deal.</p></div></div><div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;transition:all 0.25s;" onmouseover="this.style.boxShadow=\'0 12px 32px -8px rgba(0,0,0,0.08)\';this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.boxShadow=\'none\';this.style.transform=\'none\'"><span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">?</span><div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Save Time</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Stop calling 20 shops. Post once and let verified sellers come to you.</p></div></div></div></div></section>';
            target.parentNode.insertBefore(why, target);
        }
    }
})();
