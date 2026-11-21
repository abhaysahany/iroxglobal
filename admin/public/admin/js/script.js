document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector('[data-sidebar-toggle]');
    const closeArea = document.querySelector('[data-sidebar-close]');

    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });
    }

    if (closeArea) {
        closeArea.addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    }
});

if (window.jQuery && $.fn.DataTable) {
    $(document).ready(function () {
        $('.admin-datatable').each(function () {
            const $table = $(this);
            const $tableWrap = $table.closest('.table-wrap');
            const $emptyCell = $table.find('tbody tr td[colspan]').first();
            const emptyMessage = $emptyCell.length ? $.trim($emptyCell.text()) : 'No records found.';
            const scrollX = $table.data('scroll-x') !== false;

            if ($emptyCell.length) {
                $emptyCell.closest('tr').remove();
            }

            const dataTable = $table.DataTable({
                responsive: false,
                scrollX: scrollX,
                autoWidth: false,
                destroy: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-md-end"f>>' +
                    '<"row mb-3"<"col-md-12"B>>' +
                    '<"row"<"col-md-12"tr>>' +
                    '<"row mt-3"<"col-md-6"i><"col-md-6 text-md-end"p>>',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-secondary btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info btn-sm'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-primary btn-sm'
                    }
                ],
                language: {
                    emptyTable: emptyMessage,
                    search: 'Search:',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    paginate: {
                        previous: 'Previous',
                        next: 'Next'
                    }
                }
            });

            if ($tableWrap.length) {
                $tableWrap.addClass('datatable-ready');
                $tableWrap.next('nav, .pagination').hide();
            }

            setTimeout(function () {
                dataTable.columns.adjust();
            }, 100);
        });
    });
}
