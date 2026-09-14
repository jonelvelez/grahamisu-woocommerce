document.addEventListener('DOMContentLoaded', function () {

    // ── Mobile menu ──────────────────────────────────────────────
    var toggle = document.querySelector('.menu-toggle');
    var nav    = document.querySelector('.site-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.classList.toggle('is-active', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // ── Product page: image gallery carousel ─────────────────────
    (function initGallery() {
        var carousel = document.querySelector('.sp__carousel');
        if (!carousel) return;

        var mainImg  = document.getElementById('sp-main-img');
        var thumbs   = carousel.querySelectorAll('.sp__thumb');
        var prevBtn  = carousel.querySelector('.sp__arrow--prev');
        var nextBtn  = carousel.querySelector('.sp__arrow--next');
        var images   = [];
        var current  = 0;

        try { images = JSON.parse(carousel.dataset.images || '[]'); } catch (e) {}

        function setActive(index) {
            current = (index + images.length) % images.length;
            if (mainImg && images[current]) {
                mainImg.src = images[current];
                mainImg.removeAttribute('srcset');
                mainImg.removeAttribute('sizes');
            }
            thumbs.forEach(function (btn, i) {
                btn.classList.toggle('is-active', i === current);
            });
        }

        thumbs.forEach(function (btn, i) {
            btn.addEventListener('click', function () { setActive(i); });
        });

        if (prevBtn) prevBtn.addEventListener('click', function () { setActive(current - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function () { setActive(current + 1); });
    }());

    // ── Product page: quantity +/- buttons ───────────────────────
    (function initQuantityButtons() {
        document.querySelectorAll('.sp-qty-box').forEach(function (box) {
            var dec   = box.querySelector('.sp-qty-dec');
            var inc   = box.querySelector('.sp-qty-inc');
            var input = box.querySelector('input.qty');
            if (!dec || !inc || !input) return;

            dec.addEventListener('click', function () {
                var val = parseInt(input.value, 10) || 1;
                var min = parseInt(input.min, 10) || 1;
                if (val > min) {
                    input.value = val - 1;
                    triggerChange(input);
                }
            });

            inc.addEventListener('click', function () {
                var val = parseInt(input.value, 10) || 1;
                var max = parseInt(input.max, 10) || 0;
                if (!max || val < max) {
                    input.value = val + 1;
                    triggerChange(input);
                }
            });
        });
    }());

    function triggerChange(el) {
        if (window.jQuery) {
            window.jQuery(el).trigger('change');
        } else {
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // ── Cart page: fulfillment tab toggle ────────────────────────
    (function initFulfillmentTabs() {
        var tabs         = document.querySelectorAll('.gc-fulfillment__tab');
        var pickupFields = document.querySelector('.gc-pickup-fields');
        var deliveryFields = document.querySelector('.gc-delivery-fields');
        if (!tabs.length) return;

        var shippingInput = document.getElementById('gc-shipping-method');

        function applyTab(activeTab) {
            var isPickup = activeTab.dataset.tab === 'pickup';
            tabs.forEach(function (t) { t.classList.remove('is-active'); });
            activeTab.classList.add('is-active');
            if (pickupFields)  pickupFields.hidden  = !isPickup;
            if (deliveryFields) deliveryFields.hidden = isPickup;
            // Sync the shipping method hidden input so WooCommerce knows which method to use
            if (shippingInput) {
                shippingInput.value = activeTab.dataset.shippingMethod || '';
            }
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () { applyTab(tab); });
        });
    }());

    // ── Cart page: date picker calendar ──────────────────────────
    (function initDatePickers() {
        var MONTHS = ['January','February','March','April','May','June',
                      'July','August','September','October','November','December'];
        var DAYS   = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

        function pad(n) { return n < 10 ? '0' + n : '' + n; }

        function createDatePicker(btn) {
            // Wrap button in positioned container
            var wrap = document.createElement('div');
            wrap.className = 'gc-date-picker-wrap';
            btn.parentNode.insertBefore(wrap, btn);
            wrap.appendChild(btn);

            // Create calendar element
            var cal = document.createElement('div');
            cal.className = 'gc-calendar';
            cal.setAttribute('aria-hidden', 'true');
            cal.setAttribute('role', 'dialog');
            cal.setAttribute('aria-label', 'Date picker');
            wrap.appendChild(cal);

            var selectedDate = null;
            var view = new Date();
            view.setDate(1);

            function render() {
                var y = view.getFullYear();
                var m = view.getMonth();
                var daysInMonth = new Date(y, m + 1, 0).getDate();

                // First weekday of month (Mon=0 … Sun=6)
                var firstDay = new Date(y, m, 1).getDay();
                firstDay = firstDay === 0 ? 6 : firstDay - 1;

                var h = '';

                // Header
                h += '<div class="gc-cal__header">';
                h += '<button class="gc-cal__nav gc-cal__prev" type="button" aria-label="Previous month">';
                h += '<svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 6.5L7 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                h += '</button>';
                h += '<span class="gc-cal__month">' + MONTHS[m] + ' ' + y + '</span>';
                h += '<button class="gc-cal__nav gc-cal__next" type="button" aria-label="Next month">';
                h += '<svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 6.5L1 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                h += '</button>';
                h += '</div>';

                // Weekday labels
                h += '<div class="gc-cal__weekdays">';
                DAYS.forEach(function (d) { h += '<span>' + d + '</span>'; });
                h += '</div>';

                // Date grid
                h += '<div class="gc-cal__grid">';

                for (var i = 0; i < firstDay; i++) {
                    h += '<span class="gc-cal__empty"></span>';
                }

                for (var day = 1; day <= daysInMonth; day++) {
                    var ds = y + '-' + pad(m + 1) + '-' + pad(day);
                    var cls = 'gc-cal__day' + (ds === selectedDate ? ' is-selected' : '');
                    h += '<button class="' + cls + '" type="button" data-date="' + ds + '">' + day + '</button>';
                }

                h += '</div>';
                cal.innerHTML = h;

                cal.querySelector('.gc-cal__prev').addEventListener('click', function (e) {
                    e.stopPropagation();
                    view.setMonth(view.getMonth() - 1);
                    render();
                });
                cal.querySelector('.gc-cal__next').addEventListener('click', function (e) {
                    e.stopPropagation();
                    view.setMonth(view.getMonth() + 1);
                    render();
                });
                cal.querySelectorAll('.gc-cal__day').forEach(function (dayBtn) {
                    dayBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        selectedDate = dayBtn.dataset.date;
                        var parts = selectedDate.split('-');
                        var display = MONTHS[parseInt(parts[1], 10) - 1] + ' ' + parseInt(parts[2], 10) + ', ' + parts[0];
                        btn.querySelector('span').textContent = display;
                        // Populate the hidden date input on the checkout form
                        var dateInput = document.getElementById('gc-delivery-date');
                        if (dateInput) dateInput.value = selectedDate;
                        close();
                    });
                });
            }

            function open() {
                render();
                cal.setAttribute('aria-hidden', 'false');
                btn.classList.add('is-open');
            }

            function close() {
                cal.setAttribute('aria-hidden', 'true');
                btn.classList.remove('is-open');
            }

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                cal.getAttribute('aria-hidden') === 'false' ? close() : open();
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) close();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });
        }

        document.querySelectorAll('.gc-date-picker').forEach(createDatePicker);
    }());

    // ── Cart page: time slot picker ───────────────────────────────
    (function initTimePicker() {
        var wrap = document.querySelector('.gc-time-picker-wrap');
        if (!wrap) return;

        var btn      = wrap.querySelector('.gc-time-picker');
        var dropdown = wrap.querySelector('.gc-time-dropdown');
        if (!btn || !dropdown) return;

        function open() {
            dropdown.setAttribute('aria-hidden', 'false');
            btn.classList.add('is-open');
        }

        function close() {
            dropdown.setAttribute('aria-hidden', 'true');
            btn.classList.remove('is-open');
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.getAttribute('aria-hidden') === 'false' ? close() : open();
        });

        dropdown.querySelectorAll('.gc-time-slot').forEach(function (slot) {
            slot.addEventListener('click', function (e) {
                e.stopPropagation();
                btn.querySelector('span').textContent = slot.dataset.slot;
                // Populate the hidden time input on the checkout form
                var timeInput = document.getElementById('gc-delivery-time');
                if (timeInput) timeInput.value = slot.dataset.slot;
                close();
            });
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) close();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });
    }());

    // ── Gallery page: lightbox ───────────────────────────────────
    (function initGalleryLightbox() {
        var cards = document.querySelectorAll('.gc-gallery__card');
        if (!cards.length) return;

        // Build lightbox DOM
        var lb = document.createElement('div');
        lb.id  = 'gc-lightbox';
        lb.setAttribute('role', 'dialog');
        lb.setAttribute('aria-modal', 'true');
        lb.setAttribute('aria-label', 'Photo viewer');
        lb.innerHTML =
            '<div class="gc-lightbox__backdrop"></div>' +
            '<button class="gc-lightbox__close" aria-label="Close">' +
                '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L17 17M17 1L1 17" stroke="white" stroke-width="1.8" stroke-linecap="round"/></svg>' +
            '</button>' +
            '<button class="gc-lightbox__prev" aria-label="Previous photo">' +
                '<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 1L1 9L9 17" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
            '</button>' +
            '<div class="gc-lightbox__inner">' +
                '<img class="gc-lightbox__img" id="gc-lb-img" alt="">' +
                '<div class="gc-lightbox__meta">' +
                    '<span class="gc-lightbox__label" id="gc-lb-label"></span>' +
                    '<p class="gc-lightbox__title" id="gc-lb-title"></p>' +
                '</div>' +
            '</div>' +
            '<button class="gc-lightbox__next" aria-label="Next photo">' +
                '<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L9 9L1 17" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
            '</button>' +
            '<span class="gc-lightbox__counter" id="gc-lb-counter"></span>';
        document.body.appendChild(lb);

        var current = 0;

        function getVisible() {
            return Array.from(cards).filter(function (c) {
                return c.style.display !== 'none';
            });
        }

        function show(index) {
            var visible = getVisible();
            if (!visible.length) return;
            current = (index + visible.length) % visible.length;
            var card  = visible[current];
            var img   = document.getElementById('gc-lb-img');
            img.src   = card.dataset.img || '';
            img.alt   = card.dataset.title || '';
            document.getElementById('gc-lb-label').textContent   = card.dataset.label || '';
            document.getElementById('gc-lb-title').textContent   = card.dataset.title || '';
            document.getElementById('gc-lb-counter').textContent = (current + 1) + ' / ' + visible.length;
            var hasPrev = visible.length > 1;
            lb.querySelector('.gc-lightbox__prev').style.display = hasPrev ? '' : 'none';
            lb.querySelector('.gc-lightbox__next').style.display = hasPrev ? '' : 'none';
        }

        function open(index) {
            show(index);
            lb.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            lb.querySelector('.gc-lightbox__close').focus();
        }

        function close() {
            lb.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        function nav(dir) {
            show(current + dir);
        }

        // Open on card click or Enter/Space
        cards.forEach(function (card) {
            card.addEventListener('click', function () {
                var visible  = getVisible();
                var visIndex = visible.indexOf(card);
                if (visIndex !== -1) open(visIndex);
            });
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    card.click();
                }
            });
        });

        lb.querySelector('.gc-lightbox__close').addEventListener('click', close);
        lb.querySelector('.gc-lightbox__backdrop').addEventListener('click', close);
        lb.querySelector('.gc-lightbox__prev').addEventListener('click', function () { nav(-1); });
        lb.querySelector('.gc-lightbox__next').addEventListener('click', function () { nav(1); });

        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('is-open')) return;
            if (e.key === 'Escape')     close();
            if (e.key === 'ArrowLeft')  nav(-1);
            if (e.key === 'ArrowRight') nav(1);
        });

        // Touch swipe support
        var touchStartX = 0;
        lb.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
        lb.addEventListener('touchend', function (e) {
            var dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 50) nav(dx < 0 ? 1 : -1);
        });
    }());

    // ── Gallery page: category filter ────────────────────────────
    (function initGalleryFilter() {
        var tabs  = document.querySelectorAll('.gc-gallery__tab');
        var cards = document.querySelectorAll('.gc-gallery__card');
        var count = document.querySelector('.gc-gallery__count');
        if (!tabs.length || !cards.length) return;

        function setActive(tab) {
            tabs.forEach(function (t) {
                var active = t === tab;
                t.classList.toggle('is-active', active);
                t.classList.toggle('border-rust', active);
                t.classList.toggle('bg-rust', active);
                t.classList.toggle('text-white', active);
                t.classList.toggle('border-[#ded8d4]', !active);
                t.classList.toggle('bg-white', !active);
                t.classList.toggle('text-[#4e4e4e]', !active);
            });
        }

        function filterCards(category) {
            var visible = 0;
            cards.forEach(function (card) {
                var show = category === 'all' || card.dataset.category === category;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (count) count.textContent = visible + (visible === 1 ? ' photo' : ' photos');
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                setActive(tab);
                filterCards(tab.dataset.tab);
            });
        });
    }());

});
