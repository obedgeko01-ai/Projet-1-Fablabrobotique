<?php

require_once __DIR__ . '/../modèles/Projet.php';

class ProjetsControleur {

    public function index() {
        $modele = new Projet();
        $projects = $modele->getTousLesProjets();
        require __DIR__ . '/../vues/projets/index.php';
    }

    public function creation(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $role = $_SESSION['utilisateur_role'] ?? '';
        if (!in_array($role, ['Admin', 'Éditeur'], true)) {
            header('Location: ?page=projets');
            exit;
        }

        include __DIR__ . '/../vues/projets/projet_creation.php';
    }

    public function enregistrer(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=projets');
            exit;
        }

       
        $role = $_SESSION['utilisateur_role'] ?? '';
        if (!in_array($role, ['Admin', 'Éditeur'], true)) {
            $_SESSION['message'] = "❌ Accès refusé.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=projets');
            exit;
        }

        
        require_once __DIR__ . '/../config/database.php';
        $db = new Database();
        $conn = $db->getConnection();

        
        $title                = trim($_POST['titre'] ?? '');
        $auteur               = trim($_POST['auteur'] ?? $_SESSION['utilisateur_nom'] ?? 'Inconnu');
        $description          = trim($_POST['description'] ?? '');
        $description_detailed = trim($_POST['description_detailed'] ?? '');
        $technologies         = trim($_POST['technologies'] ?? '');
        $features             = trim($_POST['features'] ?? '');
        $challenges           = trim($_POST['challenges'] ?? '');
        $image_url            = trim($_POST['image_url'] ?? '');

       
        if (empty($title) || empty($description)) {
            $_SESSION['message'] = "❌ Le titre et la description sont obligatoires.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=projet_creation');
            exit;
        }

        
        if (empty($image_url)) {
            $image_url = null;
        }

        
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/projets/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_url = $filename; 
            }
        }

       
        try {
            $stmt = $conn->prepare("
                INSERT INTO projects (title, auteur, description, description_detailed, technologies, image_url, features, challenges)
                VALUES (:title, :auteur, :description, :description_detailed, :technologies, :image_url, :features, :challenges)
            ");

            $stmt->execute([
                ':title'                => $title,
                ':auteur'               => $auteur,
                ':description'          => $description,
                ':description_detailed' => $description_detailed ?: null,
                ':technologies'         => $technologies ?: null,
                ':image_url'            => $image_url,
                ':features'             => $features ?: null,
                ':challenges'           => $challenges ?: null,
            ]);

            $_SESSION['message'] = "✅ Projet ajouté avec succès !";
            $_SESSION['message_type'] = 'success';
        } catch (Exception $e) {
            $_SESSION['message'] = "❌ Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header('Location: ?page=projets');
        exit;
    }
}