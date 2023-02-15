window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
$(document).ready(function () {
    $('.delButton').on('click',function () {
        supModal(this);
    });
    $('#btn_first_name').on('click',function () {
        form_first_name();
    });
    $('#btn_last_name').on('click',function () {
        form_last_name();
    });
    $('#btn_email').on('click',function () {
        form_email();
    });
    $('#btn_password').on('click',function () {
       form_password();
    });
    $('.modal').on('hidden.bs.modal', function () {
        resetErrors();
    });

})

var myModal = new bootstrap.Modal(document.getElementById('delModal'));
var delToastEl = document.getElementById('toastSupp');
var delToast = bootstrap.Toast.getOrCreateInstance(delToastEl);
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
    let formInput = $('.form-control');
    formInput.removeClass('is-invalid');
    formInput.val('');
    $('.alert').addClass('d-none');
    $('.invalid-feedback strong').html('');
}

function ajaxForm(dataVal,dataUrl){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:"post",
        url:dataUrl,
        data: dataVal,
        success:function (data) {
            if($.isEmptyObject(data.error)){
                resetErrors();
                $('.alert').removeClass('d-none');
                $('.modal.fade.show').find('.alert').html(data.success);
                $.each( data.datas, function( key, value ) {
                    console.log(key.matches('password'))
                    if(key !== 'id'){
                        $('.'+key+"_text").html(value);
                    }
                });
            }else{
                $.each( data.error, function( key, value ) {
                    let typeSelect = (key === "old_password") ? ".modal.fade.show ." : "#";
                    $(typeSelect+key).addClass('is-invalid');
                    $(typeSelect+key).parent().find(".invalid-feedback strong").html(value);
                });
            }
        }
    })
}

function form_first_name() {
    let obj = {
        "id" : $('.id_user').val(),
        "first_name" : $('#first_name').val(),
        "old_password":$('.modal.fade.show .old_password').val(),
        "required_first_name": true
    };
    ajaxForm(obj,'/user/edit/first_name');
}
function form_last_name() {
    let obj = {
        "id" : $('.id_user').val(),
        "last_name" : $('#last_name').val(),
        "old_password":$('.modal.fade.show .old_password').val(),
        "required_last_name": true
    };
    ajaxForm(obj,'/user/edit/last_name');
}
function form_email(){
    let obj = {
        "id" : $('.id_user').val(),
        "email" : $('#email').val(),
        "old_password":$('.modal.fade.show .old_password').val(),
        "required_email": true
    };
    ajaxForm(obj,'/user/edit/email');
}
function form_password() {
    let obj = {
        "id" : $('.id_user').val(),
        "new_password" : $('#new_password').val(),
        "new_password_confirmation" : $('#new_password_confirmation').val(),
        "old_password":$('.modal.fade.show .old_password').val(),
        "required_password": true
    };
    ajaxForm(obj,'/user/edit/password');
}
