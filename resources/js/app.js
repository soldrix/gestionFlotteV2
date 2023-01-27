require('datatables.net-bs5');
require( 'datatables.net-responsive-bs5' );
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
require('jquery-mask-plugin/dist/jquery.mask.js');
import flatpickr from "flatpickr";
let myModal3;
function initDataTable(){

    $('.dataTable').DataTable({
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

}
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
    initDataTable()
    if($('#dateD').length > 0){
        let currentDate = new Date();
        let monthnow = parseInt(currentDate.getUTCMonth()) +1;
        monthnow = (monthnow < 10) ? '0'+monthnow: monthnow;
        let datenow = currentDate.getFullYear()+'-'+monthnow+'-'+currentDate.getDate();
        flatpickr('#dateD',{
            dateFormat: "Y-m-d",
            enable:[
                {
                    from: datenow,
                    to: (parseInt(currentDate.getFullYear())+1)+'-'+monthnow+'-'+currentDate.getDate()
                }
            ]
        });
        flatpickr('#timeD',{
            enableTime:true,
            noCalendar:true,
            dateFormat:'H:i',
            time_24hr:true,
            minTime:currentDate.getHours()+':'+currentDate.getMinutes()
        })
        $('#timeD').on('focusout',function () {
            let timeV = ($('#timeD').val() < currentDate.getHours()+':'+currentDate.getMinutes() ) ? currentDate.getHours()+':'+currentDate.getMinutes(): $('#timeD').val();
            let timeDebut =($('#dateD').val() === $('#dateF')) ? timeV : "00:00";

            flatpickr('#timeF',{
                enableTime:true,
                noCalendar:true,
                dateFormat:'H:i',
                time_24hr:true,
                minTime:timeDebut
            })
        }).on('focus',function () {
            $('#timeF').val('')
        })

        $('#dateD').on('focusout',function () {
            let datedebut = ($('#dateD').val() < datenow) ? datenow : $('#dateD').val();
            flatpickr('#dateF',{
                dateFormat: "Y-m-d",
                enable:[
                    {
                        from: datedebut,
                        to: (parseInt(currentDate.getFullYear())+1)+'-'+monthnow+'-'+currentDate.getDate()
                    }
                ]
            });
        }).on('focus',function () {
            $('#dateF').val('')
        })


    }
    $(document).on('click','#btnAddVoiture',function () {
        modal('voiture')
    }).on('click',"#btnAddAssurance",function () {
        modal('assurance')
    }).on('click','#btnAddEntretiens',function () {
        modal('entretiens')
    }).on('click','#btnAddReparations',function () {
        modal('reparations')
    }).on('click','#btnAddConsommation',function () {
        modal('consommation')
    }).on('click','.btnAddModal',function () {
        let urlName = window.location.pathname.replace('/','');
        modal(urlName,'add')
        myModal3 = new bootstrap.Modal(document.getElementById( urlName.replace(urlName[0],urlName[0].toUpperCase())+'Modal'));
        myModal3.show();
    }).on('click','#btnAddAgence',function () {
        modal('agence')
    }).on('click','#btnAddLocation',function () {
        modal('location')
    })

})
function eventModif(){
    $('.delButton').off().on('click',function () {
        supModal(this)
    })
    $('.editButton').off().on('click',function () {
        let db = $(this).parent().parent().parent().attr('data-db');
        let dataid = $(this).parent().parent().parent().attr('data-voiture');
        let url  = (db === 'voiture') ? '/getVoiture': (db === 'consommation') ? '/getConsommation' : (db === 'entretiens') ? '/getEntretiens' :(db === 'reparations') ? '/getReparations' :(db === 'assurance') ? '/getAssurance' : (db === 'agence') ? '/getAgence' : (db === 'location') ? '/getLocation' :'';
        modal(db,'edit',url,dataid);
    })
}
$(window).on('load',function () {
    eventModif();
})


function loadDatas(table,optSelect){
    let url = (table === 'agence') ? '/loadAgence' : (table === 'voiture') ? '/loadVoiture' : '';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $.ajax({
        type:'post',
        url:url,
        dataType: 'json',
        success:function (rowdata) {
            rowdata.forEach(datas =>{
                let optionSelected =  (datas.id === optSelect && optSelect !== null) ? 'selected="selected"' : '';
                if(table === 'agence'){
                    $('#agenceId').append(`
                        <option value="${datas.id}" ${optionSelected}>${datas.ville} ${datas.rue}</option>
                    `)
                }
                if(table==='voiture'){
                    $('#voitureId').append(`
                        <option value="${datas.id}" ${optionSelected}>${datas.immatriculation}</option>
                    `)
                }

            })
        }
    })
}


