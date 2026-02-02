<?php

require_once __DIR__ . '/../modèles/AdminProjetsModele.php';

class AdminProjetsControleur
{
    private AdminProjetsModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modele = new AdminProjetsModele();
    }

 
    private function handleImage(): ?string
    {
        $image_url = trim($_POST['image_url'] ?? '');

       
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/projets/';

          
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

           
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                $_SESSION['message'] = "Type d'image non autorisé. Utilisez PNG, JPG, GIF ou WebP.";
                $_SESSION['message_type'] = 'danger';
                return null;
            }

            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                return $filename; // On stocke juste le nom du fichier (comme les autres projets existants)
            }
        }

       
        if (!empty($image_url)) {
            return $image_url;
        }

      
        return null;
    }

    public function handleRequest(?string $action = null): void
    {
        $action = $action ?? ($_GET['action'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formAction = $_POST['action'] ?? null;

            try {
                // ---- CREATE ----
                if ($formAction === 'create') {
                    $image_url = $this->handleImage();

                    $this->modele->create([
                        'title'                => trim($_POST['title'] ?? ''), 
                        'description'          => trim($_POST['description'] ?? ''),
                        'auteur'               => trim($_POST['auteur'] ?? ''),
                        'description_detailed' => trim($_POST['description_detailed'] ?? ''),
                        'technologies'         => trim($_POST['technologies'] ?? ''),
                        'image_url'            => $image_url,
                        'features'             => trim($_POST['features'] ?? ''),
                        'challenges'           => trim($_POST['challenges'] ?? '')
                    ]);

                    $_SESSION['message'] = "Projet créé avec succès !";
                    $_SESSION['message_type'] = 'success';
                }

                // ---- UPDATE ----
                elseif ($formAction === 'update') {
                    $id = (int)($_POST['project_id'] ?? 0);
                    $image_url = $this->handleImage();

                   
                    if ($image_url === null) {
                        $projet = $this->modele->find($id);
                        $image_url = $projet['image_url'] ?? null;
                    }

                    $this->modele->update($id, [
                        'title'                => trim($_POST['title'] ?? ''), 
                        'description'          => trim($_POST['description'] ?? ''),
                        'auteur'               => trim($_POST['auteur'] ?? ''),
                        'description_detailed' => trim($_POST['description_detailed'] ?? ''),
                        'technologies'         => trim($_POST['technologies'] ?? ''),
                        'image_url'            => $image_url,
                        'features'             => trim($_POST['features'] ?? ''),
                        'challenges'           => trim($_POST['challenges'] ?? '')
                    ]);

                    $_SESSION['message'] = "Projet mis à jour avec succès !";
                    $_SESSION['message_type'] = 'success';
                }

                // ---- DELETE ----
                elseif ($formAction === 'delete') {
                    $id = (int)($_POST['project_id'] ?? 0);
                    $this->modele->delete($id);

                    $_SESSION['message'] = "Projet supprimé avec succès !";
                    $_SESSION['message_type'] = 'success';
                }

            } catch (Throwable $e) {
                $_SESSION['message'] = "Erreur: " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }

            header('Location: ?page=admin-projets');
            exit;
        }

        $this->index();
    }

    public function index(): void
    {
        $projects = $this->modele->all();
        $total_projects = count($projects);
        include __DIR__ . '/../vues/admin/projets-admin.php';
    }
}