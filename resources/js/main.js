window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
require('jquery-mask-plugin/dist/jquery.mask.js');
import flatpickr from "flatpickr";


let prixPJ;//prix par jours



$(document).ready(function () {
    //profil
    var profilEmail = new bootstrap.Modal(document.getElementById('modifEmailmodal'));
    var profilName = new bootstrap.Modal(document.getElementById('modifNamemodal'));
    var profilPassword = new bootstrap.Modal(document.getElementById('modifPasswordmodal'));
    var emailModal = document.getElementById('modifEmailmodal');
    var nameModal = document.getElementById('modifNamemodal');
    var passwordModal = document.getElementById('modifPasswordmodal');
    emailModal.addEventListener('shown.bs.modal', function () {
        $('.modal.fade.show .passwordProfil,.emailProfil').on('keyup',function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                $('#btnProfilEmail').click();
            }
        });
    });
    nameModal.addEventListener('shown.bs.modal', function () {
        $('.modal.fade.show .passwordProfil,#nameProfil').on('keyup',function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                $('#btnProfilName').click();
            }
        });
    });
    passwordModal.addEventListener('shown.bs.modal', function () {
        $('.modal.fade.show .passwordProfil,#newPassword').on('keyup',function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                $('#btnProfilPassword').click();
            }
        });
    });

   $('#btnProfilEmail').on('click',function () {
       var emailS = $('.modal.fade.show .emailProfil');
       var passwordS = $('.modal.fade.show .passwordProfil');
       $('.emailProfil').removeClass('is-invalid');
       $('.passwordProfil').removeClass('is-invalid');
       $('.errormsg').html('');
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       $.ajax({
           url:'/updateEmail',
           type:'post',
           data:{
               'email' : emailS.val(),
               'password' : passwordS.val()
           },
           success:function (datas) {
               passwordS.val('');
               $('#textEmail').html(emailS.val())
               profilEmail.hide();
                $('#profilContent').html(`
                    <div class="alert alert-success" role="alert">
                        <strong>Succès: </strong> ${datas}
                    </div>
                `)
           },
           error: function(xhr) {
               var err = eval("(" + xhr.responseText + ")");
               if(err.errors.email !== undefined){
                   emailS.addClass('is-invalid')
                   $('#errorEmail').html(`
                     <span class="invalid-feedback d-flex" role="alert">
                        <strong>${err.errors.email[0]}</strong>
                     </span>
                   `)
               }
               if(err.errors.password !== undefined){
                   passwordS.addClass('is-invalid')
                   $('.modal.fade.show .errorPassword').html(`
                     <span class="invalid-feedback d-flex" role="alert">
                        <strong>${err.errors.password[0]}</strong>
                     </span>
                   `)
               }

           }
       })
   })
   $('#btnProfilName').on('click',function () {
       var emailS = $('.modal.fade.show .emailProfil');
       var passwordS = $('.modal.fade.show .passwordProfil');
       $('#nameProfil').removeClass('is-invalid');
       $('.passwordProfil').removeClass('is-invalid');
       $('.errormsg').html('');
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       $.ajax({
           url:'/updateName',
           type:'post',
           data:{
               'name' : $('#nameProfil').val(),
               'password' : passwordS.val()
           },
           success:function (datas) {
               passwordS.val('');
               $('#textName').html(passwordS.val());
               profilName.hide();
                $('#profilContent').html(`
                    <div class="alert alert-success" role="alert">
                        <strong>Succès: </strong> ${datas}
                    </div>
                `)
           },
           error: function(xhr) {
               var err = eval("(" + xhr.responseText + ")");
               if(err.errors.email !== undefined){
                   $('#nameProfil').addClass('is-invalid')
                   $('#errorName').html(`
                     <span class="invalid-feedback d-flex" role="alert">
                        <strong>${err.errors.name[0]}</strong>
                     </span>
                   `)
               }
               if(err.errors.password !== undefined){
                   passwordS.addClass('is-invalid')
                   $('.modal.fade.show .errorPassword').html(`
                     <span class="invalid-feedback d-flex" role="alert">
                        <strong>${err.errors.password[0]}</strong>
                     </span>
                   `)
               }

           }
       })
   })
   $('#btnProfilPassword').on('click',function () {
       var passwordS = $('.modal.fade.show .passwordProfil');
       var newPasswordS = $('#newPassword');
       $('.passwordProfil').removeClass('is-invalid');
       newPasswordS.removeClass('is-invalid');
       $('.errormsg').html('');
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       $.ajax({
           url:'/updatePassword',
           type:'post',
           data:{
               'password' : passwordS.val(),
               'newPassword' : newPasswordS.val()
           },
           success:function (datas) {
               passwordS.val('');
               newPasswordS.val('');
               profilPassword.hide();
                $('#profilContent').html(`
                    <div class="alert alert-success" role="alert">
                        <strong>Succès: </strong> ${datas}
                    </div>
                `)
           },
           error: function(xhr) {
               var err = eval("(" + xhr.responseText + ")");
               if(err.errors.password !== undefined){
                   passwordS.addClass('is-invalid')
                   $('.modal.fade.show .errorPassword').html(`
                     <span class="invalid-feedback d-flex" role="alert">
                        <strong>${err.errors.password[0]}</strong>
                     </span>
                   `)
               }
               if(err.errors.newPassword !== undefined){
                   newPasswordS.addClass('is-invalid')
                   $('.modal.fade.show .errorNewPassword').html(`
                     <span class="invalid-feedback d-flex" role="alert">
                        <strong>${err.errors.newPassword[0]}</strong>
                     </span>
                   `)
               }

           }
       })
   })
    // / profil

    var myModal = new bootstrap.Modal(document.getElementById('annulModal'));
    var annulToastEl = document.getElementById('toastAnnul');
    var annulToast = bootstrap.Toast.getOrCreateInstance(annulToastEl);
    function annulModal(row){
        let data = $(row).attr('data-location');
        myModal.show();
        $('#btnAnnulModal').on('click',function () {
            $(row).parent().parent().remove();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"POST",
                data:{id:data},
                url:'/delLocation',
                success:function () {
                    myModal.hide();
                    annulToast.show();
                }
            })
        })

    }
    if(window.location.pathname.match('location')){
        $('.annulLocation').off().on('click',function () {
            annulModal(this)
        })
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    //setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 1500;  //time in ms, 5 seconds for example

//on keyup, start the countdown
    $('#searchBar').on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });
