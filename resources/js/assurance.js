require('datatables.net-bs5');
require( 'datatables.net-responsive-bs5' );
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
let table;
$(document).ready(function () {
    table = $('#DataTable_assurances').DataTable({
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
    });
    $('.delButton').on('click',function () {
        supModal(this);
    })
})

var myModal = new bootstrap.Modal(document.getElementById('delModal'));
var delToastEl = document.getElementById('toastSupp');
var delToast = bootstrap.Toast.getOrCreateInstance(delToastEl);
function supModal(row){
    let id_assurance = $(row).attr('data-voiture');
    let assurance = table.row($(row).parent().parent().parent());
    myModal.show();
    $('#btnDelModal').on('click',function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"DELETE",
            url: '/assurance/delete/'+id_assurance,
            success:function () {
                assurance.remove().draw();
                myModal.hide();
                delToast.show();
            }
        })
    })
}
