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
    let id_users = $(row).attr('data-voiture');
    myModal.show();
    $('#btnDelModal').on('click',function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"DELETE",
            url: '/admin/user/delete/'+id_users,
            success:function () {
                myModal.hide();
                delToast.show();
                window.location.href = '/login';
            }
        })
    })
}
