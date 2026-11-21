(function ($) {
    'use strict';

    var storageKey = 'greenlife-theme';

    function getStoredTheme() {
        try {
            var theme = localStorage.getItem(storageKey);
            return theme === 'theme-dark' || theme === 'theme-light' ? theme : 'theme-light';
        } catch (error) {
            return 'theme-light';
        }
    }

    function saveTheme(theme) {
        try {
            localStorage.setItem(storageKey, theme);
        } catch (error) {
            return false;
        }

        return true;
    }

    function applyTheme(theme) {
        $('body')
            .removeClass('theme-light theme-dark')
            .addClass(theme);

        $('.js-theme-toggle').each(function () {
            var isDark = theme === 'theme-dark';
            $(this)
                .attr('aria-label', isDark ? 'Switch to light theme' : 'Switch to dark theme')
                .find('i')
                .attr('class', isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun');
        });
    }

    function formatCounter(value, prefix, suffix, decimals) {
        var formatted = value.toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

        return prefix + formatted + suffix;
    }

    function animateCounters() {
        $('[data-counter]').each(function () {
            var $item = $(this);
            var target = parseFloat($item.data('counter')) || 0;
            var prefix = $item.data('prefix') || '';
            var suffix = $item.data('suffix') || '';
            var decimals = parseInt($item.data('decimals'), 10) || 0;
            var duration = 1100;
            var start = performance.now();

            function tick(now) {
                var progress = Math.min((now - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                var current = target * eased;
                $item.text(formatCounter(current, prefix, suffix, decimals));

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    $item.text(formatCounter(target, prefix, suffix, decimals));
                }
            }

            requestAnimationFrame(tick);
        });
    }

    function animateProgressBars() {
        $('.progress-bar, .mini-progress').each(function () {
            var progress = parseFloat($(this).data('progress')) || 0;
            $(this).css('--target-progress', progress + '%');
            $(this).find('span').css('width', progress + '%');
        });
    }

    function animateEarningsChart() {
        $('.earnings-line').each(function () {
            var line = this;
            var length = line.getTotalLength ? line.getTotalLength() : 0;

            if (!length) {
                return;
            }

            line.style.strokeDasharray = length;
            line.style.strokeDashoffset = length;

            setTimeout(function () {
                line.style.transition = 'stroke-dashoffset 1.3s ease';
                line.style.strokeDashoffset = 0;
            }, 160);
        });
    }

    function redrawCharts() {
        $('.earnings-line').each(function () {
            this.style.transition = 'none';
            this.style.strokeDasharray = '';
            this.style.strokeDashoffset = '';
        });

        animateEarningsChart();
        animateDonut();
    }

    function debounce(callback, wait) {
        var timer;

        return function () {
            var context = this;
            var args = arguments;

            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, wait);
        };
    }

    function setSidebarState(open) {
        $('body').toggleClass('sidebar-open', open);
        $('.js-sidebar-toggle').attr('aria-expanded', open ? 'true' : 'false');
    }

    function animateDonut() {
        $('.network-donut').each(function () {
            var element = this;
            var start = performance.now();
            var duration = 900;
            var a = 37;
            var b = 180;
            var c = 289;

            function tick(now) {
                var progress = Math.min((now - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);

                element.style.setProperty('--donut-a', (a * eased) + 'deg');
                element.style.setProperty('--donut-b', (b * eased) + 'deg');
                element.style.setProperty('--donut-c', (c * eased) + 'deg');

                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            }

            requestAnimationFrame(tick);
        });
    }

    function createFloatingLeaves() {
        var $page = $('.member-page').first();

        if (!$page.length || $page.data('leaves-ready')) {
            return;
        }

        $page.data('leaves-ready', true);

        for (var i = 0; i < 14; i += 1) {
            $('<span class="greenlife-leaf" aria-hidden="true"></span>')
                .css({
                    left: (Math.random() * 96) + 'vw',
                    top: (Math.random() * 88) + 'vh',
                    '--leaf-duration': (9 + Math.random() * 9) + 's',
                    '--leaf-x': ((Math.random() * 80) - 40) + 'px',
                    '--leaf-y': ((Math.random() * -90) - 20) + 'px',
                    '--leaf-rotate': (Math.random() * 180) + 'deg',
                    opacity: 0.18 + Math.random() * 0.4,
                    transform: 'scale(' + (0.6 + Math.random() * 0.9) + ')'
                })
                .appendTo($page);
        }
    }

    $(function () {
        applyTheme(getStoredTheme());
        createFloatingLeaves();
        animateCounters();
        animateProgressBars();
        animateEarningsChart();
        animateDonut();

        $('.js-sidebar-toggle').attr('aria-expanded', 'false');
        $('.js-dropdown-toggle').attr('aria-expanded', 'false');

        $('.field-error').each(function () {
            $(this).prev('.form-field').addClass('has-error');
        });

        $('.js-theme-toggle').on('click', function () {
            var nextTheme = $('body').hasClass('theme-dark') ? 'theme-light' : 'theme-dark';
            applyTheme(nextTheme);
            saveTheme(nextTheme);
        });

        $('.js-password-toggle').on('click', function () {
            var $button = $(this);
            var $input = $button.siblings('input');
            var show = $input.attr('type') === 'password';

            $input.attr('type', show ? 'text' : 'password');
            $button.attr('aria-label', show ? 'Hide password' : 'Show password');
            $button.find('i').attr('class', show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye');
        });

        $('.js-sidebar-toggle').on('click', function () {
            setSidebarState(!$('body').hasClass('sidebar-open'));
        });

        $('.dashboard-backdrop').on('click', function () {
            if (window.matchMedia('(max-width: 991px)').matches) {
                setSidebarState(false);
            }
        });

        $('.member-sidebar a').on('click', function (e) {
            var $link = $(this);
            if ($link.is('[data-sidebar-dropdown-toggle]')) {
                return;
            }
            var href = $link.attr('href') || '';
            if (href === '#' || href.indexOf('javascript:') === 0) {
                return;
            }
            if (window.matchMedia('(max-width: 991px)').matches) {
                setSidebarState(false);
            }
        });

        $(document).on('keydown', function (event) {
            if (event.key === 'Escape') {
                setSidebarState(false);
                $('.header-dropdown').removeClass('is-open');
                $('.js-dropdown-toggle').attr('aria-expanded', 'false');
            }
        });

        $('.js-dropdown-toggle').on('click', function (event) {
            event.stopPropagation();
            var $dropdown = $(this).closest('.header-dropdown');
            $('.header-dropdown').not($dropdown).removeClass('is-open');
            $dropdown.toggleClass('is-open');
            $('.js-dropdown-toggle').attr('aria-expanded', 'false');
            $(this).attr('aria-expanded', $dropdown.hasClass('is-open') ? 'true' : 'false');
        });

        $(document).on('click', function () {
            $('.header-dropdown').removeClass('is-open');
            $('.js-dropdown-toggle').attr('aria-expanded', 'false');
        });

        $('.dropdown-menu').on('click', function (event) {
            event.stopPropagation();
        });

        $('.header-search input')
            .on('focus', function () {
                $(this).closest('.header-search').addClass('is-focused');
            })
            .on('blur', function () {
                $(this).closest('.header-search').removeClass('is-focused');
            });

        $('.js-form-loading').on('submit', function () {
            var $button = $(this).find('.green-button[type="submit"]');
            $button.addClass('is-loading');
            $button.find('span').text('Please wait...');
        });

        $('.sidebar-link').each(function () {
            var href = this.getAttribute('href') || '';

            if (href !== '#' && window.location.href.indexOf(href) === 0) {
                $('.sidebar-link').removeClass('is-active');
                $(this).addClass('is-active');
            }
        });

        $(window).on('resize', debounce(function () {
            if (window.matchMedia('(min-width: 992px)').matches) {
                setSidebarState(false);
            }

            redrawCharts();
        }, 250));

        // Referral copy button
        $(document).on('click', '.copy-referral-btn', function () {
            const text = $('.profile-referral-link').val();

            navigator.clipboard.writeText(text).then(function () {
                $('.copy-referral-msg')
                    .text('Copied!')
                    .fadeIn(150)
                    .delay(1200)
                    .fadeOut(150);
            });
        });

        // Avatar preview
        $(document).on('change', '.profile-avatar-input', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-avatar-img').attr('src', e.target.result);
            };

            reader.readAsDataURL(file);
            $('.profile-avatar-submit-btn').show();
        });

        // Sidebar dropdown toggler with mobile fix
        $(document)
            .off('click.greenlifeMobileSidebarDropdownFix', '[data-sidebar-dropdown-toggle]')
            .on('click.greenlifeMobileSidebarDropdownFix', '[data-sidebar-dropdown-toggle]', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $toggle = $(this);
                var $item = $toggle.closest('.sidebar-dropdown-wrapper');
                var $submenu = $item.find('.sidebar-submenu');

                if (!$submenu.length) {
                    return;
                }

                var isOpen = $item.hasClass('is-expanded') || $submenu.is(':visible');

                if (isOpen) {
                    $item.removeClass('is-expanded');
                    $submenu.stop(true, true).slideUp(180);
                } else {
                    $item.addClass('is-expanded');
                    $submenu.stop(true, true).slideDown(180);
                }

                return false;
            });

        // Payout Wallet copy button
        $(document).on('click', '.js-copy-wallet-btn', function () {
            const text = $('.js-raw-wallet-address').val();

            navigator.clipboard.writeText(text).then(function () {
                $('.js-copy-wallet-msg')
                    .text('Copied!')
                    .fadeIn(150)
                    .delay(1200)
                    .fadeOut(150);
            });
        });

        // Wallet type / network auto-fill
        $(document).on('change', '#wallet_type', function () {
            const walletType = $(this).val();
            let network = 'TRC20';

            if (walletType === 'USDT TRC20') network = 'TRC20';
            else if (walletType === 'USDT BEP20') network = 'BEP20';
            else if (walletType === 'USDT ERC20') network = 'ERC20';
            else if (walletType === 'Bitcoin') network = 'BTC';
            else if (walletType === 'Ethereum') network = 'ETH';
            else if (walletType === 'Bank Transfer') network = 'BANK';

            $('#network').val(network);
        });

        // Live update request preview amount
        $(document).on('input', '#amount', function () {
            var val = parseFloat($(this).val());
            if (isNaN(val) || val <= 0) {
                $('#js-fund-amount-preview').text('$ 0.00');
            } else {
                var formatted = val.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                $('#js-fund-amount-preview').text('$ ' + formatted);
            }
        });

        // Copy wallet address
        $(document).on('click', '#js-copy-wallet-btn', function () {
            const text = $('#js-wallet-address-text').text().trim();

            navigator.clipboard.writeText(text).then(function () {
                $('#js-wallet-copy-msg')
                    .text('Copied!')
                    .fadeIn(150)
                    .delay(1200)
                    .fadeOut(150);
            });
        });

        // Form loading state
        $('.fund-add-form').on('submit', function () {
            var $button = $(this).find('.fund-add-submit');
            $button.addClass('is-loading');
            $button.find('span').text('Processing...');
        });

        // Screenshot upload preview
        $(document).on('change', '#transaction_screenshot', function () {
            const file = this.files[0];
            if (!file) {
                $('.fund-add-screenshot-preview').hide();
                $('.fund-add-file-trigger span').text('Choose Screenshot Image');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                $('.fund-add-screenshot-preview img').attr('src', e.target.result);
                $('.fund-add-screenshot-preview').show();
            };
            reader.readAsDataURL(file);
            $('.fund-add-file-trigger span').text(file.name);
        });

        window.initInteractiveComponents = function () {
            // Add Fund History interactive table handlers
            if ($('.fund-history-table').length) {
            var $table = $('.fund-history-table');
            var $tbody = $table.find('tbody');
            var originalRows = $tbody.find('tr').not('.js-empty-row').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No default sorting
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $('.fund-history-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    $tbody.append('<tr class="js-empty-row"><td colspan="7" class="fund-history-empty">' + emptyMsg + '</td></tr>');
                    $('#js-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $('#js-prev-page-btn').attr('disabled', true).addClass('disabled');
                    $('#js-next-page-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $('#js-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $('#js-prev-page-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $('#js-next-page-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $('.fund-history-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $('.fund-history-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $('#js-prev-page-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $('#js-next-page-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $('.fund-history-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $('.fund-history-sort').removeClass('sort-asc sort-desc');
                $('.fund-history-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        }

        // Daily Income interactive table handlers
        if ($('.income-table').length) {
            var $table = $('.income-table');
            var $tbody = $table.find('tbody');
            var originalRows = $tbody.find('tr').not('.js-empty-row').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No default sorting
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $('.income-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    var colCount = $table.find('thead th').length;
                    $tbody.append('<tr class="js-empty-row"><td colspan="' + colCount + '" class="income-empty">' + emptyMsg + '</td></tr>');
                    $('#js-income-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $('#js-prev-btn').attr('disabled', true).addClass('disabled');
                    $('#js-next-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $('#js-income-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $('#js-prev-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $('#js-next-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $('.income-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $('.income-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $('#js-prev-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $('#js-next-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $('.income-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $('.income-sort').removeClass('sort-asc sort-desc');
                $('.income-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        }

        // P2P Wallet transfer page form submission handler
        $('.p2p-wallet-form').on('submit', function () {
            var $form = $(this);
            var $button = $form.find('.p2p-wallet-btn');

            // Trim input values before submit
            $form.find('.p2p-wallet-input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            // Prevent double submit
            if ($button.hasClass('is-loading')) {
                return false;
            }

            $button.addClass('is-loading');
            $button.find('span').text('Processing...');
        });

        // P2P Wallet History interactive table handlers
        if ($('.p2p-history-table').length) {
            var $table = $('.p2p-history-table');
            var $tbody = $table.find('tbody');
            var originalRows = $tbody.find('tr').not('.js-empty-row').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No default sorting
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $('.p2p-history-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    var colCount = $table.find('thead th').length;
                    $tbody.append('<tr class="js-empty-row"><td colspan="' + colCount + '" class="p2p-history-empty">' + emptyMsg + '</td></tr>');
                    $('#js-p2p-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $('#js-p2p-prev-btn').attr('disabled', true).addClass('disabled');
                    $('#js-p2p-next-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $('#js-p2p-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $('#js-p2p-prev-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $('#js-p2p-next-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $('.p2p-history-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $('.p2p-history-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $('#js-p2p-prev-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            // Pagination next button event
            $('#js-p2p-next-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $('.p2p-history-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $('.p2p-history-sort').removeClass('sort-asc sort-desc');
                $('.p2p-history-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        }

        // Wallet to P2P transfer page form submission handler
        $('.wallet-transfer-form').on('submit', function () {
            var $form = $(this);
            var $button = $form.find('.wallet-btn');

            // Trim input values before submit
            $form.find('.wallet-input, .p2p-wallet-input').each(function () {
                $(this).val($.trim($(this).val()));
            });

            // Prevent double submit
            if ($button.hasClass('is-loading')) {
                return false;
            }

            $button.addClass('is-loading');
            $button.find('span').text('Processing...');
        });

        // Wallet Module Interactive Tables Handler
        $('.wallet-table').each(function () {
            var $table = $(this);
            var $tbody = $table.find('tbody');
            var $card = $table.closest('.wallet-table-card');
            var originalRows = $tbody.find('tr').not('.js-empty-row').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No default sorting
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $card.find('.wallet-table-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    var colCount = $table.find('thead th').length;
                    $tbody.append('<tr class="js-empty-row"><td colspan="' + colCount + '" class="wallet-table-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">' + emptyMsg + '</td></tr>');
                    $card.find('.wallet-table-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $card.find('.wallet-table-prev-btn').attr('disabled', true).addClass('disabled');
                    $card.find('.wallet-table-next-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $card.find('.wallet-table-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $card.find('.wallet-table-prev-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $card.find('.wallet-table-next-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $card.find('.wallet-table-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $card.find('.wallet-table-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $card.find('.wallet-table-prev-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $card.find('.wallet-table-next-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $table.find('.wallet-table-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $table.find('.wallet-table-sort').removeClass('sort-asc sort-desc');
                $table.find('.wallet-table-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        });

        // Referral Module Interactive Tables Handler
        $('.referral-table').each(function () {
            var $table = $(this);
            var $tbody = $table.find('tbody');
            var $card = $table.closest('.referral-table-card');
            var originalRows = $tbody.find('tr').not('.js-empty-row').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No default sorting
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $card.find('.referral-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    var colCount = $table.find('thead th').length;
                    $tbody.append('<tr class="js-empty-row"><td colspan="' + colCount + '" class="referral-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">' + emptyMsg + '</td></tr>');
                    $card.find('.referral-table-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $card.find('.referral-table-prev-btn').attr('disabled', true).addClass('disabled');
                    $card.find('.referral-table-next-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $card.find('.referral-table-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $card.find('.referral-table-prev-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $card.find('.referral-table-next-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $card.find('.referral-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $card.find('.referral-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $card.find('.referral-table-prev-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $card.find('.referral-table-next-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $table.find('.referral-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $table.find('.referral-sort').removeClass('sort-asc sort-desc');
                $table.find('.referral-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        });

        // Support module form submission handler
        $('.support-form').on('submit', function () {
            var $form = $(this);
            var $button = $form.find('.support-btn');

            // Trim input values before submit
            $form.find('.support-input, .support-textarea').each(function () {
                $(this).val($.trim($(this).val()));
            });

            // Prevent double submit
            if ($button.hasClass('is-loading')) {
                return false;
            }

            $button.addClass('is-loading');
            $button.prop('disabled', true);
            $button.text('Creating...');
        });

        // Support Tickets Table Interactive Handler
        $('.support-table').each(function () {
            var $table = $(this);
            var $tbody = $table.find('tbody');
            var $card = $table.closest('.support-table-card');
            var originalRows = $tbody.find('tr').not('.js-empty-row').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No. sorting by default
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $card.find('.support-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    var colCount = $table.find('thead th').length;
                    $tbody.append('<tr class="js-empty-row"><td colspan="' + colCount + '" class="support-empty" style="text-align: center; font-style: italic; color: var(--gl-muted); padding: 20px;">' + emptyMsg + '</td></tr>');
                    $card.find('.support-table-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $card.find('.support-table-prev-btn').attr('disabled', true).addClass('disabled');
                    $card.find('.support-table-next-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $card.find('.support-table-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $card.find('.support-table-prev-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $card.find('.support-table-next-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $card.find('.support-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $card.find('.support-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $card.find('.support-table-prev-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $card.find('.support-table-next-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $table.find('.support-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $table.find('.support-sort').removeClass('sort-asc sort-desc');
                $table.find('.support-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        });

        // Activation Details Table Interactive Handler
        $('.activation-table').each(function () {
            var $table = $(this);
            var $tbody = $table.find('tbody');
            var $card = $table.closest('.activation-table-card');
            var originalRows = $tbody.find('tr').not('.activation-empty').get();
            var filteredRows = [...originalRows];
            var currentPage = 1;
            var showCount = 10;
            var currentSortCol = 0; // S.No default sorting
            var currentSortDir = 'asc';

            function updateTable() {
                $tbody.empty();

                // Sort filtered rows
                filteredRows.sort(function (a, b) {
                    var cellA = $(a).find('td').eq(currentSortCol);
                    var cellB = $(b).find('td').eq(currentSortCol);

                    var valA = cellA.data('sort-val') !== undefined ? parseFloat(cellA.data('sort-val')) : cellA.text().trim().toLowerCase();
                    var valB = cellB.data('sort-val') !== undefined ? parseFloat(cellB.data('sort-val')) : cellB.text().trim().toLowerCase();

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return currentSortDir === 'asc' ? valA - valB : valB - valA;
                    }

                    if (valA < valB) return currentSortDir === 'asc' ? -1 : 1;
                    if (valA > valB) return currentSortDir === 'asc' ? 1 : -1;
                    return 0;
                });

                var totalEntries = filteredRows.length;
                
                if (totalEntries === 0) {
                    var searchVal = $card.find('.activation-search-input').val().trim();
                    var emptyMsg = searchVal ? 'No matching records found' : 'No data available in table';
                    var colCount = $table.find('thead th').length;
                    $tbody.append('<tr class="activation-empty"><td colspan="' + colCount + '" style="text-align: center; padding: 32px; color: var(--gl-muted); font-weight: 600;">' + emptyMsg + '</td></tr>');
                    $card.find('#js-pagination-summary').text('Showing 0 to 0 of 0 entries');
                    $card.find('#js-prev-page-btn').attr('disabled', true).addClass('disabled');
                    $card.find('#js-next-page-btn').attr('disabled', true).addClass('disabled');
                    return;
                }

                var startIndex = (currentPage - 1) * showCount;
                var endIndex = Math.min(startIndex + showCount, totalEntries);

                var displayRows = filteredRows.slice(startIndex, endIndex);
                $.each(displayRows, function (i, row) {
                    $tbody.append(row);
                });

                $card.find('#js-pagination-summary').text('Showing ' + (startIndex + 1) + ' to ' + endIndex + ' of ' + totalEntries + ' entries');

                // Enable/disable navigation buttons
                $card.find('#js-prev-page-btn').prop('disabled', currentPage === 1).toggleClass('disabled', currentPage === 1);
                $card.find('#js-next-page-btn').prop('disabled', endIndex >= totalEntries).toggleClass('disabled', endIndex >= totalEntries);
                
                if (window.GreenLifePageFX && window.GreenLifePageFX.animateTableRows) {
                    window.GreenLifePageFX.animateTableRows();
                }
            }

            // Search filter
            $card.find('.activation-search-input').on('input', function () {
                var searchVal = $(this).val().toLowerCase().trim();
                currentPage = 1;

                if (!searchVal) {
                    filteredRows = [...originalRows];
                } else {
                    filteredRows = originalRows.filter(function (row) {
                        var rowText = $(row).text().toLowerCase();
                        return rowText.indexOf(searchVal) !== -1;
                    });
                }
                updateTable();
            });

            // Show entries select dropdown
            $card.find('.activation-show-select').on('change', function () {
                showCount = parseInt($(this).val(), 10);
                currentPage = 1;
                updateTable();
            });

            // Pagination button events
            $card.find('#js-prev-page-btn').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            $card.find('#js-next-page-btn').on('click', function () {
                var totalEntries = filteredRows.length;
                if (currentPage * showCount < totalEntries) {
                    currentPage++;
                    updateTable();
                }
            });

            // Simple sorting trigger
            $table.find('.activation-sort').on('click', function () {
                var $th = $(this);
                var colIndex = parseInt($th.data('column'), 10);

                if (currentSortCol === colIndex) {
                    currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSortCol = colIndex;
                    currentSortDir = 'asc';
                }

                // Reset sorting status headers
                $table.find('.activation-sort').removeClass('sort-asc sort-desc');
                $table.find('.activation-sort i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

                // Set current sorting status headers
                $th.addClass(currentSortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                $th.find('i').removeClass('fa-sort').addClass(currentSortDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down');

                currentPage = 1;
                updateTable();
            });

            // Run initial update
            updateTable();
        });

        // Prevent double submit for activation buy form
        $(document)
            .off('submit.activationBuySubmit', '.activation-buy-form')
            .on('submit.activationBuySubmit', '.activation-buy-form', function () {
                var $form = $(this);
                var $button = $form.find('.js-activation-submit');

                if ($button.hasClass('is-loading')) {
                    return false;
                }

                $button.addClass('is-loading');
                $button.prop('disabled', true);
                $button.find('span').text('Processing...');
            });

        // Mirror password mirroring is disabled because txn_password is now a visible manual input field.
        /*
        $(document)
            .off('input.mirrorPassword', '.signup-form input[name="password"]')
            .on('input.mirrorPassword', '.signup-form input[name="password"]', function () {
                $('#txn_password').val($(this).val());
            });

        $(document)
            .off('submit.mirrorPasswordSubmit', '.signup-form')
            .on('submit.mirrorPasswordSubmit', '.signup-form', function () {
                var passwordVal = $(this).find('input[name="password"]').val();
                $('#txn_password').val(passwordVal);
            });
        */

        // Live AJAX Sponsor validation
        var sponsorTimer = null;
        var sponsorXhr = null;

        function checkSponsorLive() {
            var $input = $('#sponsor');
            var $box = $('#sponhtml');

            if (!$input.length || !$box.length) {
                return;
            }

            var sponsor = $.trim($input.val()).toUpperCase();
            $input.val(sponsor);

            var url = $input.data('sponsor-check-url');

            if (!sponsor) {
                $box.html('');
                return;
            }

            if (!url) {
                $box.html('<span class="text-danger">Sponsor check route missing</span>');
                return;
            }

            clearTimeout(sponsorTimer);

            sponsorTimer = setTimeout(function () {
                if (sponsorXhr) {
                    sponsorXhr.abort();
                }

                $box.html('<span class="text-warning">Checking...</span>');

                sponsorXhr = $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        sponsor: sponsor,
                        _token: $('input[name="_token"]').first().val()
                    },
                    success: function (res) {
                        if (res) {
                            $box.html('<span class="text-success">Sponsor found: ' + (res.name || res.memberid || sponsor) + '</span>');
                        } else {
                            $box.html('<span class="text-danger">' + ((res && res.message) ? res.message : 'Invalid Sponsor Id') + '</span>');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.statusText === 'abort') {
                            return;
                        }
                        $box.html('<span class="text-danger">Sponsor check failed</span>');
                    }
                });
            }, 350);
        }

        $(document)
            .off('input.forceSponsorCheck blur.forceSponsorCheck change.forceSponsorCheck', '#sponsor')
            .on('input.forceSponsorCheck blur.forceSponsorCheck change.forceSponsorCheck', '#sponsor', checkSponsorLive);

        $(function () {
            if ($('#sponsor').val()) {
                checkSponsorLive();
            }
        });

        // Dynamic and submit-time Password Confirmation check
        $(document).on('input', 'input[name="password_confirmation"], input[name="password"]', function () {
            var passwordVal = $('input[name="password"]').val();
            var confirmPasswordVal = $('input[name="password_confirmation"]').val();
            if (passwordVal === confirmPasswordVal) {
                var $confirmField = $('input[name="password_confirmation"]').closest('.form-field');
                $confirmField.removeClass('has-error');
                $confirmField.parent().find('.confirm-mismatch-error').remove();
            }
        });

        $(document).on('submit', '.signup-form', function (e) {
            var passwordVal = $('input[name="password"]').val();
            var confirmPasswordVal = $('input[name="password_confirmation"]').val();
            if (passwordVal !== confirmPasswordVal) {
                e.preventDefault();
                
                var $confirmField = $('input[name="password_confirmation"]').closest('.form-field');
                $confirmField.addClass('has-error');
                $confirmField.parent().find('.confirm-mismatch-error').remove();
                $confirmField.after('<span class="field-error confirm-mismatch-error" style="color: #FF6B7A; font-size: 12px; font-weight: 700; display: block; margin-top: 4px;">Passwords do not match</span>');
                
                var $button = $(this).find('.green-button[type="submit"]');
                $button.removeClass('is-loading');
                $button.find('span').text('Create Account');
                return false;
            } else {
                $('input[name="password_confirmation"]').closest('.form-field').removeClass('has-error').parent().find('.confirm-mismatch-error').remove();
            }
        });
    };
    window.initInteractiveComponents();
    });
})(jQuery);

(function ($) {
    'use strict';

    window.GreenLife3DTransition = {
        containerSelectors: ['[data-page-container]', '.dashboard-content', '.dashboard-main', '.member-main'],
        isBusy: false,
        hasAnimatedInitial: false,
        timers: [],
        $beam: $(),
        $layer: $(),
        $flash: $(),
        $container: $(),

        init: function () {
            this.$container = this.getCurrentContainer();

            if (!this.$container.length) {
                return;
            }

            this.ensureElements();
            // Disabled AJAX link interception for emergency click restore
            // this.bindLinks();
            this.bindPopState();

            if (window.history && window.history.replaceState && !window.history.state) {
                window.history.replaceState({ url: window.location.href }, document.title, window.location.href);
            }

            if (!this.hasAnimatedInitial) {
                this.animateCards();
                this.animateRows();
                this.hasAnimatedInitial = true;
            }
        },

        bindLinks: function () {
            var self = this;

            $(document).off('click.glPageFx', 'a');
            $(document).off('click.gl3d', 'a');
            $(document).on('click.gl3d', 'a', function (event) {
                var $link = $(this);

                if (!self.isEligibleLink($link, event)) {
                    return;
                }

                event.preventDefault();
                self.navigate(new URL($link.attr('href'), window.location.origin).href);
            });
        },

        isEligibleLink: function ($link, event) {
            if (!$link || !$link.length || this.isBusy) {
                return false;
            }

            if (event) {
                if (event.isDefaultPrevented && event.isDefaultPrevented()) return false;
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return false;
                if (event.which && event.which !== 1) return false;
                if (event.button && event.button !== 0) return false;
            }

            if ($link.closest('form').length) return false;
            if ($link.attr('target') === '_blank') return false;
            if ($link.attr('download') !== undefined) return false;
            if ($link.attr('data-no-ajax') === 'true' || $link.data('noAjax') === true) return false;

            var href = $.trim($link.attr('href') || '');
            if (!href || href === '#' || href.charAt(0) === '#') return false;

            var lowerHref = href.toLowerCase();
            if (
                lowerHref.indexOf('javascript:') === 0 ||
                lowerHref.indexOf('mailto:') === 0 ||
                lowerHref.indexOf('tel:') === 0
            ) {
                return false;
            }

            var linkUrl;
            try {
                linkUrl = new URL(href, window.location.origin);
            } catch (error) {
                return false;
            }

            if (linkUrl.origin !== window.location.origin) return false;

            var memberPath = this.getMemberPath(linkUrl.pathname);
            if (!memberPath) return false;

            memberPath = this.normalizePath(memberPath);
            if (
                memberPath === '/member/login' ||
                memberPath === '/member/signup' ||
                memberPath === '/member/logout' ||
                memberPath.indexOf('/member/logout') === 0
            ) {
                return false;
            }

            if (linkUrl.hash && this.normalizePath(linkUrl.pathname) === this.normalizePath(window.location.pathname)) {
                return false;
            }

            return true;
        },

        navigate: function (url, options) {
            var self = this;
            var settings = $.extend({ push: true }, options || {});

            if (self.isBusy) {
                return;
            }

            self.isBusy = true;
            self.ensureElements();
            self.clearTimers();
            self.$container = self.getCurrentContainer();
            $('body').addClass('gl-3d-is-transitioning');

            self.startBeam();

            var exitReady = self.playExit();
            var portalReady = self.playPortal();
            var request = self.fetchPage(url);

            request.done(function (html) {
                $.when(exitReady, portalReady).done(function () {
                    var replaced = self.replaceContent(html, url);

                    if (!replaced) {
                        self.fallback(url);
                        return;
                    }

                    if (settings.push && window.history && window.history.pushState) {
                        window.history.pushState({ url: url }, document.title, url);
                    }

                    self.playEnter().always(function () {
                        self.finish();
                    });
                });
            }).fail(function (xhr) {
                if (xhr && (xhr.status === 401 || xhr.status === 419)) {
                    self.fallback(self.getLoginUrl());
                    return;
                }

                self.fallback(url);
            });
        },

        fetchPage: function (url) {
            return $.ajax({
                url: url,
                method: 'GET',
                dataType: 'html',
                cache: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html, */*; q=0.01'
                }
            });
        },

        getPageContainer: function ($html) {
            var selectors = this.containerSelectors;

            if (!$html || !$html.length) {
                return null;
            }

            for (var i = 0; i < selectors.length; i += 1) {
                var selector = selectors[i];
                var $match = $html.find(selector).first();

                if (!$match.length) {
                    $match = $html.filter(selector).first();
                }

                if ($match.length) {
                    return {
                        $el: $match,
                        selector: selector
                    };
                }
            }

            return null;
        },

        playExit: function () {
            var self = this;
            var deferred = $.Deferred();
            var $container = self.getCurrentContainer();
            var duration = self.prefersReducedMotion() ? 170 : 430;

            if (!$container.length) {
                deferred.resolve();
                return deferred.promise();
            }

            $container
                .removeClass('gl-3d-page-enter gl-3d-is-active')
                .addClass('gl-3d-page-exit');

            self.forceReflow($container);
            self.setTimer(function () {
                deferred.resolve();
            }, duration);

            return deferred.promise();
        },

        playPortal: function () {
            var self = this;
            var deferred = $.Deferred();
            var duration = self.prefersReducedMotion() ? 120 : 620;

            if (self.prefersReducedMotion()) {
                self.setTimer(function () {
                    deferred.resolve();
                }, duration);
                return deferred.promise();
            }

            self.positionLayer();
            self.$layer.removeClass('gl-3d-is-active');
            self.$flash.removeClass('gl-3d-is-active');
            self.forceReflow(self.$layer);

            self.$layer.addClass('gl-3d-is-active');
            self.$flash.addClass('gl-3d-is-active');

            self.setTimer(function () {
                deferred.resolve();
            }, duration);

            return deferred.promise();
        },

        replaceContent: function (html, url) {
            var $html = this.parseHtml(html);
            var responseInfo = this.getPageContainer($html);

            if (!responseInfo || !responseInfo.$el.length) {
                return false;
            }

            var currentInfo = this.getCurrentContainerInfo(responseInfo.selector);
            if (!currentInfo || !currentInfo.$el.length) {
                return false;
            }

            var $current = currentInfo.$el;
            var $incoming = responseInfo.$el;
            var incomingClass = $incoming.attr('class');
            var currentDataContainer = $current.attr('data-page-container');

            this.updateTitle($html);

            $current.removeClass('gl-3d-page-exit gl-3d-page-enter gl-3d-is-active');

            if (incomingClass) {
                $current.attr('class', incomingClass);
            }

            if (currentDataContainer !== undefined) {
                $current.attr('data-page-container', currentDataContainer);
            }

            $current.html($incoming.html());
            this.$container = $current;

            $('.dashboard-shell').scrollTop(0);
            $('html, body').scrollTop(0);

            if (window.initInteractiveComponents) {
                window.initInteractiveComponents();
            }

            this.updateSidebarActive(url);
            this.closeMobileSidebar();
            $(document).trigger('greenlife:page-loaded');

            return true;
        },

        playEnter: function () {
            var self = this;
            var deferred = $.Deferred();
            var $container = self.getCurrentContainer();
            var duration = self.prefersReducedMotion() ? 220 : 690;

            if (!$container.length) {
                deferred.resolve();
                return deferred.promise();
            }

            $container
                .removeClass('gl-3d-page-exit gl-3d-is-active')
                .addClass('gl-3d-page-enter');

            self.forceReflow($container);

            self.setTimer(function () {
                $container.addClass('gl-3d-is-active');
            }, 20);

            self.setTimer(function () {
                self.animateCards();
                self.animateRows();
            }, self.prefersReducedMotion() ? 30 : 90);

            self.setTimer(function () {
                deferred.resolve();
            }, duration);

            return deferred.promise();
        },

        updateTitle: function ($html) {
            var responseTitle = $.trim($html.find('title').first().text() || $html.filter('title').first().text() || '');
            var responseInfo = this.getPageContainer($html);
            var $newContainer = responseInfo ? responseInfo.$el : $html;

            var newPageTitle = $html.find('meta[name="member-page-title"]').attr('content')
                || $html.find('[data-page-title]').first().text()
                || $html.find('.page-title').first().text()
                || $html.find('.dashboard-title').first().text()
                || ($newContainer.length ? $newContainer.find('h1').first().text() : '')
                || responseTitle
                || 'Dashboard';

            $('[data-current-page-title]').text($.trim(newPageTitle));

            if (responseTitle) {
                document.title = responseTitle;
            } else {
                document.title = $.trim(newPageTitle) + ' - GreenLife Network';
            }

            return responseTitle;
        },

        updateSidebarActive: function (url) {
            var path = this.normalizePath(this.getPathFromUrl(url));
            var memberPath = this.normalizePath(this.getMemberPath(path) || path);
            var $matchingLink = $();

            $('.sidebar-nav .sidebar-link').removeClass('gl-3d-sidebar-pulse');

            $('.sidebar-nav .sidebar-link').each(function () {
                var href = $(this).attr('href') || '';
                var linkPath;

                if (!href || href === '#') {
                    return;
                }

                try {
                    linkPath = new URL(href, window.location.origin).pathname;
                } catch (error) {
                    linkPath = href;
                }

                var normalized = window.GreenLife3DTransition.normalizePath(linkPath);
                var normalizedMember = window.GreenLife3DTransition.normalizePath(window.GreenLife3DTransition.getMemberPath(normalized) || normalized);

                if (normalized === path || normalizedMember === memberPath) {
                    $matchingLink = $(this);
                    return false;
                }
            });

            if (!$matchingLink.length) {
                return;
            }

            var $nav = $('.sidebar-nav');
            var $wrapper = $matchingLink.closest('.sidebar-dropdown-wrapper');

            $nav.find('.sidebar-link').removeClass('is-active');
            $matchingLink.addClass('is-active gl-3d-sidebar-pulse');

            if ($wrapper.length) {
                $nav.find('.sidebar-dropdown-wrapper').not($wrapper)
                    .removeClass('is-expanded')
                    .find('.sidebar-submenu')
                    .stop(true, true)
                    .slideUp(180);

                $wrapper.addClass('is-expanded');
                $wrapper.find('.sidebar-submenu').stop(true, true).slideDown(180);
                $wrapper.children('.js-sidebar-dropdown-toggle').addClass('is-active gl-3d-sidebar-pulse');
            } else {
                $nav.find('.sidebar-dropdown-wrapper')
                    .removeClass('is-expanded')
                    .find('.sidebar-submenu')
                    .stop(true, true)
                    .slideUp(180);
            }

            this.setTimer(function () {
                $('.sidebar-nav .sidebar-link').removeClass('gl-3d-sidebar-pulse');
            }, 2900);
        },

        closeMobileSidebar: function () {
            if (window.matchMedia && window.matchMedia('(max-width: 991px)').matches) {
                $('body').removeClass('sidebar-open');
                $('.js-sidebar-toggle').attr('aria-expanded', 'false');
            }
        },

        bindPopState: function () {
            var self = this;

            $(window).off('popstate.glPageFx');
            $(window).off('popstate.gl3d');
            $(window).on('popstate.gl3d', function (event) {
                var state = event.originalEvent.state;
                var url = state && state.url ? state.url : window.location.href;
                self.navigate(url, { push: false });
            });
        },

        animateRows: function () {
            var self = this;
            var $rows = self.getCurrentContainer().find('table tbody tr:visible');

            $rows.each(function (index) {
                var $row = $(this);
                if (!$row.find('td').length) {
                    return;
                }

                $row
                    .removeClass('gl-3d-row-enter gl-3d-is-active')
                    .css('animation-delay', Math.min(index * 42, 760) + 'ms');

                self.forceReflow($row);
                $row.addClass('gl-3d-row-enter gl-3d-is-active');
            });
        },

        animateCards: function () {
            var self = this;
            var $container = self.getCurrentContainer();

            if (!$container.length) {
                return;
            }

            var heroSelectors = [
                '.page-hero-card',
                '.wallet-table-hero',
                '.wallet-hero',
                '.support-hero',
                '.referral-hero',
                '.income-hero',
                '.p2p-history-hero',
                '.fund-history-hero',
                'section[class*="hero"]'
            ];

            var statSelectors = [
                '.stat-card',
                '.page-hero-stat',
                '.support-stat-card',
                '.wallet-stat-card',
                '.referral-stat-card',
                '.income-stat-card',
                '.p2p-wallet-stat',
                '.fund-history-stat'
            ];

            var cardSelectors = [
                '.fund-history-card',
                '.income-card',
                '.p2p-history-card',
                '.p2p-wallet-card',
                '.wallet-table-card',
                '.wallet-transfer-card',
                '.support-form-card',
                '.support-table-card',
                '.referral-table-card',
                '.referral-tree-card',
                '.rank-progress-card',
                '.chart-card',
                '.mini-table-card',
                '.country-card',
                '.activity-card',
                '.reward-card',
                '.glass-surface:not(.member-sidebar):not(.member-header):not(.sidebar-rank):not(.stat-card)'
            ];

            var titleSelectors = [
                '.page-hero-title',
                '.wallet-title',
                '.support-title',
                '.referral-title',
                '.income-title',
                '.p2p-history-title',
                '.fund-history-title',
                '.section-heading h2'
            ];

            var $heroes = $container.find(heroSelectors.join(',')).filter(':visible');
            var $titles = $container.find(titleSelectors.join(',')).filter(':visible');
            var $stats = $container.find(statSelectors.join(',')).filter(':visible');
            var $cards = $container.find(cardSelectors.join(',')).filter(':visible').not($heroes).not($stats);

            $heroes.each(function (index) {
                self.activateEnterClass($(this), 'gl-3d-hero-enter', Math.min(index * 80, 220));
            });

            $titles.each(function (index) {
                self.activateEnterClass($(this), 'gl-3d-title-enter', Math.min(index * 45, 260));
            });

            $stats.each(function (index) {
                self.activateEnterClass($(this), 'gl-3d-stat-enter', Math.min(90 + (index * 55), 520));
            });

            $cards.each(function (index) {
                self.activateEnterClass($(this), 'gl-3d-card-enter', Math.min(140 + (index * 48), 620));
            });
        },

        finish: function () {
            var self = this;
            var $container = self.getCurrentContainer();

            self.$layer.removeClass('gl-3d-is-active');
            self.$flash.removeClass('gl-3d-is-active');
            self.$beam.removeClass('gl-3d-is-active').addClass('gl-3d-is-complete');

            if ($container.length) {
                $container.removeClass('gl-3d-page-exit gl-3d-page-enter gl-3d-is-active');
            }

            self.setTimer(function () {
                self.$beam.removeClass('gl-3d-is-complete');
                $('body').removeClass('gl-3d-is-transitioning');
                self.isBusy = false;
            }, 260);
        },

        fallback: function (url) {
            this.clearTimers();
            this.isBusy = false;
            $('body').removeClass('gl-3d-is-transitioning');
            window.location.href = url;
        },

        ensureElements: function () {
            if (!$('.gl-3d-top-beam').length) {
                $('body').append('<div class="gl-3d-top-beam" aria-hidden="true"></div>');
            }

            if (!$('.gl-3d-transition-layer').length) {
                $('body').append(
                    '<div class="gl-3d-transition-layer" aria-hidden="true">' +
                    '  <div class="gl-3d-portal-ring"></div>' +
                    '  <div class="gl-3d-glass-window">' +
                    '    <div class="gl-3d-window-box">' +
                    '      <span class="gl-3d-window-line"></span>' +
                    '      <span class="gl-3d-window-line"></span>' +
                    '      <span class="gl-3d-window-line"></span>' +
                    '      <span class="gl-3d-window-line"></span>' +
                    '    </div>' +
                    '  </div>' +
                    '  <div class="gl-3d-loader-text">Opening GreenLife Page</div>' +
                    '</div>'
                );
            }

            if (!$('.gl-3d-flash').length) {
                $('body').append('<div class="gl-3d-flash" aria-hidden="true"></div>');
            }

            this.$beam = $('.gl-3d-top-beam').first();
            this.$layer = $('.gl-3d-transition-layer').first();
            this.$flash = $('.gl-3d-flash').first();
        },

        getCurrentContainer: function () {
            var info = this.getCurrentContainerInfo();
            return info ? info.$el : $();
        },

        getCurrentContainerInfo: function (preferredSelector) {
            var selectors = preferredSelector ? [preferredSelector].concat(this.containerSelectors) : this.containerSelectors;

            for (var i = 0; i < selectors.length; i += 1) {
                var $match = $(selectors[i]).first();
                if ($match.length) {
                    return {
                        $el: $match,
                        selector: selectors[i]
                    };
                }
            }

            return null;
        },

        parseHtml: function (html) {
            return $('<div></div>').append($.parseHTML($.trim(html || ''), document, false) || []);
        },

        positionLayer: function () {
            var $container = this.getCurrentContainer();
            var rect;
            var viewportWidth = $(window).width();
            var viewportHeight = $(window).height();

            if ($container.length && $container[0].getBoundingClientRect) {
                rect = $container[0].getBoundingClientRect();
            }

            if (!rect || rect.width < 120 || rect.height < 120) {
                rect = {
                    top: 0,
                    left: 0,
                    width: viewportWidth,
                    height: viewportHeight
                };
            }

            var top = Math.max(0, rect.top);
            var left = Math.max(0, rect.left);
            var width = Math.max(1, Math.min(rect.width, viewportWidth - left));
            var height = Math.max(1, Math.min(Math.max(rect.height, 260), viewportHeight - top));

            this.$layer.css({
                top: top + 'px',
                left: left + 'px',
                width: width + 'px',
                height: height + 'px'
            });
        },

        startBeam: function () {
            this.$beam.removeClass('gl-3d-is-active gl-3d-is-complete');
            this.forceReflow(this.$beam);
            this.$beam.addClass('gl-3d-is-active');
        },

        activateEnterClass: function ($element, className, delay) {
            if (!$element || !$element.length) {
                return;
            }

            $element
                .removeClass(className + ' gl-3d-is-active')
                .css('animation-delay', delay + 'ms');

            this.forceReflow($element);
            $element.addClass(className + ' gl-3d-is-active');
        },

        prefersReducedMotion: function () {
            return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
        },

        normalizePath: function (path) {
            path = path || '/';
            path = path.split('?')[0].split('#')[0];
            path = path.replace(/\/+$/, '');
            return path || '/';
        },

        getPathFromUrl: function (url) {
            try {
                return new URL(url, window.location.origin).pathname;
            } catch (error) {
                return url || window.location.pathname;
            }
        },

        getMemberPath: function (path) {
            path = path || '';
            var match = path.match(/(^|\/)member(\/.*)?$/);

            if (!match) {
                return '';
            }

            return '/member' + (match[2] || '');
        },

        getLoginUrl: function () {
            var path = window.location.pathname;
            var memberIndex = path.toLowerCase().indexOf('/member/');

            if (memberIndex > -1) {
                return window.location.origin + path.slice(0, memberIndex) + '/member/login';
            }

            return window.location.origin + '/member/login';
        },

        forceReflow: function ($element) {
            if ($element && $element.length && $element[0]) {
                return $element[0].offsetHeight;
            }

            return 0;
        },

        setTimer: function (callback, delay) {
            var timer = window.setTimeout(callback, delay);
            this.timers.push(timer);
            return timer;
        },

        clearTimers: function () {
            while (this.timers.length) {
                window.clearTimeout(this.timers.pop());
            }
        }
    };

    $(function () {
        window.GreenLife3DTransition.init();
    });

})(jQuery);

(function ($) {
    'use strict';

    // Helper to extract page container from fetched HTML
    function getPageContainer($html) {
        var selectors = ['[data-page-container]', '.dashboard-content', '.dashboard-main', '.member-main'];
        for (var i = 0; i < selectors.length; i++) {
            var $el = $html.find(selectors[i]);
            if ($el.length) return { $el: $el, selector: selectors[i] };
            $el = $html.filter(selectors[i]);
            if ($el.length) return { $el: $el, selector: selectors[i] };
        }
        return null;
    }

    window.GreenLifePageFX = {
        init: function () {
            var self = this;

            // Dynamically inject loader elements if they don't exist
            if (!$('.gl-anim-top-loader').length) {
                $('body').append('<div class="gl-anim-top-loader"></div>');
            }
            if (!$('.gl-anim-loading-overlay').length) {
                $('body').append(
                    '<div class="gl-anim-loading-overlay">' +
                    '  <div class="gl-anim-loader-card">' +
                    '    <div class="gl-anim-spinner"></div>' +
                    '    <span style="font-family: inherit; font-size: 13.5px; font-weight: 700; color: var(--gl-text);">Loading GreenLife Page</span>' +
                    '  </div>' +
                    '</div>'
                );
            }

            // Bind click and submit handlers on document
            // Disabled AJAX link interception for emergency click restore
            // self.bindAjaxLinks();
            self.bindFormSubmits();

            // Bind popstate handler
            $(window).off('popstate.glPageFx');
            $(window).on('popstate.glPageFx', function (e) {
                var state = e.originalEvent.state;
                var url = (state && state.url) ? state.url : window.location.href;
                self.loadPage(url, false);
            });

            // Initial setup on hard page load
            $('body').addClass('gl-anim-ready');
            self.animatePageIn();
            self.checkForToasts();
        },

        bindAjaxLinks: function () {
            var self = this;
            $(document).off('click.glPageFx', 'a');
            $(document).on('click.glPageFx', 'a', function (event) {
                // Ignore middle clicks, ctrl clicks, etc.
                if (event.isDefaultPrevented()) return;
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.which === 2) return;

                var $link = $(this);
                var href = $link.attr('href');

                // Ignore anchors, void actions, and non-GET JS actions
                if (!href || href === '#' || href.indexOf('javascript:') === 0 || href.indexOf('#') === 0) return;

                // Ignore target="_blank", download, and data-no-ajax="true"
                if ($link.attr('target') === '_blank' || $link.attr('download') !== undefined || $link.attr('data-no-ajax') === 'true') return;

                // Resolve absolute URL to verify origin
                var linkUrl;
                try {
                    linkUrl = new URL(href, window.location.origin);
                } catch (e) {
                    return; // invalid URL
                }

                // Must be same origin
                if (linkUrl.origin !== window.location.origin) return;

                // Must start with /member/ but exclude login, signup, logout
                var path = linkUrl.pathname;
                if (path.indexOf('/member/') !== 0) return;
                if (path === '/member/login' || path === '/member/signup' || path === '/member/logout' || path.indexOf('/member/logout') !== -1) return;

                event.preventDefault();
                self.loadPage(linkUrl.href, true);
            });
        },

        bindFormSubmits: function () {
            $(document).off('submit.glPageFx', '[data-page-container] form, .dashboard-content form');
            $(document).on('submit.glPageFx', '[data-page-container] form, .dashboard-content form', function (e) {
                var $form = $(this);
                if (e.isDefaultPrevented()) return;
                if ($form.attr('data-no-loading') === 'true') return;
                if ($form.attr('action') && $form.attr('action').indexOf('logout') !== -1) return;
                if ($form.attr('id') === 'logout-form') return;

                var $btn = $form.find('button[type="submit"]');
                if ($btn.length) {
                    // Prevent double submit
                    if ($form.data('gl-submitting')) {
                        e.preventDefault();
                        return;
                    }
                    $form.data('gl-submitting', true);

                    var origText = $btn.html();
                    $btn.data('gl-orig-text', origText);

                    var text = 'Processing...';
                    var btnText = $btn.text().toLowerCase();
                    if (btnText.indexOf('create') !== -1) text = 'Creating...';
                    else if (btnText.indexOf('submit') !== -1) text = 'Submitting...';
                    else if (btnText.indexOf('add') !== -1) text = 'Adding...';
                    else if (btnText.indexOf('transfer') !== -1) text = 'Transferring...';

                    // Update text and apply spinner style
                    var $span = $btn.find('span');
                    if ($span.length) {
                        $span.text(text);
                    } else {
                        $btn.text(text);
                    }
                    $btn.addClass('gl-anim-btn-loading');

                    // Disable button slightly after event to let submit trigger in browser
                    setTimeout(function () {
                        $btn.prop('disabled', true);
                    }, 0);
                }
            });
        },

        loadPage: function (url, pushToHistory) {
            var self = this;
            self.showLoader();
            self.showSkeleton();

            var startTime = Date.now();

            self.animatePageOut(function () {
                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        var $html = $(response);
                        var containerInfo = getPageContainer($html);

                        if (containerInfo) {
                            var responseTitle = $html.filter('title').text() || $html.find('title').text();
                            var newPageTitle = $html.find('meta[name="member-page-title"]').attr('content')
                                || $html.find('[data-page-title]').first().text()
                                || $html.find('.page-title').first().text()
                                || $html.find('.dashboard-title').first().text()
                                || containerInfo.$el.find('h1').first().text()
                                || responseTitle
                                || 'Dashboard';

                            $('[data-current-page-title]').text($.trim(newPageTitle));
                            if (responseTitle) {
                                document.title = responseTitle;
                            } else {
                                document.title = $.trim(newPageTitle) + ' - GreenLife Network';
                            }

                            if (pushToHistory) {
                                history.pushState({ url: url }, document.title, url);
                            }

                            // Keep skeleton visible for at least 300ms to guarantee smooth transition
                            var elapsed = Date.now() - startTime;
                            var delay = Math.max(0, 300 - elapsed);

                            setTimeout(function () {
                                var $currentContainer = $(containerInfo.selector);
                                if ($currentContainer.length) {
                                    $currentContainer.html(containerInfo.$el.html());
                                    $currentContainer.attr('class', containerInfo.$el.attr('class'));
                                }

                                // Reset scroll position
                                $('.dashboard-shell').scrollTop(0);
                                $('html, body').scrollTop(0);

                                self.hideSkeleton();
                                self.hideLoader();
                                self.animatePageIn();
                                self.reinitializePageScripts();
                                self.updateSidebarActive(url);

                                // Close mobile sidebar if open
                                $('body').removeClass('sidebar-open');
                            }, delay);
                        } else {
                            window.location.href = url;
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 401 || xhr.status === 419) {
                            window.location.href = '/member/login';
                        } else {
                            window.location.href = url;
                        }
                    }
                });
            });
        },

        animatePageIn: function () {
            var $container = $('.dashboard-content');
            if ($container.length) {
                $container.removeClass('gl-anim-page-leave active gl-anim-page-enter')
                          .addClass('gl-anim-page-enter');
                
                // Reflow
                $container[0].offsetHeight;
                
                $container.addClass('active');

                this.animateHeroAndCards();
                this.animateTableRows();
            }
        },

        animatePageOut: function (callback) {
            var $container = $('.dashboard-content');
            if ($container.length) {
                $container.removeClass('gl-anim-page-enter active gl-anim-page-leave')
                          .addClass('gl-anim-page-leave');
                
                // Reflow
                $container[0].offsetHeight;
                
                $container.addClass('active');

                setTimeout(function () {
                    if (callback) callback();
                }, 180);
            } else {
                if (callback) callback();
            }
        },

        animateHeroAndCards: function () {
            var $container = $('.dashboard-content');

            var heroSelectors = [
                '.page-hero-card', '.wallet-table-hero', '.wallet-hero',
                '.support-hero', '.referral-hero', '.income-hero',
                '.p2p-history-hero', '.fund-history-hero', 'section[class*="hero"]'
            ];
            var $heros = $container.find(heroSelectors.join(','));
            $heros.addClass('gl-anim-hero-enter');

            var cardSelectors = [
                '.fund-history-card', '.income-card', '.p2p-history-card',
                '.p2p-wallet-card', '.wallet-table-card', '.wallet-transfer-card',
                '.support-form-card', '.support-table-card', '.referral-table-card',
                '.referral-tree-card', '.glass-surface:not(.stat-card)'
            ];
            var $cards = $container.find(cardSelectors.join(','));
            $cards.addClass('gl-anim-card-enter');

            if ($heros.length || $cards.length) {
                setTimeout(function () {
                    $heros.addClass('active');
                    $cards.addClass('active');
                }, 40);
            }
        },

        animateTableRows: function () {
            var $rows = $('.dashboard-content table tbody tr:visible');
            $rows.each(function (index) {
                var $row = $(this);
                if ($row.find('td').length === 0) return;

                $row.addClass('gl-anim-row-enter');
                var delay = (index * 45) + 'ms';
                $row.css({
                    'transition-delay': delay,
                    '-webkit-transition-delay': delay
                });

                setTimeout(function () {
                    $row.addClass('active');
                }, 20);
            });
        },

        showLoader: function () {
            var $loader = $('.gl-anim-top-loader');
            if ($loader.length) {
                $loader.removeClass('complete').addClass('loading');
            }
            var $overlay = $('.gl-anim-loading-overlay');
            if ($overlay.length) {
                $overlay.addClass('visible');
            }
        },

        hideLoader: function () {
            var $loader = $('.gl-anim-top-loader');
            if ($loader.length) {
                $loader.removeClass('loading').addClass('complete');
                setTimeout(function () {
                    $loader.removeClass('complete');
                }, 250);
            }
            var $overlay = $('.gl-anim-loading-overlay');
            if ($overlay.length) {
                $overlay.removeClass('visible');
            }
        },

        showSkeleton: function () {
            var $container = $('.dashboard-content');
            if ($container.length) {
                var skeletonHtml =
                    '<div class="gl-anim-skeleton-wrap">' +
                    '  <div class="gl-anim-skeleton gl-anim-skeleton-hero"></div>' +
                    '  <div class="gl-anim-skeleton-wrap" style="flex-direction: row; gap: 16px;">' +
                    '    <div class="gl-anim-skeleton gl-anim-skeleton-row" style="flex: 1;"></div>' +
                    '    <div class="gl-anim-skeleton gl-anim-skeleton-row" style="flex: 1;"></div>' +
                    '  </div>' +
                    '  <div class="gl-anim-skeleton gl-anim-skeleton-card" style="height: 220px;"></div>' +
                    '</div>';
                $container.html(skeletonHtml);
            }
        },

        hideSkeleton: function () {
            $('.gl-anim-skeleton-wrap').remove();
        },

        checkForToasts: function () {
            var self = this;
            $('.glass-alert.success, .alert-success').each(function () {
                var text = $(this).text().trim();
                if (text) self.showToast(text, 'success');
            });
            $('.glass-alert.danger, .alert-danger, .alert-error').each(function () {
                var text = $(this).text().trim();
                if (text) self.showToast(text, 'error');
            });
            $('.glass-alert.warning, .alert-warning').each(function () {
                var text = $(this).text().trim();
                if (text) self.showToast(text, 'warning');
            });
        },

        showToast: function (message, type) {
            var $wrap = $('.gl-anim-toast-wrap');
            if (!$wrap.length) {
                $wrap = $('<div class="gl-anim-toast-wrap" aria-live="polite"></div>').appendTo('body');
            }

            var iconClass = 'fa-circle-info';
            var toastClass = '';
            if (type === 'success') {
                iconClass = 'fa-circle-check';
                toastClass = 'gl-anim-toast-success';
            } else if (type === 'error') {
                iconClass = 'fa-circle-xmark';
                toastClass = 'gl-anim-toast-error';
            } else if (type === 'warning') {
                iconClass = 'fa-circle-exclamation';
                toastClass = 'gl-anim-toast-warning';
            }

            var $toast = $(
                '<div class="gl-anim-toast ' + toastClass + '">' +
                '  <i class="fa-solid ' + iconClass + '"></i>' +
                '  <span>' + message + '</span>' +
                '</div>'
            );

            $wrap.append($toast);

            setTimeout(function () {
                $toast.addClass('visible');
            }, 50);

            setTimeout(function () {
                $toast.removeClass('visible');
                setTimeout(function () {
                    $toast.remove();
                }, 300);
            }, 4000);
        },

        reinitializePageScripts: function () {
            if (window.initInteractiveComponents) {
                window.initInteractiveComponents();
            }
            this.checkForToasts();
            $(document).trigger('greenlife:page-loaded');
        },

        updateSidebarActive: function (url) {
            var path = '';
            try {
                path = new URL(url, window.location.origin).pathname;
            } catch (e) {
                path = url;
            }

            $('.sidebar-link').removeClass('gl-anim-sidebar-pulse');

            var $matchingLink = null;
            $('.sidebar-link').each(function () {
                var href = $(this).attr('href') || '';
                if (href === '#' || href === '') return;

                var linkPath = '';
                try {
                    linkPath = new URL(href, window.location.origin).pathname;
                } catch (e) {
                    linkPath = href;
                }

                if (path === linkPath) {
                    $matchingLink = $(this);
                    return false;
                }
            });

            if ($matchingLink && $matchingLink.length) {
                $('.sidebar-link').removeClass('is-active');
                $matchingLink.addClass('is-active gl-anim-sidebar-pulse');

                var $wrapper = $matchingLink.closest('.sidebar-dropdown-wrapper');
                if ($wrapper.length) {
                    if (!$wrapper.hasClass('is-expanded')) {
                        $('.sidebar-dropdown-wrapper').not($wrapper).removeClass('is-expanded').find('.sidebar-submenu').slideUp(220);
                        $wrapper.addClass('is-expanded');
                        $wrapper.find('.sidebar-submenu').slideDown(220);
                    }
                    $wrapper.find('.js-sidebar-dropdown-toggle').addClass('is-active');
                } else {
                    $('.sidebar-dropdown-wrapper').removeClass('is-expanded').find('.sidebar-submenu').slideUp(220);
                    $('.js-sidebar-dropdown-toggle').removeClass('is-active');
                }
            }
        }
    };

    $(function () {
        window.GreenLifePageFX.init();
    });

})(jQuery);

// Language dropdown removed: English-only project
window.GreenLifeLang = {
    storageKey: 'greenlife-member-language',
    current: 'en',
    normalizeCode: function(c) { return 'en'; },
    init: function () {},
    bind: function () {},
    apply: function () {},
    updateDropdown: function () {},
    translatePage: function () {},
    translateNodeText: function () {},
    translatePlaceholders: function () {},
    translatePatterns: function () {},
    refreshAfterAjax: function () {}
};

(function ($) {
    'use strict';

    $(function () {
        try {
            localStorage.removeItem('greenlife-member-language');
        } catch (e) {}

        // Reset document direction to English LTR default
        $('html, body').removeClass('language-rtl gl-3d-loading gl-anim-loading is-loading').attr('dir', 'ltr');

        // Turn off all language namespaces
        $(document).off('.greenlifeLang');
        $(document).off('.greenlifeLanguageDropdown');
        $(document).off('.glForceLang');
        $(document).off('.glForceLangOption');
        $(document).off('.glForceLangOutside');
        $(document).off('.glForceLangEsc');
        $(document).off('.greenlifeLangToggle');
        $(document).off('.greenlifeLangSelect');
        $(document).off('.greenlifeLangOutside');
        $(document).off('.greenlifeLangEsc');
        $(document).off('.greenlifeLangRefresh');

        // Emergency safety: ensure loading overlays are inactive on initial load
        $('.gl-3d-transition-layer, .gl-anim-loading-overlay, .gl-anim-skeleton-wrap').css({
            'pointer-events': 'none'
        });
    });

})(jQuery);

/* Premium Geneology Tree Patch */
document.addEventListener('DOMContentLoaded', function () {
    const popover =
        document.getElementById('geneologyDetailsPopover') ||
        document.getElementById('geneologyHoverCard') ||
        document.getElementById('geneologyPopover') ||
        document.getElementById('genealogyDetailsPopover') ||
        document.getElementById('genealogyHoverCard');

    if (!popover) {
        return;
    }

    function setPopoverText(key, value) {
        const el = popover.querySelector('[data-hover="' + key + '"]');
        if (el) {
            el.textContent = value || 'N/A';
        }
    }

    function fillPopover(card) {
        setPopoverText('memberid', card.getAttribute('data-memberid'));
        setPopoverText('name', card.getAttribute('data-name'));
        setPopoverText('email', card.getAttribute('data-email'));
        setPopoverText('sponsorid', card.getAttribute('data-sponsorid'));
        setPopoverText('rank', card.getAttribute('data-rank'));
        setPopoverText('status', card.getAttribute('data-status'));
    }

    function positionPopoverNextToCard(card) {
        const gap = 12;
        const viewportPadding = 12;
        const cardRect = card.getBoundingClientRect();

        popover.style.display = 'block';
        popover.style.visibility = 'hidden';
        popover.style.position = 'fixed';

        const popRect = popover.getBoundingClientRect();

        /*
            MAIN RULE:
            Popup must be just right side of the hovered card.
            Not near mouse cursor.
            Not far away.
        */
        let left = cardRect.right + gap;
        let top = cardRect.top + (cardRect.height / 2) - (popRect.height / 2);

        /*
            If right side does not have space,
            show popup just left side of same card.
        */
        if (left + popRect.width > window.innerWidth - viewportPadding) {
            left = cardRect.left - popRect.width - gap;
        }

        /*
            Keep popup inside viewport.
        */
        if (left < viewportPadding) {
            left = viewportPadding;
        }

        if (top < viewportPadding) {
            top = viewportPadding;
        }

        if (top + popRect.height > window.innerHeight - viewportPadding) {
            top = window.innerHeight - popRect.height - viewportPadding;
        }

        popover.style.left = left + 'px';
        popover.style.top = top + 'px';
        popover.style.visibility = 'visible';
    }

    const cards = document.querySelectorAll(
        '.geneology-hover-target, .geneology-card, .geneology-pro-card, .geneology-node-card, .geneology-member-card-clean, .geneology-pro-member-card, .genealogy-card'
    );

    cards.forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            fillPopover(card);
            positionPopoverNextToCard(card);
        });

        card.addEventListener('mousemove', function () {
            positionPopoverNextToCard(card);
        });

        card.addEventListener('mouseleave', function () {
            popover.style.display = 'none';
            popover.style.visibility = 'hidden';
        });
    });

    /*
        Hide popup while scrolling the genealogy board/page,
        so it does not stay at old card position.
    */
    document.addEventListener('scroll', function () {
        popover.style.display = 'none';
        popover.style.visibility = 'hidden';
    }, true);

    window.addEventListener('resize', function () {
        popover.style.display = 'none';
        popover.style.visibility = 'hidden';
    });

    const canvas = document.getElementById('geneologyTreeCanvas');
    const board = document.getElementById('geneologyBoard');
    const zoomText = document.querySelector('[data-geneology-zoom-text]');

    let zoom = 1;

    function applyGeneologyZoom() {
        if (!canvas) return;
        canvas.style.transform = 'scale(' + zoom + ')';
        canvas.style.transformOrigin = 'top center';

        if (zoomText) {
            zoomText.textContent = Math.round(zoom * 100) + '%';
        }
    }

    document.querySelectorAll('[data-geneology-zoom-in]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            zoom = Math.min(1.8, zoom + 0.1);
            applyGeneologyZoom();
        });
    });

    document.querySelectorAll('[data-geneology-zoom-out]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            zoom = Math.max(0.45, zoom - 0.1);
            applyGeneologyZoom();
        });
    });

    document.querySelectorAll('[data-geneology-zoom-reset]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            zoom = 1;
            applyGeneologyZoom();
            if (board) {
                board.scrollLeft = Math.max(0, (board.scrollWidth - board.clientWidth) / 2);
                board.scrollTop = 0;
            }
        });
    });

    document.querySelectorAll('[data-geneology-fullscreen]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!board) return;

            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                board.requestFullscreen().catch(function () {});
            }
        });
    });

    applyGeneologyZoom();

    if (board) {
        setTimeout(function () {
            board.scrollLeft = Math.max(0, (board.scrollWidth - board.clientWidth) / 2);
        }, 300);
    }
});
