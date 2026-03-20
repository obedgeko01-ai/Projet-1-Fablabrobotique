<?php
require_once __DIR__ . '/../modèles/WebtvModele.php';
require_once __DIR__ . '/../modèles/CommentairesVideoModele.php';

class WebtvControleur
{
    private WebtvModele $videoModele;
    private CommentairesVideoModele $commentaireModele;

    public function __construct()
    {
        $this->videoModele = new WebtvModele();
        $this->commentaireModele = new CommentairesVideoModele();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $q   = $_GET['q']         ?? '';
        $cat = $_GET['categorie'] ?? '';

        $videos     = $this->videoModele->all($q ?: null, $cat ?: null);
        $categories = $this->videoModele->categories();
        $current    = $this->selectCurrentVideo($videos);

        // ---------- AJOUT COMMENTAIRE ----------
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_comment') {
            $result = $this->handleCommentSubmission($current);

            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }

            $this->redirect($current);
        }

        // ---------- SUPPRESSION COMMENTAIRE (AJAX) ----------
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            ($_POST['action'] ?? '') === 'delete_comment' &&
            $this->isAjax()
        ) {
            $commentId = $_POST['comment_id'] ?? '';
            $isAdmin   = !empty($_SESSION['utilisateur_role']) && strtolower($_SESSION['utilisateur_role']) === 'admin';

            if (!$isAdmin) {
                header('Content-Type: application/json');
                echo json_encode(["success" => false, "message" => "Non autorise"]);
                exit;
            }

            if (!ctype_digit((string)$commentId)) {
                header('Content-Type: application/json');
                echo json_encode(["success" => false, "message" => "ID invalide"]);
                exit;
            }

            $this->commentaireModele->delete((int)$commentId);
            header('Content-Type: application/json');
            echo json_encode(["success" => true]);
            exit;
        }

        // ---------- SUPPRESSION COMMENTAIRE (GET classique) ----------
        if (isset($_GET['del']) && !empty($_SESSION['utilisateur_role']) && strtolower($_SESSION['utilisateur_role']) === 'admin') {
            $this->handleCommentDeletion($_GET['del'], $current);
        }

        // ---------- VUES ----------
        if ($current && !empty($current['id'])) {
            $this->videoModele->incrementViews((int)$current['id']);
        }

        // ---------- COMMENTAIRES ----------
        $commentaires = ($current && !empty($current['id']))
            ? $this->commentaireModele->listForVideo((int)$current['id'])
            : [];

        $isLogged = !empty($_SESSION['utilisateur_id']);
        $isAdmin  = !empty($_SESSION['utilisateur_role']) && strtolower($_SESSION['utilisateur_role']) === 'admin';
        $userName = $_SESSION['utilisateur_nom'] ?? null;

        $flashMessage = $_SESSION['message']      ?? null;
        $flashType    = $_SESSION['message_type'] ?? 'info';

        unset($_SESSION['message'], $_SESSION['message_type']);

        require __DIR__ . '/../vues/webtv/webtv.php';
    }

    private function handleCommentSubmission(?array $current): array
    {
        if (!$current || empty($current['id'])) {
            return ["success" => false, "message" => "Video introuvable"];
        }

        if (empty($_SESSION['utilisateur_id'])) {
            return ["success" => false, "message" => "Connexion requise"];
        }

        $texte = trim($_POST['commentaire'] ?? '');

        if ($texte === '') {
            return ["success" => false, "message" => "Commentaire vide"];
        }

        $newId = $this->commentaireModele->create(
            (int)$current['id'],
            (int)$_SESSION['utilisateur_id'],
            $texte
        );

        return [
            "success"    => (bool)$newId,
            "message"    => $newId ? "Commentaire publie" : "Erreur lors de la publication",
            "comment_id" => $newId,
            "is_admin"   => !empty($_SESSION['utilisateur_role']) && strtolower($_SESSION['utilisateur_role']) === 'admin',
            "video_id"   => (int)$current['id'],
        ];
    }

    private function handleCommentDeletion($commentId, ?array $current): void
    {
        if (!ctype_digit((string)$commentId)) {
            $this->redirect($current);
        }

        $this->commentaireModele->delete((int)$commentId);

        $_SESSION['message']      = "Commentaire supprime";
        $_SESSION['message_type'] = "success";

        $this->redirect($current);
    }

    private function selectCurrentVideo(array $videos): ?array
    {
        if (isset($_GET['video']) && ctype_digit((string)$_GET['video'])) {
            $video = $this->videoModele->findById((int)$_GET['video']);
            if ($video) return $video;
        }

        if (isset($_GET['video_id']) && trim($_GET['video_id']) !== '') {
            $video = $this->videoModele->findByYoutubeId(trim($_GET['video_id']));
            if ($video) return $video;
        }

        return $videos[0] ?? null;
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function redirect(?array $current): void
    {
        $videoParam = $current
            ? (isset($current['youtube_id'])
                ? 'video_id=' . urlencode($current['youtube_id'])
                : 'video=' . (int)$current['id'])
            : '';

        header("Location: ?page=webtv" . ($videoParam ? "&$videoParam" : ""));
        exit;
    }
}