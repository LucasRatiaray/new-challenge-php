<?php

namespace Core\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Mailer
{
    public function sendActivationEmail(string $to, string $activationLink): bool
    {
        $mail = new PHPMailer(true);
        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Remplacez par votre hôte SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'e892906f873863'; // Remplacez par votre nom d'utilisateur SMTP
            $mail->Password = '38da645c12a71b'; // Remplacez par votre mot de passe SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            // Destinataires
            $mail->setFrom('no-reply@yourdomain.com', 'Votre Site'); // Remplacez par votre adresse "from"
            $mail->addAddress($to);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Activation de votre compte';
            $mail->Body = "Cliquez sur ce lien pour activer votre compte : <a href=\"$activationLink\">Activer</a>";

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            // Enregistrez l'erreur dans un fichier ou affichez-la en développement
            error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
            return false;
        }
    }
}
