window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
$(document).ready(function () {
    $('.delButton').on('click',function () {
        supModal(this);
    })
})

var myModal = new bootstrap.Modal(document.getElementById('delModal'));
var delToastEl = document.getElementById('toastSupp');
var delToast = bootstrap.Toast.getOrCreateInstance(delToastEl);
function supModal(row){
    let id_voiture = $(row).attr('data-voiture');
    let voiture = $(row).parent();
    myModal.show();
    $('#btnDelModal').on('click',function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"DELETE",
            url: '/voiture-fournisseur/delete/'+id_voiture,
            success:function () {
                voiture.remove();
                myModal.hide();
                delToast.show();
            }
        })
    })
}
