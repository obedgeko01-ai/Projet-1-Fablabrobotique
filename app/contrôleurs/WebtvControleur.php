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

        // Filtres
        $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $cat = isset($_GET['categorie']) ? trim((string)$_GET['categorie']) : '';

        $videos = $this->videoModele->all($q ?: null, $cat ?: null);
        $categories = $this->videoModele->categories();

        $current = $this->selectCurrentVideo($videos);

        // Ajout commentaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_comment') {
            $this->handleCommentSubmission($current);
            return;
        }

        // Suppression (admin)
        if (isset($_GET['del']) && !empty($_SESSION['utilisateur_role']) && strtolower($_SESSION['utilisateur_role']) === 'admin') {
            $this->handleCommentDeletion($_GET['del'], $current);
            return;
        }

        // Incrément vues
        if ($current && !empty($current['id'])) {
            $this->videoModele->incrementViews((int)$current['id']);
        }

        // Commentaires (UNIQUEMENT par video_id)
        $commentaires = ($current && !empty($current['id']))
            ? $this->commentaireModele->listForVideo((int)$current['id'])
            : [];

        require __DIR__ . '/../vues/webtv/webtv.php';
    }

    private function selectCurrentVideo(array $videos): ?array
    {
        if (isset($_GET['video']) && ctype_digit((string)$_GET['video'])) {
            $video = $this->videoModele->findById((int)$_GET['video']);
            if ($video) return $video;
        }

        if (isset($_GET['video_id']) && trim((string)$_GET['video_id']) !== '') {
            $video = $this->videoModele->findByYoutubeId(trim((string)$_GET['video_id']));
            if ($video) return $video;
        }

        return !empty($videos) ? $videos[0] : null;
    }

    private function handleCommentSubmission(?array $current): void
    {
        if (!$current || empty($current['id'])) {
            $_SESSION['message'] = "Vidéo introuvable.";
            $_SESSION['message_type'] = "danger";
            $this->redirect($current);
            return;
        }

        if (empty($_SESSION['utilisateur_id'])) {
            $_SESSION['message'] = "Vous devez être connecté pour commenter.";
            $_SESSION['message_type'] = "danger";
            $this->redirect($current);
            return;
        }

        $texte = trim((string)($_POST['commentaire'] ?? ''));

        if ($texte === '') {
            $_SESSION['message'] = "Le commentaire ne peut pas être vide.";
            $_SESSION['message_type'] = "warning";
            $this->redirect($current);
            return;
        }

        $ok = $this->commentaireModele->create(
            (int)$current['id'],
            (int)$_SESSION['utilisateur_id'],
            $texte
        );

        if ($ok) {
            $_SESSION['message'] = "Commentaire publié ✅";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de la publication du commentaire.";
            $_SESSION['message_type'] = "danger";
        }

        $this->redirect($current);
    }

    private function handleCommentDeletion($commentId, ?array $current): void
    {
        if (!ctype_digit((string)$commentId)) {
            $this->redirect($current);
            return;
        }

        $this->commentaireModele->delete((int)$commentId);
        $_SESSION['message'] = "Commentaire supprimé.";
        $_SESSION['message_type'] = "success";
        $this->redirect($current);
    }

    private function redirect(?array $current): void
    {
        $videoParam = $current
            ? (isset($current['youtube_id']) ? 'video_id=' . urlencode($current['youtube_id']) : 'video=' . (int)$current['id'])
            : '';

        header("Location: ?page=webtv" . ($videoParam ? "&$videoParam" : ""));
        exit;
    }
}
