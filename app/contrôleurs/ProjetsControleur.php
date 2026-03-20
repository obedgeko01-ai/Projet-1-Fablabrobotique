<?php
require_once __DIR__ . '/../modèles/ProjetModele.php';
require_once __DIR__ . '/../config/database.php';

class ProjetsControleur
{
    public function index(): void
    {
        $modele  = new ProjetModele();
        $projets = $modele->getTousLesProjets();
        require __DIR__ . '/../vues/projets/projets.php';
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
            $_SESSION['message']      = "❌ Accès refusé.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=projets');
            exit;
        }

        $db   = getDatabase();
        $titre                 = trim($_POST['titre']                ?? '');
        $auteur                = trim($_POST['auteur']               ?? $_SESSION['utilisateur_nom'] ?? 'Inconnu');
        $description           = trim($_POST['description']          ?? '');
        $description_detaillee = trim($_POST['description_detaillee']?? '');
        $technologies          = trim($_POST['technologies']         ?? '');
        $fonctionnalites       = trim($_POST['fonctionnalites']      ?? '');
        $defis                 = trim($_POST['defis']                ?? '');
        $image_url             = trim($_POST['image_url']            ?? '') ?: null;

        if (empty($titre) || empty($description)) {
            $_SESSION['message']      = "❌ Le titre et la description sont obligatoires.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=projet_creation');
            exit;
        }

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/projets/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename  = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                $image_url = $filename;
            }
        }

        try {
            $stmt = $db->prepare("
                INSERT INTO projets (titre, auteur, description, description_detaillee, technologies, image_url, fonctionnalites, defis)
                VALUES (:titre, :auteur, :description, :description_detaillee, :technologies, :image_url, :fonctionnalites, :defis)
            ");
            $stmt->execute([
                ':titre'                => $titre,
                ':auteur'               => $auteur,
                ':description'          => $description,
                ':description_detaillee'=> $description_detaillee ?: null,
                ':technologies'         => $technologies ?: null,
                ':image_url'            => $image_url,
                ':fonctionnalites'      => $fonctionnalites ?: null,
                ':defis'                => $defis ?: null,
            ]);
            $_SESSION['message']      = "✅ Projet ajouté avec succès !";
            $_SESSION['message_type'] = 'success';
        } catch (Exception $e) {
            $_SESSION['message']      = "❌ Erreur : " . $e->getMessage();
            $_SESSION['message_type'] = 'danger';
        }

        header('Location: ?page=projets');
        exit;
    }
}