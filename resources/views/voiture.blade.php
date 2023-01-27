
@extends('layouts.app')

@section('content')
        <div id="containerVoiture" class="container  px-5 py-4 d-flex flex-column">
            <div class="w-100 d-flex justify-content-between">
                    <div class="col-7 p-3 imageVoiture" style="background-image: url({{asset('/api/image/'.$voiture->image)}});">
                        <h2 class="text-white">{{$voiture->model}} ou similaire | Berline</h2>
                        <div class="w-100 d-flex align-items-baseline">
                            <i class="fa-solid fa-user-group text-white iconVoiture"></i>
                            <p class="text-white mx-3">4 Siège</p>
                            <div class="iconVoiture">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path fill="#191919" fill-rule="evenodd" d="M4 12v3.062c2.929.364 5.4 2.303 5.906 4.938H20v-8H4zm.618-2H20V4H7.618l-3 6zM2 10.764L6.382 2H22v20H8v-1c0-2.218-2.29-4-5-4H2v-6.236zM7 15v-2h3v2H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-white mx-2">4 porte</p>
                            <div class="iconVoiture">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path fill="#191919" fill-rule="evenodd" d="M11 13H8v5H6V6h2v5h3V6h2v5h3V6h2v7h-5v5h-2v-5zM8 4H6V2h2v2zm5 0h-2V2h2v2zm5 0h-2V2h2v2zm-5 18h-2v-2h2v2zm-5 0H6v-2h2v2z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-white mx-2">Manuelle</p>
                        </div>
                    </div>
                    <div id="blockSell" class="col-4 p-3 bg-light rounded d-flex flex-column">
                        <h3 class="mb-3">{{{$voiture->model.' '.$voiture->marque}}}</h3>
                        <h4>Période de location</h4>
                        <div class="d-flex flex-column mx-2 mt-2 mb-4">
                            <div class="d-flex ps-2">
                                <label for="dateD" class="text-opacity-50 text-dark mb-2 me-5">Date de départ</label>
                                <label for="dateF" class="text-opacity-50 text-dark mb-2 ms-4">Date de retour</label>
                            </div>
                            <div class="d-flex" id="LocationDate">
                                <input type="text" id="dateD" class="ms-2 inputSearch" placeholder="_ _ / _ _ / _ _ _ _" required>
                                <input type="text" id="dateF" class="me-2 inputSearch" placeholder="_ _ / _ _ / _ _ _ _" required readonly>
                            </div>
                        </div>
                        <div class="w-100 d-flex justify-content-between px-4">
                            <p class="m-0 textSell">Durée de location</p>
                            <p class="m-0" id="prixTimeLocation">1 jour</p>
                        </div>
                        <h4>Frais</h4>
                        <div class="w-100 d-flex px-4 flex-column">
                            <div class="col-auto p-0 d-flex justify-content-between">
                                <p class="m-0 textSell">Participation environnementale</p>
                                <p id="prixEnv" class="m-0">30€</p>
                            </div>
                            <div class="col-auto p-0 d-flex justify-content-between">
                                <p class="m-0 textSell">Supplément local</p>
                                <p id="prixSuppLocal" class="m-0">10€</p>
                            </div>
                            <p class="m-0 textSell">250 kilomètre inclus, 2.75€ / km supplémentaire</p>
                        </div>
                        <div class="ligne-75 align-self-center my-4"></div>
                        <div class="d-flex w-100 justify-content-between">
                            <h3>Total</h3>
                            <div class="d-flex flex-column">
                                <h3 id="priceVoiture" class="m-0">{{$voiture->prix + 40}}€</h3>
                                <span class="taxeText">Taxes incluse</span>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary mt-3" id="validationBtn">Continuer</button>
                    </div>
            </div>
            <h5>
                Le véhicule peut être récuperer à partir de 8h et doit être remis à l'agence avant 22h. <br>
                L'annulation peut être gratuite jusqu'à 24 heures avant le début de la location.
            </h5>

            <h2 class="my-5">Choisissez votre protection et vos options</h2>
            <div class="d-flex w-100">
                <div class="col-4 d-flex flex-column bg-white p-2 rounded h-fit">
                    <h3 class="m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" style="height: 2.5rem">
                            <path fill="#007aff" d="M19 17H8.303l-1.734 1.156 4.244 1.819-.788 1.838L6 20.088V29h4v-1H8v-2h11v2h-7v3H4V17.465l1.583-1.055-1.925-.963.895-1.789 2.8 1.4L10.382 9h18.236l1 2h-18l-2 4H19v2zm0 3.5V23h-6l1-2.5h5zm4-4.5h13v10.5c0 2.194-2.09 4.094-6.086 5.91l-.414.188-.414-.188C25.091 30.594 23 28.694 23 26.5V16zm2 2v8.5c0 1.092 1.446 2.452 4.5 3.898 3.054-1.446 4.5-2.806 4.5-3.898V18h-9z"></path>
                        </svg>
                        Protégez votre location
                    </h3>
                    <div class="d-flex flex-column w-100 px-4-5">
                        <h4>Protection vol et collision</h4>
                        <p class="text-start ps-4 pe-2 mb-1">Réduisez votre franchise en cas de vol
                            ou de dommages accidentels à la carrosserie
                            et aux jantes du véhicule.</p>
                        <div class="d-flex my-1">
                            <input type="radio" id="fp1" class="franchiseP" name="protection" checked>
                            <label for="fp1">Actuellement responsable des dommages
                                Jusqu’à la valeur total du véhicule</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="col-auto p-0">
                                <input type="radio" id="fp2" class="franchiseP" name="protection">
                                <label for="fp2">3000€ de franchise</label>
                            </div>
                            <p><span id="pPrice1">{{(($voiture->prix*5)/100) * 1.5}}</span>€ | jour</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="col-auto p-0">
                                <input type="radio" id="fp3" class="franchiseP" name="protection">
                                <label for="fp3">1500€ de franchise</label>
                            </div>
                            <p><span id="pPrice2">{{(($voiture->prix*10)/100) * 1.5}}</span>€ | jour</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="col-auto p-0">
                                <input type="radio" id="fp4" class="franchiseP" name="protection">
                                <label for="fp4">0€ de franchise</label>
                            </div>
                            <p><span id="pPrice3">{{(($voiture->prix*15)/100) * 1.5}}</span>€ | jour</p>
                        </div>
                    </div>
                    <div class="ligne-75 align-self-center my-2"></div>
                    <div class="w-100 d-flex px-4-5 flex-column">
                        <h4 class="m-0">Protection des pneus et vitres</h4>
                        <div class="d-flex col-auto py-2 justify-content-around">
                            <input type="checkbox" class="franchisePneu" id="protecPneu">
                            <label for="protecPneu">Franchise à 0 EUR.</label>
                            <p class="m-0"> <span id="pneuPrice">10</span>€ | Jour</p>
                        </div>
                        <p class="m-0">Protection pour les dommages
                            causés au pare-brise, aux fenêtres ou aux pneus.</p>
                    </div>
                </div>
                <div class="col-4 mx-2 d-flex flex-column">
                    <div class="w-100 d-flex flex-column bg-white p-3 rounded mx-2 mb-2 h-fit">
                        <h3 class="m-0">
                            <i class="fa-solid fa-user-plus text-primary"></i>
                            Conducteur supplémentaire
                        </h3>
                        <div class="d-flex flex-column w-100 p-2">
                            <p class="text-start ps-4 pe-2 mb-1">Partagez le plaisir de conduire et arrivez à destination en toute sécurité.</p>
                            <div class="d-flex w-100 justify-content-around my-2 align-items-baseline">
                                <select name="addDriver" id="addDriver">
                                    <option value="0" selected>0</option>
                                    @for($i=1;$i < 9;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <p class="mb-0"><span id="driverPrice">10</span>€ | Jour/Conducteur</p>
                            </div>
                        </div>
                    </div>
                    <div id="navigationV" class="w-100 d-flex flex-column bg-white p-3 rounded m-2 h-fit">
                        <h3 class="m-0">
                            <i class="fa-solid fa-map-location-dot text-primary me-3"></i>
                            Systèmes de navigation <br> <span class="ms-5">garanti</span>
                        </h3>
                        <div class="p-4">
                            <p class="mb-4">
                                <input type="checkbox" id="gps">
                                Trouvez le meilleur itinéraire avec le GPS
                            </p>
                            <p class="m-0"> <span id="navigationPrice">10</span>€ | jour</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex flex-column bg-white p-3 rounded mx-2 h-fit">
                    <h3 class="m-0">
                        <i class="fa-regular fa-snowflake text-primary"></i>
                        Équipement d'hiver
                    </h3>
                    <div class="d-flex flex-column w-100 p-3">
                        <div class="d-flex w-100 justify-content-around align-items-center mt-2">
                            <input type="checkbox" id="chainesV">
                            <label for="chainesV">Chaînes à neige</label>
                            <p class="m-0"><span id="eqpPrice">10</span>€ | Jour</p>
                        </div>
                        <p class="ms-4">Ne laissez pas la neige vous prendre au dépourvu</p>
                    </div>
                </div>


            </div>

        </div>
@endsection
