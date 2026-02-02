<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!in_array($_SESSION['utilisateur_role'] ?? '', ['Éditeur', 'Admin'])) {
    header('Location: ?page=articles');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un Article</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .image-preview-container {
      margin-top: 15px;
      display: none;
    }
    .image-preview-container img {
      max-width: 100%;
      max-height: 250px;
      border-radius: 8px;
      border: 2px solid var(--primary-color, #00afa7);
    }
    .info-box {
      background: rgba(0, 175, 167, 0.1);
      border: 1px solid rgba(0, 175, 167, 0.3);
      padding: 12px;
      border-radius: 8px;
      margin-top: 10px;
      font-size: 0.9rem;
    }
    .loading-spinner {
      display: none;
      text-align: center;
      padding: 10px;
      color: #00afa7;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1><i class="fas fa-pen-nib"></i> Créer un nouvel article</h1>
    
    <?php if (!empty($_SESSION['message'])): ?>
      <div class="alert alert-<?= $_SESSION['message_type'] ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
      </div>
      <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form action="?page=article_enregistrer" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Titre *</label>
        <input type="text" name="titre" required>
      </div>

      <div class="form-group">
        <label>Contenu *</label>
        <textarea name="contenu" rows="8" required></textarea>
      </div>

      
      <div class="form-group">
        <label>URL de l'image (optionnel)</label>
        <input type="text" 
               id="image_url" 
               name="image_url" 
               placeholder="https://exemple.com/image.jpg"
               oninput="previewImage(this.value)">
        
        <div class="info-box">
          <strong>💡 Astuce :</strong> Vous pouvez coller n'importe quelle URL d'image depuis Google, Discord, etc.<br>
          Ou utiliser le champ ci-dessous pour uploader un fichier depuis votre ordinateur.
        </div>

        <div id="imagePreviewContainer" class="image-preview-container">
          <p><strong>Aperçu :</strong></p>
          <img id="imagePreview" alt="Aperçu de l'image">
          <div id="loadingSpinner" class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i> Chargement...
          </div>
        </div>
      </div>


      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane"></i> Publier
        </button>
        <a href="?page=articles" class="btn btn-secondary">
          <i class="fas fa-times"></i> Annuler
        </a>
      </div>
    </form>
  </div>

  <script>
    let imageLoadTimeout = null;

    function previewImage(url) {
      const preview = document.getElementById('imagePreview');
      const container = document.getElementById('imagePreviewContainer');
      const spinner = document.getElementById('loadingSpinner');

      
      if (imageLoadTimeout) {
        clearTimeout(imageLoadTimeout);
      }

     
      container.style.display = 'none';
      spinner.style.display = 'none';

      if (url && url.trim() !== '') {
        container.style.display = 'block';
        spinner.style.display = 'block';
        preview.style.opacity = '0.3';

        
        imageLoadTimeout = setTimeout(() => {
          spinner.style.display = 'none';
          preview.style.opacity = '1';
        }, 5000);

        
        preview.src = url;

        preview.onerror = function() {
          
          console.log('Image directe échouée, tentative avec proxy...');
          const proxyUrl = '../app/proxy-image.php?url=' + encodeURIComponent(url);
          preview.src = proxyUrl;

          preview.onerror = function() {
            clearTimeout(imageLoadTimeout);
            spinner.style.display = 'none';
            preview.style.opacity = '1';
            container.style.display = 'none';
            console.error('Impossible de charger l\'image');
          };
        };

        preview.onload = function() {
          clearTimeout(imageLoadTimeout);
          spinner.style.display = 'none';
          preview.style.opacity = '1';
        };
      }
    }
  </script>
</body>
</html>