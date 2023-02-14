<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class maillerController extends Controller
{

    public function UserPassword($datas){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = config('mail.mailers.smtp.host');  //gmail SMTP server
        $mail->SMTPAuth = true;
        //to view proper logging details for success and error messages
        // $mail->SMTPDebug = 1;
        $mail->Username = config('mail.mailers.smtp.username');   //email
        $mail->Password = config('mail.mailers.smtp.password');   //16 character obtained from app password created
        $mail->Port = config('mail.mailers.smtp.port');                    //SMTP port
        $mail->SMTPSecure = "ssl";
        $mail->setFrom('admin@gmail.com', 'admin');
        //receiver email address and name
        $mail->addAddress($datas->email, $datas->name);

        // Add cc or bcc
        // $mail->addCC('email@mail.com');
        // $mail->addBCC('user@mail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Modification mot de passe';
        $mail->Body    = "<h4> Modification mot de passe </h4>
        <p> Votre nouveau mot de passe : ".$datas->password."</p>
            <a href='http://127.0.0.1:8000/UpdatePassword'>Modifier votre mot de passe</a>";

        // Send mail
        if (!$mail->send()) {
            return [
                "status" => 'error',
                "message" =>   'Email not sent an error was encountered: ' . $mail->ErrorInfo
            ];
        }
        $mail->smtpClose();
        return ["status" => 'ok'];
    }

    public function createUser($datas){
        $mail = new PHPMailer(true);
        header("Content-Type: text/html; charset=utf-8");
        $mail->isSMTP();
        $mail->Host = config('mail.mailers.smtp.host');  //gmail SMTP server
        $mail->SMTPAuth = true;
        //to view proper logging details for success and error messages
        // $mail->SMTPDebug = 1;
        $mail->Username = config('mail.mailers.smtp.username');   //email
        $mail->Password = config('mail.mailers.smtp.password');   //16 character obtained from app password created
        $mail->Port = config('mail.mailers.smtp.port');                    //SMTP port
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('admin@gmail.com', 'admin');

        //receiver email address and name
        $mail->addAddress($datas->email_receiver, $datas->name);

        // Add cc or bcc
        // $mail->addCC('email@mail.com');
        // $mail->addBCC('user@mail.com');


        $mail->isHTML(true);
        $mail->Subject = 'Création compte utilisateur';
        $mail->Body    = "<h4> Votre compte est disponible.</h4>
        <p> Votre email de connexion: ".$datas->email."</p>
        <p> Votre mot de passe de connexion: ".$datas->password."</p>
        <p>Vous pouvez changer le mot de passe en cliquant sur le lien ci dessous:</p>
        <a href='http://127.0.0.1:8000/UpdatePassword'>Modifier votre mot de passe</a>";

        // Send mail
        if (!$mail->send()) {
            return [
                "status" => 'error',
                "message" =>   'Email not sent an error was encountered: ' . $mail->ErrorInfo
            ];
        }
        $mail->smtpClose();
        return ["status" => 'ok'];
    }

    public function createLocation($datas){
        $mail = new PHPMailer(true);
        header("Content-Type: text/html; charset=utf-8");
        $mail->isSMTP();
        $mail->Host = config('mail.mailers.smtp.host');  //gmail SMTP server
        $mail->SMTPAuth = true;
        //to view proper logging details for success and error messages
        // $mail->SMTPDebug = 1;
        $mail->Username = config('mail.mailers.smtp.username');   //email
        $mail->Password = config('mail.mailers.smtp.password');   //16 character obtained from app password created
        $mail->Port = config('mail.mailers.smtp.port');                    //SMTP port
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('admin@gmail.com', 'mailer');
        //receiver email address and name
        $mail->addAddress($datas->email, $datas->name);

        // Add cc or bcc
        // $mail->addCC('email@mail.com');
        // $mail->addBCC('user@mail.com');


        $mail->isHTML(true);
        $mail->Subject = 'Location véhicule';
        $mail->Body    = "<h4> Votre location a été enregistrer avec succès.</h4>
        <p>Le montant total de votre location à régler a l'agence est de ".$datas->montant."€</p>
        <p> Vous pourez récupérer votre véhicule à partir de 8 heure à partir du".$datas->DateDebut.", vous devez rendre le véhicule avant 20 h le ".$datas->DateFin.".</p>
        <p>Vous pouvez retrouvé les informations de votre location sur votre compte dans la partie mes locations:</p>
        <a href='http://127.0.0.1:1000/login'>Se connecter</a>
        <p style='margin-top: 15px;border-top: 1px solid;padding-bottom: 15px'>Ce message est automatique, merci de ne pas répondre.</p>
        ";

        // Send mail
        if (!$mail->send()) {
            return [
                "status" => 'error',
                "message" =>   'Email not sent an error was encountered: ' . $mail->ErrorInfo
            ];
        }
        $mail->smtpClose();
        return ["status" => 'ok'];
    }
}
