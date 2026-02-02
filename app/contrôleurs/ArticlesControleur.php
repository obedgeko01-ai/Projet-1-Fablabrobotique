<?php


require_once __DIR__ . '/../modèles/ArticleModele.php';

class ArticlesControleur {

    private $modele;

    public function __construct() {
        $this->modele = new ArticleModele();
    }

    
    public function index() {
        $articles = $this->modele->getAllArticles();
        require __DIR__ . '/../vues/articles/articles.php';
    }

 
    public function detail($id) {
        $article = $this->modele->getArticleById($id);

        if ($article) {
            require __DIR__ . '/../vues/articles/article_detail.php';
        } else {
            echo "<h2>Article introuvable.</h2>";
        }
    }

   
    public function enregistrer(): void
    {
       
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=articles');
            exit;
        }

       
        $role = $_SESSION['utilisateur_role'] ?? 'Visiteur';
        if (!in_array($role, ['Admin', 'Éditeur'], true)) {
            $_SESSION['message'] = "Vous n'avez pas les droits pour créer un article.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=articles');
            exit;
        }

        require_once __DIR__ . '/../modèles/AdminArticlesModele.php';
        $modele = new AdminArticlesModele();

        $titre   = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $auteur  = $_SESSION['utilisateur_nom'] ?? 'Inconnu';
        $image_url = trim($_POST['image_url'] ?? ''); 

        
        if (empty($image_url)) {
            $image_url = null;
        }

        
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/articles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target = $uploadDir . $filename;

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
                    'image_url' => $image_url
                ]);

                $_SESSION['message'] = "✅ Article publié avec succès !";
                $_SESSION['message_type'] = 'success';
            } catch (Exception $e) {
                $_SESSION['message'] = "❌ Erreur lors de la création : " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        } else {
            $_SESSION['message'] = "❌ Le titre et le contenu sont obligatoires.";
            $_SESSION['message_type'] = 'danger';
        }

        header('Location: ?page=articles');
        exit;
    }

    
    public function creation(): void
    {
      
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $role = $_SESSION['utilisateur_role'] ?? 'Visiteur';
        if (!in_array($role, ['Admin', 'Éditeur'], true)) {
            $_SESSION['message'] = "❌ Vous n'avez pas les droits pour créer un article.";
            $_SESSION['message_type'] = 'danger';
            header('Location: ?page=articles');
            exit;
        }

        require __DIR__ . '/../vues/articles/article_creation.php';
    }
}