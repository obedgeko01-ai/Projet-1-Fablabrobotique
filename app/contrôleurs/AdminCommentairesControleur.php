<?php
require_once __DIR__ . '/../modèles/AdminCommentairesModele.php';

class AdminCommentairesControleur
{
    private AdminCommentairesModele $modele;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
            header('Location: ?page=login');
            exit;
        }

        $this->modele = new AdminCommentairesModele();
    }

    public function handleRequest(?string $action = null): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $this->supprimer();
            return;
        }

        $this->index();
    }

    public function index(): void
    {
        $q = isset($_GET['q']) && trim((string)$_GET['q']) !== '' ? trim((string)$_GET['q']) : null;

        $commentaires = $this->modele->all($q);
        $stats = $this->modele->getStats();

        require __DIR__ . '/../vues/admin/commentaires-admin.php';
    }

    private function supprimer(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['message'] = "ID de commentaire invalide.";
            $_SESSION['message_type'] = "danger";
            $this->redirect();
            return;
        }

        if ($this->modele->delete($id)) {
            $_SESSION['message'] = "Commentaire supprimé avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression.";
            $_SESSION['message_type'] = "danger";
        }

        $this->redirect();
    }

    private function redirect(): void
    {
        $query = !empty($_GET['q']) ? '&q=' . urlencode((string)$_GET['q']) : '';
        header('Location: ?page=admin-comments' . $query);
        exit;
    }
}
