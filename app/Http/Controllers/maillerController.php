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
        <a href='http://127.0.0.1:8000/UpdatePassword'>Modifier mot de passe</a>";

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
