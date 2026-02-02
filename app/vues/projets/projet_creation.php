<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!in_array($_SESSION['utilisateur_role'] ?? '', ['Éditeur', 'Admin'], true)) {
    header('Location: ?page=projets');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un Projet</title>
  <link rel="stylesheet" href="css/admin.css">
  <style>
    .info-box {
      background: rgba(0, 175, 167, 0.1);
      border: 1px solid rgba(0, 175, 167, 0.3);
      padding: 12px;
      border-radius: 8px;
      margin-top: 8px;
      font-size: 0.85rem;
      color: rgba(245,245,245,0.7);
    }
    .image-preview-container {
      margin-top: 12px;
      display: none;
    }
    .image-preview-container img {
      max-width: 100%;
      max-height: 220px;
      border-radius: 8px;
      border: 2px solid #00afa7;
    }
    .section-separator {
      border: none;
      border-top: 1px solid rgba(0, 175, 167, 0.3);
      margin: 30px 0 10px;
    }
    .section-label {
      color: #00afa7;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 15px;
    }
    .section-label i {
      margin-right: 8px;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1><i class="fas fa-robot"></i> Créer un nouveau projet</h1>

    <?php if (!empty($_SESSION['message'])): ?>
      <div class="alert alert-<?= $_SESSION['message_type'] ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
      </div>
      <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form action="?page=projet_enregistrer" method="POST" enctype="multipart/form-data">

     
      <p class="section-label"><i class="fas fa-info-circle"></i> Informations principales</p>

      <div class="form-group">
        <label>Titre du projet *</label>
        <input type="text" name="titre" placeholder="Ex : Robot suiveur de ligne" required>
      </div>

      <div class="form-group">
        <label>Auteur</label>
        <input type="text" name="auteur" value="<?= htmlspecialchars($_SESSION['utilisateur_nom'] ?? '') ?>" placeholder="Votre nom">
      </div>

      <div class="form-group">
        <label>Description courte *</label>
        <textarea name="description" rows="3" placeholder="Une courte description du projet (affichée sur la carte)" required></textarea>
      </div>

     
      <hr class="section-separator">
      <p class="section-label"><i class="fas fa-file-alt"></i> Détails du projet</p>

      <div class="form-group">
        <label>Description détaillée</label>
        <textarea name="description_detailed" rows="5" placeholder="Une description plus complète du projet..."></textarea>
      </div>

      <div class="form-group">
        <label>Technologies utilisées</label>
        <input type="text" name="technologies" placeholder="Ex : Arduino, Moteurs DC, Capteur ultrasonique, Impression 3D">
        <div class="info-box">💡 Séparez les technologies par des virgules. Elles apparaîtront comme des badges sur la carte du projet.</div>
      </div>

      <div class="form-group">
        <label>Fonctionnalités principales</label>
        <textarea name="features" rows="3" placeholder="Ex : Navigation autonome|Détection d'obstacles|Contrôle Bluetooth"></textarea>
        <div class="info-box">💡 Séparez les fonctionnalités par des <strong>|</strong> (barre verticale).</div>
      </div>

      <div class="form-group">
        <label>Défis rencontrés</label>
        <textarea name="challenges" rows="3" placeholder="Décrivez les difficultés techniques et comment vous les avez résolues..."></textarea>
      </div>

      
      <hr class="section-separator">
      <p class="section-label"><i class="fas fa-image"></i> Image du projet</p>

      <div class="form-group">
        <label>URL d'image (optionnel)</label>
        <input type="text" id="image_url" name="image_url" placeholder="https://exemple.com/image.jpg" oninput="previewImage(this.value)">
        <div class="info-box">💡 Coller une URL d'image depuis Google, Discord, Wikipedia, etc.|Les images url ia fonctionne pas ! X( |    </div>

        <div id="imagePreviewContainer" class="image-preview-container">
          <p style="color:#00afa7; font-weight:600; margin-bottom:8px;"><i class="fas fa-eye"></i> Aperçu :</p>
          <img id="imagePreview" alt="Aperçu">
        </div>
      </div>

      <div class="form-group">
        <label>OU uploader une image (pas utiliser hormis url bug )</label>
        <input type="file" name="image" accept="image/*">
        <div class="info-box">💡 Si vous uploadez un fichier, il aura priorité sur l'URL.</div>
      </div>

     
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane"></i> Créer le projet
        </button>
        <a href="?page=projets" class="btn btn-secondary">
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

      if (imageLoadTimeout) clearTimeout(imageLoadTimeout);
      container.style.display = 'none';

      if (url && url.trim() !== '') {
        preview.style.opacity = '0.3';
        container.style.display = 'block';

        imageLoadTimeout = setTimeout(() => {
          preview.style.opacity = '1';
        }, 5000);

        preview.src = url;

        preview.onerror = function() {
          const proxyUrl = '../app/proxy-image.php?url=' + encodeURIComponent(url);
          preview.src = proxyUrl;
          preview.onerror = function() {
            clearTimeout(imageLoadTimeout);
            container.style.display = 'none';
          };
        };

        preview.onload = function() {
          clearTimeout(imageLoadTimeout);
          preview.style.opacity = '1';
        };
      }
    }
  </script>
</body>
</html>