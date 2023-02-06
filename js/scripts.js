window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    
});

$(document).ready( function () {
    var table = $('#table').DataTable( {
        "dom": 'lrtip',
        responsive: false,
        scrollX: true,
        scrollY: true,
        fixedColumns: true,
        fixedHeader: true,
        "pagingType": "simple_numbers",
        "lengthChange": false,
        "language": {
            "decimal":        "",
            "emptyTable":     "Brak rekordów w bazie herbat",
            "info":           "Wyświetla _START_ do _END_ z _TOTAL_ wyników",
            "infoEmpty":      "Wyświetla zero wyników",
            "infoFiltered":   "(wybrane z _MAX_ wszystkich wyników)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Show _MENU_ entries",
            "loadingRecords": "Ładowanie...",
            "processing":     "",
            "search":         "Wyszukaj:",
            "zeroRecords":    "Nie znaleziono pasujących wyników",
            "paginate": {
                "first":      "Początek",
                "last":       "Koniec",
                "next":       "Następne",
                "previous":   "Poprzednie"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    } );
    
    $('#search_input').keyup(function(){
        table.search($(this).val()).draw() ;
   })

});

