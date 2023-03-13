window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
require('datatables.net-bs5');
require( 'datatables.net-responsive-bs5' );
//fonction pour initialiser les datatable
function initDatatable(){
    //options du datatable
    let DataTableOption = {
        "language": {
            "lengthMenu": "Afficher _MENU_ entrées",
            "zeroRecords": "Aucune entrée correspondante trouvée",
            "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
            "infoFiltered": "(filtrées depuis un total de _MAX_ entrées)",
            "search": "Rechercher:",
            "paginate": {
                "first": "Première",
                "last": "Dernière",
                "next": "Suivant",
                "previous": "Précédent"
            },
        },
        paging : false,
        responsive :true,
        columnDefs :[
            {  responsivePriority: 1, targets: 0},
            {  responsivePriority: 2, targets: -2},
            {  responsivePriority: 3, targets: -1}
        ]
    };
    //initialiser tout les tableaux existant
    $.each($('.dataTable'),function (datas) {
        let tableId = $('.dataTable').eq(datas).attr('id');
        if(tableId.length > 0){
            if(! $.fn.DataTable.isDataTable( '#'+tableId )){
                //initialise le datatable avec les options
                $('#'+tableId).DataTable(DataTableOption);
            }
        }
    })

}


$(document).ready(function () {
    //pour afficher le premier datatable disponible
    $('.tabsHome').first().addClass('active');
    $('.contentHome').first().addClass('active show');
    initDatatable();
    //évènement au click des tabs pour initialiser le nouveau datatable
    $('.tab-pane').on('click',function () {
        initDatatable()
    })
})
