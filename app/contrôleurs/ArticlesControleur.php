<?php

require_once __DIR__ . '/../modèles/ArticleModele.php';

class ArticlesControleur {

    private $modele;

    public function __construct() {
        $this->modele = new ArticleModele();
    }

    /**
     * Liste tous les articles.
     * Prépare $articles et $roleUtilisateur pour la vue.
     */
    public function index(): void
    {
        $articles = $this->modele->getAllArticles();
        $roleUtilisateur = $_SESSION['utilisateur_role'] ?? 'Visiteur';

        include __DIR__ . '/../vues/articles/articles.php';
    }

    /**
     * Affiche le détail d'un article.
     * Prépare $article ou $error pour la vue.
     */
    public function detail($id): void
    {
        $id = intval($id);
        $article = null;
        $error   = null;

        if ($id > 0) {
            $article = $this->modele->getArticleById($id);
            if (!$article) {
                $error = "Article introuvable.";
            }
        } else {
            $error = "Identifiant d'article invalide.";
        }

        include __DIR__ . '/../vues/articles/article_detail.php';
    }

    /**
     * Affiche le formulaire de création.
     * Vérifie les droits — redirige si non autorisé.
     */
    public function creation(): void
    {
        $role = $_SESSION['utilisateur_role'] ?? 'Visiteur';

        if (!in_array($role, ['Admin', 'Éditeur', 'Editeur'], true)) {
            $_SESSION['message']      = "❌ Vous n'avez pas les droits pour créer un article.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=articles');
            exit;
        }

        include __DIR__ . '/../vues/articles/article_creation.php';
    }

    /**
     * Traite la soumission du formulaire de création.
     * Valide, enregistre, puis redirige.
     */
    public function enregistrer(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=articles');
            exit;
        }

        $role = $_SESSION['utilisateur_role'] ?? 'Visiteur';

        if (!in_array($role, ['Admin', 'Éditeur', 'Editeur'], true)) {
            $_SESSION['message']      = "❌ Vous n'avez pas les droits pour créer un article.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=articles');
            exit;
        }

        require_once __DIR__ . '/../modèles/AdminArticlesModele.php';
        $modele = new AdminArticlesModele();

        $titre     = trim($_POST['titre']     ?? '');
        $contenu   = trim($_POST['contenu']   ?? '');
        $auteur    = $_SESSION['utilisateur_nom'] ?? 'Inconnu';
        $image_url = trim($_POST['image_url'] ?? '') ?: null;

        // Upload fichier prioritaire sur l'URL saisie
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/articles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target   = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image_url = 'images/articles/' . $filename;
            }
        }

        if ($titre && $contenu) {
            try {
                $modele->create([
                    'titre'     => $titre,
                    'contenu'   => $contenu,
                    'auteur'    => $auteur,
                    'image_url' => $image_url,
                ]);

                $_SESSION['message']      = "✅ Article publié avec succès !";
                $_SESSION['message_type'] = 'success';
            } catch (Exception $e) {
                $_SESSION['message']      = "❌ Erreur lors de la création : " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        } else {
            $_SESSION['message']      = "❌ Le titre et le contenu sont obligatoires.";
            $_SESSION['message_type'] = 'danger';
        }

        header('Location: ?page=articles');
        exit;
    }
}