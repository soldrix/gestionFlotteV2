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
    //vérifie si le tableau est existe
    if($('#DataTable_entretiens').length > 0){
        //vérifie si le tableau n'est pas un datatable
        if(! $.fn.DataTable.isDataTable( '#DataTable_entretiens' )){
            //initialise le datatable avec les options
            $('#DataTable_entretiens').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_assurances').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_assurances' )){
            $('#DataTable_assurances').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_reparations').length > 0) {
        if (!$.fn.DataTable.isDataTable('#DataTable_reparations')) {
            $('#DataTable_reparations').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_carburants').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_carburants' )){
            $('#DataTable_carburants').DataTable(DataTableOption);
        }
    }

    if($('#DataTable_location').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_location' )){
            $('#DataTable_location').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_users').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_users' )){
            $('#DataTable_users').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_voitures').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_voitures' )){
            $('#DataTable_voitures').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_fournisseur').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_fournisseur' )){
            $('#DataTable_fournisseur').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_agence').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_agence' )){
            $('#DataTable_agence').DataTable(DataTableOption);
        }
    }
}


$(document).ready(function () {
    //pour afficher le premier datatable disponible
    $('.tabsHome').first().addClass('active');
    $('.contentHome').first().addClass('active');
    $('.contentHome').first().addClass('show');
    initDatatable();
    //évènement au click des tabs pour initialiser le nouveau datatable
    $('.tab-pane').on('click',function () {
        initDatatable()
    })
})
