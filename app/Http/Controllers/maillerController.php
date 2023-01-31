<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class maillerController extends Controller
{

    public function UserPassword($data){
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
        $mail->addAddress($data->email, $data->name);

        // Add cc or bcc
        // $mail->addCC('email@mail.com');
        // $mail->addBCC('user@mail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Modification mot de passe';
        $mail->Body    = "<h4> Modification mot de passe </h4>
        <p> Votre nouveau mot de passe : ".$data->password."</p>
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

    public function createUser($data){
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
        $mail->addAddress($data->email_receiver, $data->name);

        // Add cc or bcc
        // $mail->addCC('email@mail.com');
        // $mail->addBCC('user@mail.com');


        $mail->isHTML(true);
        $mail->Subject = 'Création compte utilisateur';
        $mail->Body    = "<h4> Votre compte est disponible.</h4>
        <p> Votre email de connexion: ".$data->email."</p>
        <p> Votre mot de passe de connexion: ".$data->password."</p>
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
}