let myModal1 = new bootstrap.Modal(document.getElementById('AddModal'));
function modal(name,type,url,dataid) {
        let htmlModal = (name === "voiture") ?
            `<div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="typeVoiture">type de voiture :</label>
                <input type="text" name="typeVoiture" placeholder="type ex(berline)" class="mb-2 me-2 inputForm inputText"  required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="nbPorte">Nombre de porte :</label>
                <input type="text" name="nbPorte" placeholder="nombre de porte" class="mb-2 me-2 inputForm inputNumber"  required>
            </div>
             <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="nbPlace">Nombre de siège :</label>
                <input type="text" name="nbPlace" placeholder="nombre de siège" class="mb-2 me-2 inputForm inputNumber"  required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="prix">prix pour une journée :</label>
                <input type="text" name="prix" placeholder="ex(100)" class="mb-2 me-2 inputForm inputNumber"  required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="marque">Marque :</label>
                <input type="text" name="marque" placeholder="Marque" class="mb-2 me-2 inputForm inputText"  required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="model">Model :</label>
                <input type="text" name="model" placeholder="Model" class="mb-2 inputForm inputText" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="carburant">Type de carburant :</label>
                <input type="text" name="carburant" placeholder="Carburant ex:(diesel)" class="mb-2 me-2 inputText inputForm" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="cicrculation">Mise en circulation :</label>
                <input type="text" name="circulation" placeholder="Date circulation" class="mb-2 inputCirc inputDate inputForm" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="immatriculation">Immatriculation du véhicule :</label>
                <input type="text" name="immatriculation" placeholder="Immatriculation" class="mb-2 me-2 inputIm inputForm" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="puissance">Nombre de chevaux :</label>
                <input type="text" name="puissance" placeholder="Puissance ex:(100cc)" class="mb-2 inputPuissance inputForm" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="status">Status du véhicule :</label>
                <select name="status" id="voitureStatut" class="mb-2 me-2">
                    <option value="disponible">Disponible</option>
                    <option value="indisponible">Indisponible</option>
                </select>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="agenceId">Agence :</label>
                <select name="id_agence" id="agenceId" class="mb-2 me-2">
                    <option value="null">Vide</option>
                </select>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="file">Image du véhicule :</label>
                <input type="file" name="file" accept="image/png, image/jpeg, image/jpg" class="mb-2 inputFile inputForm" required>
            </div>
           `
        : (name === "assurance") ?
            `<div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="nomAssu">Nom de l'assurance :</label>
                <input type="text" name="nomAssu" placeholder="Nom assurance" class="inputForm inputText mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="debutAssu">Date de début :</label>
                <input type="text" name="debutAssu" placeholder="Debut assurance" class="inputForm inputDate assuDateD mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="finAssu">Date de fin :</label>
                <input type="text" name="finAssu" placeholder="Fin assurance"  class="inputForm inputDate assuDateF mb-2" required>
            </div>
            <div class="d-flex">
                <label class="me-2" for="frais">Frais de l'assurance :</label>
                <input type="text" name="frais" placeholder="Frais assurance" class="inputForm  inputNumber mb-2" required>
            </div>`
        : (name === "entretiens") ?
            `<div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="typeEnt">Type d'entretien :</label>
                <input type="text" name="typeEnt" placeholder="Type ex:(vidange)" class="inputForm inputText mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="dateEnt">Date de l'entretien :</label>
                <input type="text" name="dateEnt" placeholder="Date Entretiens" class="inputForm inputDate mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="montantEnt">Montant de l'entretien :</label>
                <input type="text" name="montantEnt" placeholder="Montant total" class="inputForm inputNumber mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="garageEnt">Nom du garage :</label>
                <input type="text" name="garageEnt" placeholder="Garage" class="inputForm inputText  mb-2" required>
            </div>
            <div class="d-flex flex-column">
                <label class="me-2" for="noteEnt">Note supplémentaire :</label>
                <textarea name="noteEnt" id="noteEnt" cols="30" rows="4" class="inputForm mb-2"></textarea>
            </div>`
        : (name === "reparations") ?
            `<div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="typeRep">Type de réparation :</label>
                <input type="text" name="typeRep" placeholder="Type ex:(vidange)" class="inputForm inputText mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="dateRep">Date de la réparation :</label>
                <input type="text" name="dateRep" placeholder="Date Reparations" class="inputForm inputDate mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="montantRep">Montant de la réparation :</label>
                <input type="text" name="montantRep" placeholder="Montant total" class="inputForm inputNumber mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="garageRep">Nom du garage :</label>
                <input type="text" name="garageRep" placeholder="Garage" class="inputForm inputText mb-2" required>
            </div>
            <div class="d-flex flex-column">
                <label class="me-2" for="noteRep">Note supplémentaire :</label>
                <textarea name="noteRep" id="noteRep" cols="30" rows="4" class="inputForm mb-2"></textarea>
            </div>`
        : (name === "consommation") ?
            `<div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="montantCons">Montant :</label>
                <input type="text" name="montantCons" placeholder="Montant total" class="inputForm inputNumber mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="litre">nombre de litres :</label>
                <input type="text" name="litre" placeholder="Nombre de litre" class="inputForm inputNumber mb-2" required>
            </div>` : (name === "agence") ?
                    `
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="ville">Ville de l'agence :</label>
                <input type="text" name="ville" placeholder="Ville" class="inputForm mb-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="rue">rue de l'agence :</label>
                <input type="text" name="rue" placeholder="Rue" class="inputForm mb-2" required>
            </div>`
        : (name === 'location') ?
            `<div class="d-flex flex-wrap align-items-baseline">
                <label for="nomAssu">Date de debut :</label>
                <input type="text" name="locationD" id="locationD" class="inputForm assuDateD mb-2 me-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label for="debutAssu">Date de fin :</label>
                <input type="text" name="locationF" id="locationF" class="inputForm assuDateF mb-2 me-2" required disabled>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label for="debutAssu">Montant :</label>
                <input type="text" name="montantLoca" id="montantLoca" class="inputForm inputNumber mb-2 me-2" required>
            </div>
            <div class="d-flex flex-wrap align-items-baseline">
                <label class="me-2" for="voitureId">Immatriculation :</label>
                <select name="id_voiture" id="voitureId" class="mb-2 me-2">
                      <option value="null">Vide</option>
                </select>
            </div>` : "";

    if(window.location.pathname.match('voiture') && type === "add" || window.location.pathname.match('home') && type === "add" || window.location.pathname.match('agence') && type === "add"  || type !== 'add'){
        myModal1.show();
        $('#AddModal').find('.modal-body').html(htmlModal)
        $('#AddModal').ready(function () {
            $('input[name=puissance]').mask('00000');
            $('input[name=immatriculation]').mask('SS-000-SS');
            $('input[name=nbPorte]').mask('0');
            $('input[name=nbPlace]').mask('0');
            $('input[name=prix]').mask('0000000');
        })
    }
    $('#AddModal').ready(function () {
        flatpickr('.inputDate',{
            dateFormat : 'd/m/Y'
        });
        flatpickr('#locationD',{
            mode: "range",
            minDate: "today",
            dateFormat: "d/m/Y",
            onClose: function(selectedDates, dateStr){
                let allDate = dateStr.replace(' to ', ',').split(',');
                $('#locationD').val(allDate[0])
                $('#locationF').val(allDate[1])
            }
        });
        let bigregex = "A";
        for (let i = 0; i < 99; i++) {
            bigregex = bigregex + "A";
        }
        $('.inputText').mask(bigregex)
        bigregex = '0';
        for (let i = 0; i < 99 ; i++) {
            bigregex += "0";
        }
        $('input[name=litre]').mask(bigregex)

    })
    if (type=== "edit"){
        $('#AddModal .modal-header h3').html((name === 'entretiens' || name === 'assurance' || name === 'agence') ? "Modifier l' " + name : "Modifier la " + name)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            type:"POST",
            data:{
                id:dataid
            },
            dataType:'json',
            url:url,
            success:function (dataRow) {
                dataRow.forEach(datas =>{
                    if(url === '/getAgence'){
                        $('input[name=ville]').val(datas.ville);
                        $('input[name=rue]').val(datas.rue);
                    }
                    if(url === '/getConsommation'){
                        $('input[name=montantCons]').val(datas.montantCons);
                        $('input[name=litre]').val(datas.litre);
                    }
                    if(url === '/getAssurance'){
                        $('input[name=nomAssu]').val(datas.nomAssu);
                        $('input[name=frais]').val(datas.frais);
                        $('input[name=debutAssu]').val(reverseDate(datas.debutAssu));
                        $('input[name=finAssu]').val(reverseDate(datas.finAssu));
                    }
                    if(url === '/getReparations'){
                        $('input[name=typeRep]').val(datas.typeRep);
                        $('input[name=dateRep]').val(reverseDate(datas.dateRep));
                        $('input[name=montantRep]').val(datas.montantRep);
                        $('input[name=garageRep]').val(datas.garageRep);
                        $('textarea[name=noteRep]').val(datas.noteRep);
                    }
                    if(url === '/getEntretiens'){
                        $('input[name=typeEnt]').val(datas.typeEnt);
                        $('input[name=dateEnt]').val(reverseDate(datas.dateEnt));
                        $('input[name=montantEnt]').val(datas.montantEnt);
                        $('input[name=garageEnt]').val(datas.garageEnt);
                        $('textarea[name=noteEnt]').val(datas.noteEnt);
                    }
                    if(url === '/getVoiture'){
                        $('input[name=marque]').val(datas.marque);
                        $('input[name=model]').val(datas.model);
                        $('input[name=puissance]').val(datas.puissance);
                        $('input[name=circulation]').val(reverseDate(datas.circulation));
                        $('input[name=carburant]').val(datas.carburant);
                        $('input[name=immatriculation]').val(datas.immatriculation);
                        $('input[name=typeVoiture]').val(datas.type);
                        $('input[name=nbPorte]').val(datas.nbPorte);
                        $('input[name=nbPlace]').val(datas.nbPlace);
                        $('input[name=prix]').val(datas.prix);
                        if(datas.statut === 'Disponible'){
                            $('select[name=status]').children().first().prop('selected',true);
                        }else{
                            $('select[name=status]').children().last().prop('selected',true);
                        }
                        loadDatas('agence',datas.id_agence);
                    }
                    if(url === '/getLocation'){
                        $('input[name=locationD]').val(reverseDate(datas.dateDebut))
                        $('input[name=locationF]').val(reverseDate(datas.dateFin))
                        $('input[name=montantLoca]').val(datas.montant)
                        loadDatas('voiture',datas.id_voiture);
                        $("select[name=id_voiture] option[value='"+datas.id_voiture+"']").prop('selected',true);
                    }
                })
                $('.btnModal').off().on('click',function () {
                    $(this).prop('disabled',true);
                    updateDatas(dataid,name);
                    setTimeout(()=>{
                        $('.btnModal').prop('disabled',false);
                    },2000)
                })

            }
        })
    }
    if (type !== "edit"){
        $('#AddModal').ready(function () {
            if(window.location.pathname.match('home') || window.location.pathname.match('voiture')){
                loadDatas('agence');
            }
            if(window.location.pathname.match('location') || window.location.pathname.match('voiture')){
                loadDatas('voiture');
                loadDatas('agence');
            }
        })
        $('#AddModal .modal-header h3').html((name === 'entretiens') ? "Ajouter un " + name : "Ajouter une " + name)
        $('.btnModal').off().on('click', function () {
            $(this).prop('disabled',true);
            setTimeout(()=>{
                $('.btnModal').prop('disabled',false);
            },2000)
            updateDatas('',name,'add');
            if(!window.location.pathname.match('voiture') && !window.location.pathname.match('home')){
                document.getElementById(name.replace(name[0],name[0].toUpperCase())+'Modal').addEventListener('hide.bs.modal', function () {
                    $('.modal input').val('');
                    $('.modal input').removeClass('active');
                    $('.text-danger').remove();
                })
            }
        })
    }
    document.getElementById('AddModal').addEventListener('hide.bs.modal', function () {
        $('.modal input').val('');
        $('.modal input').removeClass('active');
        $('.text-danger').remove();
    })
}
var myModal = new bootstrap.Modal(document.getElementById('delModal'));
var delToastEl = document.getElementById('toastSupp');
var delToast = bootstrap.Toast.getOrCreateInstance(delToastEl);
function supModal(row){
    let data = (window.location.pathname !== '/home') ? $(row).parent().parent().parent().attr('data-voiture') : $(row).parent().attr('data-voiture');
    let db = (window.location.pathname !== '/home') ? $(row).parent().parent().parent().attr('data-db') : $(row).parent().attr('data-db');
    myModal.show();
    $('#btnDelModal').on('click',function () {

        let url  = (db === 'voiture') ? '/delVoiture': (db === 'consommation') ? '/delConsommation' : (db === 'entretiens') ? '/delEntretiens' :(db === 'reparations') ? '/delReparations' :(db === 'assurance') ? '/delAssurance' : (db === 'agence') ? '/delAgence' : (db === 'location') ? 'delLocation' :'';
        if(url !== '/delVoiture'){
            $("tr[data-voiture='"+data+"']").remove()
        }else{
            $(row).parent().remove();
        }
        if (url !== ''){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"POST",
                data:{id:data},
                url:url,
                success:function () {
                    myModal.hide();
                    if($('#nbRep').length > 0 && db === 'reparations'){
                        countData($('#nbRep'));
                    }
                    if($('#nbCons').length > 0 && db === 'consommation'){
                        countData($('#nbCons'));
                    }
                    if($('#nbAssu').length > 0 && db === 'assurance'){
                        countData($('#nbAssu'));
                    }
                    if($('#nbEnt').length > 0 && db === 'entretiens'){
                        countData($('#nbEnt'));
                    }
                    delToast.show();
                }
            })
        }

    })

}
//pour inverser une date
function reverseDate(d,t){
    d = d.split('-');
    let date_time = d[2].split(' ');
    return (t !== 'time')? d[2]+'/'+d[1]+'/'+d[0] : date_time[0]+'/'+d[1]+'/'+d[0]+' '+date_time[1];
}
function verifDatas(datas,page,type){
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function reverseDate(d,t){
        if(d.val().match('/') || d.match('/') ){
            d = d.val().split('/');
            let date_time = d[2].split(' ');
            let date =(t !== 'time')? d[2]+'-'+d[1]+'-'+d[0] : date_time[0]+'-'+d[1]+'-'+d[0]+' '+date_time[1];
            return ( (t !== 'time') ? (new Date(date) instanceof Date && !isNaN(new Date(date))) : (date.match(/^([1-2]\d{3}-([0]?[1-9]|1[0-2])-([0-2]?[0-9]|3[0-1])) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1}):([0-5]?\d{1})$/g)) ) ? date : false;
        }else{
            d = d.split('-');
            let date_time = d[2].split(' ');
            return (t !== 'time')? d[2]+'-'+d[1]+'-'+d[0] : date_time[0]+'-'+d[1]+'-'+d[0]+' '+date_time[1];
        }
    }

    //function pour ajouter message erreur
    function draw_error(s,i,t,o){
        //s = selector
        //i = id error
        //t = type
        //o = other
        if(t === undefined || t === ''){
            if($('#'+i).length <= 0 && s.val() ===''){
                s.addClass('active');
                s.parent().append(`
                <p id='${i}' class='text-danger'>Champ requis</p>
            `);
            }
        }
        if(t === 'nb'){
            if($('#'+i).length <= 0 && isNaN(s.val().replaceAll(',','.')) || s.val() ===''){
                s.addClass('active')
                s.parent().append(`
                    <p id='${i}' class='text-danger'>Champ requis et doit correspondre à exemple:(151,15 ou 151)</p>
                `)
            }
        }
        if(t === 'dateDebut' || t === 'dateFin'){
            if($('#'+i).length <= 0 && reverseDate(o) >= reverseDate(s) || reverseDate(s) === false ) {
                s.addClass('active')
                let html = (t === 'dateDebut') ? 'Champ requis ,doit être plus petite que la date de fin et doit être une date valide' : 'Champ requis ,doit être plus grand que la date de début et doit être une date valide';
                s.parent().append(`
                    <p id='${i}' class='text-danger'> ${html}</p>
                `)
            }
        }
        if(t === 'date'){
            if($('#'+i).length <= 0 && reverseDate(s) === false || s.val() === ""){
                s.addClass('active')
                s.parent().append(`
                    <p id='${i}' class='text-danger'>Champ requis et doit être une date valide</p>
                `)
            }
        }
    }

    let tab = {};
    let idVoiture = (window.location.pathname.match('voiture')) ? window.location.pathname.replace('/voiture/' ,'') : $('.modal.fade.show select[name=id_voiture]').val();
    if (page === "voiture"){
        let marque = $('.modal.fade.show input[name=marque]');
        let model = $('.modal.fade.show input[name=model]');
        let puissance = $('.modal.fade.show input[name=puissance]');
        let carburant = $('.modal.fade.show input[name=carburant]');
        let immatriculation = $('.modal.fade.show input[name=immatriculation]');
        let circulation = $('.modal.fade.show input[name=circulation]');
        let imageFile = $('.modal.fade.show input[name=file]');
        let typeVoiture = $('.modal.fade.show input[name=typeVoiture]');
        let nbPorte = $('.modal.fade.show input[name=nbPorte]');
        let nbPlace = $('.modal.fade.show input[name=nbPlace]');
        let prix = $('.modal.fade.show input[name=prix]');

        if(marque.val() !=="" && model.val() !== "" && puissance.val() !== "" && carburant.val()!=="" && immatriculation.val()!=="" && circulation.val()!=="" && reverseDate(circulation) !== false && typeVoiture.val() !== "" && nbPorte.val() !== "" && nbPlace.val() !== "" && prix.val() !== ""){
            if (type === 'add' && imageFile[0].files.length <= 0){
                if($('#error_image').length <= 0){
                    imageFile.addClass('active');
                    imageFile.parent().append(`
                    <p id='error_image' class='text-danger'>Champ requis</p>
                `);
                }
            }else{

                tab['id'] = (type === 'add') ? '' : datas;
                tab['id_agence'] =$('#agenceId').val();
                tab['marque'] = capitalizeFirstLetter(marque.val());
                tab['model'] = model.val();
                tab['puissance'] = puissance.val();
                tab['carburant'] = capitalizeFirstLetter(carburant.val());
                tab['immatriculation'] = immatriculation.val().toUpperCase();
                tab['circulation'] = reverseDate(circulation);
                tab['status'] = capitalizeFirstLetter($('.modal.fade.show select[name=status]').val());
                tab['typeVoiture'] = typeVoiture.val();
                tab['nbPorte'] = nbPorte.val();
                tab['nbPlace'] = nbPlace.val();
                tab['prix']  = prix.val().replace(',','.');
            }

        }else{
            if (type === 'add' && imageFile[0].files.length <= 0){
                if($('#error_image').length <= 0){
                    imageFile.addClass('active');
                    imageFile.parent().append(`
                    <p id='error_image' class='text-danger'>Champ requis</p>
                `);
                }
            }
            draw_error(marque,'error_marque')
            draw_error(circulation,'error_circulation','date')
            draw_error(model,'error_model')
            draw_error(puissance,'error_puissance')
            draw_error(carburant,'error_carburant')
            draw_error(immatriculation,'error_immatricualtion')
            draw_error(typeVoiture, 'error_type_voiture')
            draw_error(nbPorte, 'error_nbPorte')
            draw_error(nbPlace, 'error_nbPlace')
            draw_error(prix,"error_prix",'nb')
        }
    }
    if (page === "assurance"){
        let nomAssu = $('.modal.fade.show input[name=nomAssu]');
        let debutAssu = $('.modal.fade.show input[name=debutAssu]');
        let finAssu = $('.modal.fade.show input[name=finAssu]');
        let frais = $('.modal.fade.show input[name=frais]');
        let debutAssuVal = reverseDate(debutAssu);
        let finAssuVal = reverseDate(finAssu);
        if(nomAssu.val() !=="" && debutAssu.val() !== "" && finAssu.val() !== "" && frais.val()!=="" && !isNaN(frais.val().replaceAll(',','.')) && frais.val().replaceAll(',','.') > 0 && debutAssuVal < finAssuVal && reverseDate(debutAssu) !== false && reverseDate(finAssu) !== false){
            if (type === 'add'){
                tab['id_voiture'] = idVoiture;
            }else{
                tab['id'] = datas;
            }
            tab['nomAssu'] = capitalizeFirstLetter(nomAssu.val());
            tab['debutAssu'] = debutAssuVal;
            tab['finAssu'] = finAssuVal;
            tab['frais'] = frais.val().replaceAll(',',".");
        }else{
            draw_error(nomAssu,'error_nomAssu');
            draw_error(debutAssu,'error_debutAssu','dateDebut',finAssu);
            draw_error(finAssu,'error_finAssu','dateFin',debutAssu);
            draw_error(frais,'error_frais','nb');
        }
    }
    if (page === "entretiens"){
        let typeEnt = $('.modal.fade.show input[name=typeEnt]');
        let dateEnt = $('.modal.fade.show input[name=dateEnt]');
        let montantEnt = $('.modal.fade.show input[name=montantEnt]');
        let garageEnt = $('.modal.fade.show input[name=garageEnt]');
        let noteEnt = $('.modal.fade.show textarea[name=noteEnt]');
        if(typeEnt.val() !=="" && dateEnt.val() !== "" && reverseDate(dateEnt) !== false && montantEnt.val() !== "" && garageEnt.val() !== "" && !isNaN(montantEnt.val().replaceAll(',','.')) && montantEnt.val().replaceAll(',','.') > 0){
            if (type === 'add'){
                tab['id_voiture'] = idVoiture;
            }else{
                tab['id'] = datas;
            }
            tab['typeEnt'] = capitalizeFirstLetter(typeEnt.val());
            tab['dateEnt'] = reverseDate(dateEnt);
            tab['montantEnt'] = montantEnt.val().replaceAll(',','.');
            tab['garageEnt'] = capitalizeFirstLetter(garageEnt.val());
            tab['noteEnt'] = (noteEnt.val() !== "" && noteEnt.val() !== undefined) ? capitalizeFirstLetter(noteEnt.val()) : 'Aucune note';
        }else{
            draw_error(typeEnt,'error_typeEnt')
            draw_error(dateEnt,'error_dateEnt','date')
            draw_error(montantEnt,'error_montantEnt','nb')
            draw_error(garageEnt,'error_garageEnt')
        }
    }
    if (page === "reparations"){
        let typeRep = $('.modal.fade.show input[name=typeRep]');
        let dateRep = $('.modal.fade.show input[name=dateRep]');
        let montantRep = $('.modal.fade.show input[name=montantRep]');
        let garageRep = $('.modal.fade.show input[name=garageRep]');
        let noteRep = $('.modal.fade.show textarea[name=noteRep]');
        if(typeRep.val() !=="" && dateRep.val() !== ""  && montantRep.val() !== "" && reverseDate(dateRep) !== false && garageRep.val() !== "" && !isNaN(montantRep.val().replaceAll(',','.')) && montantRep.val().replaceAll(',','.') > 0){
            if (type === 'add'){
                tab['id_voiture'] = idVoiture;
            }else{
                tab['id'] = datas;
            }
            tab['typeRep'] = capitalizeFirstLetter(typeRep.val());
            tab['dateRep'] = reverseDate(dateRep);
            tab['montantRep'] = montantRep.val().replace(',','.');
            tab['garageRep'] = capitalizeFirstLetter(garageRep.val());
            tab['noteRep'] = (noteRep.val() !=="" && noteRep.val() !== undefined) ? capitalizeFirstLetter(noteRep.val()) : 'Aucune note';
        }else{
            draw_error(typeRep,'error_typeRep');
            draw_error(dateRep,'error_dateRep','date');
            draw_error(montantRep,'error_montantRep','nb');
            draw_error(garageRep,'error_garageRep');
        }
    }
    if (page === "consommation"){
        let montantConso = $('.modal.fade.show input[name=montantCons]');
        let litre = $('.modal.fade.show input[name=litre]');
        if(montantConso.val() !=="" && litre.val() !== "" && montantConso.val().replaceAll(',','.') > 0 && !isNaN(montantConso.val().replaceAll(',','.'))){
            tab['montantCons'] = montantConso.val().replaceAll(',','.');
            tab['litre'] = litre.val().replace(',','.');
            if (type === 'add'){
                tab['id_voiture'] = idVoiture;
            }else{
                tab['id'] = datas;
            }
        }else{
            draw_error(montantConso,'error_montantCons','nb')
            draw_error(litre,'error_litre')
        }
    }
    if (page === "agence"){
        let ville = $('.modal.fade.show input[name=ville]');
        let rue = $('.modal.fade.show input[name=rue]');
        if(ville.val() !=="" && rue.val() !== ""){
            tab['ville'] = ville.val();
            tab['rue'] = rue.val();
            tab['id'] = (type !== 'add') ? datas : '';

        }else{
            draw_error(rue,'error_rue')
            draw_error(ville,'error_ville')
        }
    }
    if(page === 'location'){
        let locationF = $('.modal.fade.show input[name=locationF]');
        let locationD = $('.modal.fade.show input[name=locationD]');
        let montant = $('.modal.fade.show input[name=montantLoca]');
        if(locationD.val() !== "" && reverseDate(locationD) !== false && locationF.val() !== "" && reverseDate(locationF) !== false && montant.val().replaceAll(',','.') > 0 && !isNaN(montant.val().replaceAll(',','.')) ){
            tab['dateDebut'] = reverseDate(locationD);
            tab['dateFin'] = reverseDate(locationF);
            tab['montant'] = montant.val().replaceAll(',','.');
            tab['id_voiture'] = $('.modal.fade.show select[name=id_voiture]').val();
            tab['id'] = (type !== 'add') ? datas : ' ';
        }else{
            draw_error(locationD,'error_locationD','date');
            draw_error(locationF,'error_locationF','date');
            draw_error(montant,'error_montantLoca','nb');
        }
    }
    return tab;
}
var saveToastEl = document.getElementById('saveToast');
var saveToast = bootstrap.Toast.getOrCreateInstance(saveToastEl);

