<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Debug temporaire (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Chargement des classes PHPMailer
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Vérification de la méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nettoyage des données
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Vérification des champs requis
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "Erreur : tous les champs sont requis.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erreur : adresse email invalide.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adamapatricedja@gmail.com';       // Ton Gmail
        $mail->Password   = 'hjymcnwxxsszppsg';                // Mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Expéditeur et destinataire
        $mail->setFrom($mail->Username, $name); // Ton Gmail en "from"
        $mail->addAddress('contact@farmagrovita.com');

        // Contenu de l’email
        $mail->isHTML(false);
        $mail->Subject = "Nouveau message de $name - $subject";
        $mail->Body    = "Vous avez reçu un message depuis votre site Farm Agro Vita :\n\n"
                       . "Nom : $name\n"
                       . "Email : $email\n"
                       . "Sujet : $subject\n\n"
                       . "Message :\n$message";

        // Envoi
        $mail->send();
        header("Location: merci.html");
        exit;

    } catch (Exception $e) {
        echo "Erreur : le message n’a pas pu être envoyé.<br>";
        echo "PHPMailer Error : " . $mail->ErrorInfo;
    }
} else {
    echo "Méthode non autorisée.";
}
?>
