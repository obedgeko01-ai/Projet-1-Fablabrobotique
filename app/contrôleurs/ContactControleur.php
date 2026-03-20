<?php

require_once __DIR__ . '/../config/database.php';

class ContactControleur
{
    public function index()
    {
        $pdo = getDatabase();

        $message_sent = false;
        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $error_message = 'Tous les champs sont requis.';
            }

            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = 'Adresse email invalide.';
            }

            else {

                try {

                    $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;

                    $stmt = $pdo->prepare("
                        INSERT INTO contact_messages (nom, email, sujet, message, ip_address)
                        VALUES (:nom, :email, :sujet, :message, :ip)
                    ");

                    $stmt->execute([
                        ':nom' => $name,
                        ':email' => $email,
                        ':sujet' => $subject,
                        ':message' => $message,
                        ':ip' => $ip_address
                    ]);

                    $message_sent = true;

                } catch (PDOException $e) {

                    $error_message = 'Erreur lors de l\'enregistrement du message.';

                }

            }

        }

        require __DIR__ . '/../vues/contact/contact.php';
    }
}