function countData(s,t){
    if(t==='a'){
        $(s).html(parseInt($(s).html()) + 1)
    }else{
        $(s).html(parseInt($(s).html()) - 1)
    }
}
function updateDatas(datas,page,type){
    let dataVerif =verifDatas(datas,page,type);
    let urlAjax = (type === 'add' && page === 'consommation') ? '/addConsommation': (type !== 'add' && page === 'consommation') ? '/updateConsommation' :
        (type === 'add' && page === 'entretiens') ? '/addEntretiens' : (type !== 'add' && page === 'entretiens') ? '/updateEntretiens' :
        (type === 'add' && page === 'assurance') ? '/addAssurance' : (type !== 'add' && page === 'assurance') ? '/updateAssurance' :
        (type === 'add' && page === 'reparations') ? '/addReparations' : (type !== 'add' && page === 'reparations') ? '/updateReparations' :
        (type === 'add' && page === 'voiture') ? '/addVoiture' : (type !== 'add' && page ==='voiture') ? '/updateVoiture' :
        (type === 'add' && page === 'agence') ? '/addAgence' : (type !== 'add' && page === 'agence') ? '/updateAgence' :
        (type === 'add' && page === 'location') ? '/addLocation' : (type !== 'add' && page === 'location') ? '/updateLocation' : '';
    if (dataVerif['id'] !== undefined || dataVerif['id_voiture'] !== undefined){
        if (page === 'agence'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:dataVerif,
                url: urlAjax,
                dataType:'json',
                success:function (rowData) {
                    myModal1.hide();
                    rowData.forEach(datas=>{
                        let table = $('#DataTable_agence').DataTable();
                        if(type !== 'add'){
                            table.row($("tr[data-voiture='"+datas.id+"']"))
                                .remove()
                                .draw();
                        }
                        let tab = [datas.ville];
                        tab.push(datas.rue+`<div class="divBtnTab">
                            <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                            <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                        </div>`)
                        let row = table.row.add(tab).node();
                        $(row).attr({"data-voiture":datas.id,"data-db":page})
                        $(row).children().last().addClass('tdBtn');
                        table.draw();
                        eventModif();
                    })
                    saveToast.show();
                }
            })
        }
        if (page === 'location'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:dataVerif,
                url: urlAjax,
                dataType:'json',
                success:function (rowData) {
                    myModal1.hide();
                    rowData.forEach(datas=>{
                        let table = $('#DataTable_location').DataTable();
                        if(type !== 'add'){
                            table.row($("tr[data-voiture='"+datas.id+"']"))
                                .remove()
                                .draw();
                        }
                        let tab = [reverseDate(datas.dateDebut),reverseDate(datas.dateFin),datas.immatriculation];
                        tab.push(datas.montant+`<div class="divBtnTab">
                            <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                            <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                        </div>`)
                        let row = table.row.add(tab).node();
                        $(row).attr({"data-voiture":datas.id,"data-db":page})
                        $(row).children().last().addClass('tdBtn');
                        table.draw();
                        eventModif();
                    })
                    saveToast.show();
                }
            })
        }

        if (page === 'consommation'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:dataVerif,
                url: urlAjax,
                dataType:'json',
                success:function (rowData) {
                    if(window.location.pathname.match('voiture') || type !== 'add' ){
                        myModal1.hide();
                    }
                    rowData.forEach(datas=>{
                        let table = $('#DataTable_carburants').DataTable();
                        if(type !== 'add'){
                            table.row($("tr[data-voiture='"+datas.id+"']"))
                                .remove()
                                .draw();
                        }
                        let tab = [datas.litre,datas.montantCons+'€'];
                        if(!window.location.pathname.match('voiture')){
                            if(type==='add'){
                                myModal3.hide();
                            }
                            tab.push(datas.immatriculation);
                        }
                        tab.push(Math.round(datas.montantCons / datas.litre)+'€'+`<div class="divBtnTab">
                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                            </div>`)
                        let row = table.row.add(tab).node();
                        $(row).attr({"data-voiture":datas.id,"data-db":page})
                        $(row).children().last().addClass('tdBtn');
                        table.draw();
                        eventModif();
                    })
                    saveToast.show();
                    if($('#nbCons').length > 0){
                        countData($('#nbCons'),'a')
                    }
                }
            })
        }
        if (page === 'assurance'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:dataVerif,
                url:urlAjax,
                dataType:'json',
                success:function (rowData) {
                    if(window.location.pathname.match('voiture') || type !== 'add' ){
                        myModal1.hide();
                    }
                    rowData.forEach(datas=>{
                        let table = $('#DataTable_assurances').DataTable();
                        if(type !== 'add'){
                            table.row($("tr[data-voiture='"+datas.id+"']"))
                                .remove()
                                .draw();
                        }
                        let tab = [datas.nomAssu,reverseDate(datas.debutAssu),reverseDate(datas.finAssu)];
                        if(!window.location.pathname.match('voiture')){
                            if(type==='add'){
                                myModal3.hide();
                            }
                            tab.push(datas.immatriculation);
                        }
                        tab.push(datas.frais+'€'+`<div class="divBtnTab">
                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                            </div>`)
                        let row = table.row.add(tab).node();
                        $(row).attr({"data-voiture":datas.id,"data-db":page})
                        $(row).children().last().addClass('tdBtn');
                        table.draw();
                        eventModif();
                    })
                    saveToast.show();
                    if($('#nbAssu').length > 0){
                        countData($('#nbAssu'),'a')
                    }
                }
            })
        }
        if (page === 'entretiens'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:dataVerif,
                url:urlAjax,
                dataType:'json',
                success:function (rowData) {
                    if(window.location.pathname.match('voiture') || type !== 'add' ){
                        myModal1.hide();
                    }
                    rowData.forEach(datas=>{
                        let table = $('#DataTable_entretiens').DataTable();
                        if(type !== 'add'){
                            table.row($("tr[data-voiture='"+datas.id+"']"))
                                .remove()
                                .draw();
                        }
                        let tab = [datas.garageEnt,datas.typeEnt,datas.montantEnt+'€',reverseDate(datas.dateEnt)];
                        if(!window.location.pathname.match('voiture')){
                            if(type==='add'){
                                myModal3.hide();
                            }
                            tab.push(datas.immatriculation);
                        }
                        tab.push(`
                            <div class="noteSupp">
                                ${datas.noteEnt}
                            </div>
                            <div class="divBtnTab">
                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                            </div>`)
                        let row = table.row.add(tab).node();
                        $(row).attr({"data-voiture":datas.id,"data-db":page})
                        $(row).children().last().addClass('tdBtn');
                        table.draw();
                        eventModif();
                    })
                    saveToast.show();
                    if($('#nbEnt').length > 0){
                        countData($('#nbEnt'),'a')
                    }
                }
            })
        }
        if (page === 'reparations'){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                data:dataVerif,
                url:urlAjax,
                dataType:'json',
                success:function (rowData) {
                    if(window.location.pathname.match('voiture') || type !== 'add' ){
                        myModal1.hide();
                    }
                    rowData.forEach(datas=>{
                        let table = $('#DataTable_reparations').DataTable();
                        if(type !== 'add'){
                            table.row($("tr[data-voiture='"+datas.id+"']"))
                                .remove()
                                .draw();
                        }
                        let tab = [datas.garageRep,datas.typeRep,datas.montantRep+'€',reverseDate(datas.dateRep)];
                        if(!window.location.pathname.match('voiture')){
                            if(type==='add'){
                                myModal3.hide();
                            }
                            tab.push(datas.immatriculation);
                        }
                        tab.push(`
                            <div class="noteSupp">
                                ${datas.noteRep}
                            </div>
                            <div class="divBtnTab">
                                <button class="btn btn-info editButton text-white"><i class="fa-solid fa-pencil "></i></button>
                                <button class="btn btn-danger delButton"><i class="fa-solid fa-trash-can"></i></button>
                            </div>`)
                        let row = table.row.add(tab).node();
                        $(row).attr({"data-voiture":datas.id,"data-db":page})
                        $(row).children().last().addClass('tdBtn');
                        table.draw();
                        eventModif();
                    })
                    saveToast.show();
                    if($('#nbRep').length > 0){
                        countData($('#nbRep'),'a')
                    }
                }
            })
        }
        if (page === 'voiture'){
            let image = $('.modal.fade.show input[name=file]');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let files = (image.get(0).files.length !== 0) ? image[0].files[0] : 0;
            let fd = new FormData();
            if (files !== 0){
                fd.append('file',files);
            }
            Object.keys(dataVerif).forEach(function(key) {
                fd.append(key,dataVerif[key]);
            });
            $.ajax({
                type:'POST',
                data: fd,
                contentType:false,
                processData:false,
                url:urlAjax,
                dataType:'json',
                success:function (rowData) {
                    if(type !== 'add'){
                        rowData.forEach(datas =>{
                            $('#imageVoiture').attr('src','http://127.0.0.1:8000/storage/'+datas.image);
                            $('#immatriculation').html(datas.immatriculation);
                            $('#marque').html(datas.marque);
                            $('#model').html(datas.model);
                            $('#circulation').html(reverseDate(datas.circulation));
                            $('#statut').html(datas.statut);
                            $('#puissance').html(datas.puissance);
                            $('#carburant').html(datas.carburant);
                            $('#agence').html(datas.ville+' '+datas.rue)
                        })
                    }
                    if(type === 'add'){
                        rowData.forEach(datas=>{
                            $('.containerVoiture').append(`
                         <div class="col-12 col-lg-3 col-xxl-2 d-flex flex-column  p-2 rounded m-2 blockVoiture" style="background: #e4e4e4" data-voiture="${datas.id}" data-db="${page}">
                            <img src="http://127.0.0.1:8000/storage/${datas.image}" alt="" class="rounded">
                            <p class="text-center">Marque : ${datas.marque}</p>
                            <p class="text-center">Model : ${datas.model}</p>
                            <a  class="btn btn-primary w-75 align-self-center mt-3 btn-info-car" href="http://127.0.0.1:8000/voiture/${datas.id}">
                                En savoir plus
                            </a>
                            <button class="btn btn-danger delButton w-75 mt-2 align-self-center">Supprimer</button>
                        </div>
                        `)
                        })

                    }
                    myModal1.hide();
                    eventModif();
                    saveToast.show();
                }
            })




        }
    }
}
