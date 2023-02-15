window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
$(document).ready(function () {
    $('.delButton').on('click',function () {
        supModal(this);
    })
    $('#btn_first_name').on('click',function () {
        form_first_name();
    })
    $('.btn_close_modal').on('click',function () {
        resetErrors();
    })
})

var myModal = new bootstrap.Modal(document.getElementById('delModal'));
var delToastEl = document.getElementById('toastSupp');
var delToast = bootstrap.Toast.getOrCreateInstance(delToastEl);
function setupForm(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}
function supModal(row){
    let id_users = $(row).attr('data-voiture');
    myModal.show();
    $('#btnDelModal').on('click',function () {
        setupForm()
        $.ajax({
            type:"DELETE",
            url: '/user/delete/'+id_users,
            success:function () {
                myModal.hide();
                delToast.show();
                window.location.href = '/login';
            }
        })
    })
}

function resetErrors(){
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback strong').html('');
}


// var modal_first_name = new bootstrap.Modal(document.getElementById('modal_first_name'));
function form_first_name() {
    // modal_first_name.show()
    setupForm()
    $.ajax({
        type:"post",
        url:'/user/edit/first_name/',
        data: {"id" : $('#id_user').val(),"first_name" : $('#first_name').val(),"old_password":$('#old_password').val()},
        success:function (data) {
            if($.isEmptyObject(data.error)){
                resetErrors();

                $.each( data.datas, function( key, value ) {
                    if(key !== 'id'){
                        $('.'+key+"_text").html(value)
                    }
                });
                console.log(data.success);
            }else{
                $.each( data.error, function( key, value ) {
                    $("#"+key).addClass('is-invalid')
                    $('#'+key).parent().find(".invalid-feedback strong").html(value);
                });
            }
        }
    })
}
