<!doctype html>
<html lang="en">
<head>
    <title>{{$data['title']}}</title>
</head>
<body>
    <h4> Votre location a été enregistrer avec succès.</h4>
    <p>Le montant total de votre location à régler a l'agence est de {{$data["montant"]}}€</p>
    <p> Vous pourez récupérer votre véhicule à partir de 8 heure à partir du {{$data['DateDebut']}}, vous devez rendre le véhicule avant 20 h le
        {{$data['DateFin']}}.</p>
    <p>Vous pouvez retrouvé les informations de votre location sur votre compte dans la partie mes locations.</p>
    <p style='margin-top: 15px;border-top: 1px solid;padding-bottom: 15px'>Ce message est automatique, merci de ne pas répondre.</p>
</body>
</html>