//on keydown, clear the countdown
    $('#searchBar').on('keydown', function () {
        clearTimeout(typingTimer);
    });
    $('#searchBar').on('focusout',function () {
        setTimeout(()=>{
            $('#divSearch').hide()
        },100)
    })
    $('.dropdown-item').on('click',function () {
        var checkBoxes = $(this).children();
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        showCar(checkBoxes);
    })
    $('.typeCheck').on('click',function () {
        $(this).prop("checked", !$(this).prop("checked"));
    })
    function showCar(s){
        let type = s.attr('data-type');
        if(s.prop('checked') === true){
            $('.voiture[data-type="'+type+'"]').show();
        }else{
            $('.voiture[data-type="'+type+'"]').hide();
        }
    }


//user is "finished typing," do something
    function doneTyping () {
        if($('#searchBar').val() !== ''){
            $.ajax({
                type:'post',
                url:'/getAgenceSearch',
                data:{"search": $('#searchBar').val()},
                dataType: 'json',
                success:function (rowdata) {
                    $('#divSearch').html('');
                    if(rowdata !== null){
                        rowdata.forEach(datas =>{
                            $('#divSearch').append(`
                                <a class="bg-transparent border-0 text-start my-2 w-100 btnAgence text-dark text-decoration-none" href="/locationVoiture/${datas.id}"> <h2 class="m-0">${datas.ville+' '+datas.rue}</h2></a>
                            `);
                        })
                    }else{
                        $('#divSearch').append(`
                           <h2>Malheureusement, aucun résultat n'a été trouvé. <br> Veuillez vérifier l'orthographe et réessayer.</h2>
                        `);
                    }

                }
            })
        }
        $(this).focus().prop('readonly',true)
        $('#divSearch').show()
    }


    function reverseDate(data){
        if(data !== undefined){
            let d = data.split('-');
            return d[2]+'/'+d[1]+'/'+d[0];
        }
    }
    function reverseDateEng(data){
        if(data !== undefined){
            let d = data.split('/');
            let date = d[2]+'-'+d[1]+'-'+d[0];
            let verifDate  = new Date().getFullYear() - 122;
            return (  new Date(date) instanceof Date && !isNaN(new Date(date)) && d[2] >= verifDate ) ? date : false;
        }
    }
    function verifPrix(pj,Days){
        //pj prix par jours

        let p1 = (((pj*5)/100)*Days) * 1.5;
        let p2 = (((pj*10)/100)*Days) * 1.5;
        let p3 = (((pj*15)/100)*Days) * 1.5;
        $('#pPrice1').html(p1)
        $('#pPrice2').html(p2)
        $('#pPrice3').html(p3)

        let valP = ($('.franchiseP').length >= 1) ? parseInt($('.franchiseP:checked').attr('id').replace('fp','')) : '';
        let protection = (valP === 2) ? p1 : (valP === 3) ? p2 : (valP === 4) ? p3 : 0;
        let protecPneu = ($('#protecPneu').prop('checked')) ? (10*Days) : 0;
        let driver = (parseInt($('#addDriver').val()) > 0) ?  (parseInt($('#addDriver').val()) * 10 ) * Days : 0;
        let hiver = ($('#chainesV').prop('checked')) ? (10*Days) : 0;
        let gps = ($('#gps').prop('checked')) ? (10*Days) : 0;
        let location = pj * Days;
        let prix = protection + protecPneu + driver + hiver + gps + 40 + location;
        $('#priceVoiture').html(prix+'€');
        return prix;
    }
    var locaToastEl = document.getElementById('toastLocation');
    var locationToast = bootstrap.Toast.getOrCreateInstance(locaToastEl);
    let nbClick = 0;
    function changePrix(dateDebut,dateFin){
        var Days = '';
        if(dateDebut !== "" && dateFin !== ""){
            let date1 = new Date(reverseDateEng(dateDebut));
            let date2 = new Date(reverseDateEng(dateFin));
            let Time = date2.getTime() - date1.getTime();
            Days = (Time / (1000 * 3600 * 24)) + 1;//nombre de jour entre date 1 et 2
            $('#prixTimeLocation').html((Days > 1) ? Days+' Jour/s' : Days+' jour')
        }
        return (Days !== '') ? Days : 1;
    }

    if(window.location.pathname.match('voiture')){
        $.ajax({
            type:'post',
            url:'/getLocationDate',
            data:{"id_voiture":window.location.pathname.split('/')[2]},
            dataType: 'json',
            success:function (rowdata) {
                var disableDate=[];
                if (rowdata[0].dateDebut !== undefined){
                    rowdata.forEach(datas =>{
                        disableDate.push({
                            "from" : reverseDate(datas.dateDebut),
                            "to" : reverseDate(datas.dateFin)
                        })
                    })
                }
                prixPJ  = parseInt(rowdata[0].prix);


                if($('#dateD').val() !== '' && $('#dateF').val() !== ''){
                    verifPrix(prixPJ,changePrix($('#dateD').val(),$('#dateF').val()))
                }
                const datePicker = flatpickr('#dateD',{
                    mode: "range",
                    minDate: "today",
                    dateFormat: "d/m/Y",
                    disable: disableDate,
                    onOpen:function () {
                        $('#dateD').val('')
                        $('#dateF').val('')
                    },
                    onChange:function (selectedDates,dateStr) {
                        if (nbClick === 2){
                            let allDate = dateStr.replace(' to ', ',').split(',');
                            if(dateStr.match('to')){
                                $('#dateD').val(allDate[0])
                                $('#dateF').val(allDate[1])
                                verifPrix(prixPJ,changePrix(allDate[0],allDate[1]))
                            }
                        }
                        nbClick++;
                    },onClose:function(selectedDates,dateStr){
                        nbClick++;
                        if(nbClick >= 2){
                            setTimeout(()=>{
                                nbClick = 0;
                                if($('#dateD').val() !== '' && !dateStr.match('to')){
                                    $('#dateF').val($('#dateD').val())
                                    verifPrix(prixPJ,changePrix($('#dateD').val(),$('#dateF').val()))
                                }else if($('#dateD').val() === ''){
                                    $('#dateF').val('')
                                }
                            },100)
                        }
                    }
                });
                $('#LocationDate').on('click',function () {
                    datePicker.open();
                })
                $('.franchiseP,#protecPneu,#gps,#chainesV').on('click',function () {
                    verifPrix(prixPJ,changePrix($('#dateD').val(),$('#dateF').val()))
                })
                $('#addDriver').on('change',function () {
                    verifPrix(prixPJ,changePrix($('#dateD').val(),$('#dateF').val()))
                })
            }
        })
    }
    $('#validationBtn').on('click',function () {
        let dateDebut = $('#dateD');
        let dateFin = $('#dateF');
        dateDebut.removeClass('active');
        dateFin.removeClass('active');
        $('.errorDate').remove()
        if(dateDebut.val() !== '' && dateFin.val() !== ''){
            $.ajax({
                type:'post',
                url:'/addLocation',
                data:{
                    "id_voiture":window.location.pathname.split('/')[2],
                    "dateDebut":reverseDateEng(dateDebut.val()),
                    "dateFin":reverseDateEng(dateFin.val()),
                    "montant" : verifPrix(prixPJ,changePrix(dateDebut.val(),dateFin.val()))
                },
                dataType: 'json',
                success:function () {
                    locationToast.show()
                    dateDebut.val('')
                    dateFin.val('')
                    locaToastEl.addEventListener('hidden.bs.toast', function () {
                        window.location.href = '/home';
                    })
                }
            })
        }else{
            dateDebut.addClass('active');
            dateFin.addClass('active');
            if($('.errorDate').length < 1 ){
                dateDebut.parent().append(`
               <p class="errorDate">Champ requis</p>
            `);
            }
        }
    })
    $('#dropdownfilter').on('click',function(){
        $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down')
    })
})
