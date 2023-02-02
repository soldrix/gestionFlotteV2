window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
require('datatables.net-bs5');
require( 'datatables.net-responsive-bs5' );
let tableEnt;
let tableRep;
let tableCons;
let tableAssu;

function initDatatable(){

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

    if($('#DataTable_entretiens').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_entretiens' )){
            tableEnt = $('#DataTable_entretiens').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_assurances').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_assurances' )){
            tableAssu = $('#DataTable_assurances').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_reparations').length > 0) {
        if (!$.fn.DataTable.isDataTable('#DataTable_reparations')) {
            tableRep = $('#DataTable_reparations').DataTable(DataTableOption);
        }
    }
    if($('#DataTable_carburants').length > 0){
        if(! $.fn.DataTable.isDataTable( '#DataTable_carburants' )){
            tableCons = $('#DataTable_carburants').DataTable(DataTableOption);
        }
    }
}


$(document).ready(function () {
    initDatatable();
    $('.delButton').on('click',function () {
        supModal(this);
    })
    $('.tab-pane').on('click',function () {
        initDatatable()
    })
})

var myModal = new bootstrap.Modal(document.getElementById('delModal'));
var delToastEl = document.getElementById('toastSupp');
var delToast = bootstrap.Toast.getOrCreateInstance(delToastEl);
function supModal(row){
    let id_voiture = $(row).attr('data-voiture');
    //récupère le nom de table de la colonne à supprimé
    let db = $(row).attr('data-db');
    //sélectionne la colonne à supprimer par rapport à la table
    let voiture = (db === 'assurance') ? tableAssu.row($(row).parent().parent().parent()) : (db === 'entretien') ? tableEnt.row($(row).parent().parent().parent()) :
        (db === 'reparation') ? tableRep.row($(row).parent().parent().parent()) :
            (db === 'consommation') ? tableCons.row($(row).parent().parent().parent()): '';
    myModal.show();
    $('#btnDelModal').on('click',function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"DELETE",
            //url de suppression par rapport à la table
            url: '/'+db+'/delete/'+id_voiture,
            success:function () {
                //supprime la colonne du datatable
                voiture.remove().draw();
                myModal.hide();
                delToast.show();
            }
        })
    })
}
