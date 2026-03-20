<?php
require_once __DIR__ . '/../modèles/AdminProjetsModele.php';

class AdminProjetsControleur
{
    private AdminProjetsModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminProjetsModele();

        if (!isset($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
            header('Location: ?page=login');
            exit;
        }
    }

    private function handleImage(): ?string
    {
        $image_url = trim($_POST['image_url'] ?? '');

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir    = __DIR__ . '/../../public/images/projets/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                $_SESSION['message']      = "Type d'image non autorisé.";
                $_SESSION['message_type'] = 'danger';
                return null;
            }

            $filename = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                return $filename;
            }
        }

        return !empty($image_url) ? $image_url : null;
    }

    public function handleRequest(?string $action = null): void
    {
        $action = $action ?? ($_GET['action'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAction = $_POST['action'] ?? null;

            try {
                if ($formAction === 'create') {
                    $this->modele->create([
                        'titre'                => trim($_POST['titre'] ?? ''),
                        'description'          => trim($_POST['description'] ?? ''),
                        'auteur'               => trim($_POST['auteur'] ?? ''),
                        'description_detaillee'=> trim($_POST['description_detaillee'] ?? ''),
                        'technologies'         => trim($_POST['technologies'] ?? ''),
                        'image_url'            => $this->handleImage(),
                        'fonctionnalites'      => trim($_POST['fonctionnalites'] ?? ''),
                        'defis'                => trim($_POST['defis'] ?? ''),
                    ]);
                    $_SESSION['message']      = "Projet créé avec succès !";
                    $_SESSION['message_type'] = 'success';

                } elseif ($formAction === 'update') {
                    $id        = (int)($_POST['projet_id'] ?? 0);
                    $image_url = $this->handleImage() ?? ($this->modele->find($id)['image_url'] ?? null);

                    $this->modele->update($id, [
                        'titre'                => trim($_POST['titre'] ?? ''),
                        'description'          => trim($_POST['description'] ?? ''),
                        'auteur'               => trim($_POST['auteur'] ?? ''),
                        'description_detaillee'=> trim($_POST['description_detaillee'] ?? ''),
                        'technologies'         => trim($_POST['technologies'] ?? ''),
                        'image_url'            => $image_url,
                        'fonctionnalites'      => trim($_POST['fonctionnalites'] ?? ''),
                        'defis'                => trim($_POST['defis'] ?? ''),
                    ]);
                    $_SESSION['message']      = "Projet mis à jour avec succès !";
                    $_SESSION['message_type'] = 'success';

                } elseif ($formAction === 'delete') {
                    $this->modele->delete((int)($_POST['projet_id'] ?? 0));
                    $_SESSION['message']      = "Projet supprimé avec succès !";
                    $_SESSION['message_type'] = 'success';
                }
            } catch (Throwable $e) {
                $_SESSION['message']      = "Erreur: " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }

            header('Location: ?page=admin-projets');
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $projets       = $this->modele->all();
        $total_projets = count($projets);
        include __DIR__ . '/../vues/admin/projets-admin.php';
    }
}