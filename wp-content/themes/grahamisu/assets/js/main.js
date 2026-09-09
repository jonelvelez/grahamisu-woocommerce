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

});
