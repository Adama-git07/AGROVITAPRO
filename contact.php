<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "PHPMailer script actif";

// Chargement des classes PHPMailer (chemin relatif depuis /AgroVita/)
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sécurisation des données entrées
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation des champs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "Erreur : tous les champs sont requis.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erreur : adresse email invalide.";
        exit;
    }

    // Création du mail
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP pour Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adamapatricedja@gmail.com';     // Ton Gmail
        $mail->Password   = 'hjymcnwxxsszppsg';              // Ton mot de passe d’application (16 caractères)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Expéditeur et destinataire
        $mail->setFrom('adamapatricedja@gmail.com', $name);   // Ton Gmail en From (obligatoire pour Gmail SMTP)
        $mail->addAddress('contact@farmagrovita.com');        // Le mail destinataire final

        // Contenu du message
        $mail->isHTML(false); // message texte brut
        $mail->Subject = "Nouveau message de $name - $subject";
        $mail->Body    = "Vous avez reçu un message depuis votre site Farm Agro Vita :\n\n"
                       . "Nom : $name\n"
                       . "Email : $email\n"
                       . "Sujet : $subject\n\n"
                       . "Message :\n$message\n";

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
