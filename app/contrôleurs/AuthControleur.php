<?php
require_once __DIR__ . '/../modèles/AuthModele.php';
require_once __DIR__ . '/../config/database.php';

class AuthControleur
{
    private AuthModele $modele;

    public function __construct()
    {
        $this->modele = new AuthModele(getDatabase());
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email      = $_POST['email']    ?? '';
            $motdepasse = $_POST['password'] ?? '';

            if (empty($email) || empty($motdepasse)) {
                $error = "Tous les champs sont requis.";
                require __DIR__ . '/../vues/utilisateurs/login.php';
                return;
            }

            $utilisateur = $this->modele->verifierConnexion($email, $motdepasse);

            if ($utilisateur) {
                $_SESSION['utilisateur_id']    = $utilisateur['id'];
                $_SESSION['utilisateur_nom']   = $utilisateur['nom'];
                $_SESSION['utilisateur_email'] = $utilisateur['email'];
                $_SESSION['utilisateur_role']  = $utilisateur['role'];
                header("Location: ?page=accueil");
                exit;
            }

            $error = "Email ou mot de passe incorrect.";
        }

        require __DIR__ . '/../vues/utilisateurs/login.php';
    }

    public function inscription(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom        = $_POST['name']             ?? '';
            $email      = $_POST['email']            ?? '';
            $motdepasse = $_POST['password']         ?? '';
            $confirm    = $_POST['confirm-password'] ?? '';

            if (empty($nom) || empty($email) || empty($motdepasse) || empty($confirm)) {
                $error = "Tous les champs sont requis.";
            } elseif ($motdepasse !== $confirm) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif ($this->modele->emailExiste($email)) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
                $this->modele->createUser($nom, $email, $hash);
                $_SESSION['success'] = "Compte créé avec succès !";
                header("Location: ?page=login");
                exit;
            }
        }

        require __DIR__ . '/../vues/utilisateurs/inscription.php';
    }

    public function deconnexion(): void
    {
        session_destroy();
        header("Location: ?page=accueil");
        exit;
    }

    public function mdpOublie(): void
    {
        require __DIR__ . '/../../app/vues/utilisateurs/motdepasseoublie.php';
    }
}