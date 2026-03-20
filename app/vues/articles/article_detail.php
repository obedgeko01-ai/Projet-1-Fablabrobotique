<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($article) ? htmlspecialchars($article['titre']) : "Article" ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
   <link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/article.css">
  
  
</head>

<body>
<div class="container">

  <a href="?page=articles" class="back-btn">
    <i class="fas fa-arrow-left"></i> Retour aux articles
  </a>

  <?php if (isset($error)): ?>
    <div class="error-page">
      <div class="error-icon">⚠️</div>
      <h1 class="error-title">Erreur</h1>
      <p class="error-text"><?= htmlspecialchars($error) ?></p>
      <a href="?page=articles" class="back-btn">
        <i class="fas fa-arrow-left"></i> Retour aux articles
      </a>
    </div>

  <?php else: ?>
    <div class="project-detail">

      <div class="project-header">
        <?php if (!empty($article['image_url'])): ?>
          <?php
            $imageUrl = $article['image_url'];
            if (!preg_match('/^https?:\/\//i', $imageUrl)) {
                $imageUrl = $GLOBALS['baseUrl'] . $imageUrl;
            }
          ?>
          <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Image article" class="project-image full">
        <?php endif; ?>

        <div class="project-header-content">
          <h1 class="project-title"><?= htmlspecialchars($article['titre']) ?></h1>
          <div class="project-meta">
            <div class="meta-item">✍️ <?= htmlspecialchars($article['auteur']) ?></div>
            <div class="meta-item">📅 <?= date('d M Y', strtotime($article['cree_le'])) ?></div>
            <div class="status-badge">Article</div>
          </div>
        </div>
      </div>

      <div class="project-content">
        <div class="content-grid">

          <div class="main-content">
            <div class="description-section">
              <h2>📖 Contenu de l'article</h2>
              <div class="description-text">
                <?= nl2br(htmlspecialchars($article['contenu'])) ?>
              </div>
            </div>

            <?php if (!empty($article['resume']) || !empty($article['tags'])): ?>
            <div class="features-section">
              <h2>ℹ️ Informations complémentaires</h2>
              <div class="features-grid">

                <?php if (!empty($article['resume'])): ?>
                <div class="feature-card">
                  <h4>📝 Résumé</h4>
                  <p><?= htmlspecialchars($article['resume']) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($article['tags'])): ?>
                <div class="feature-card">
                  <h4>🏷️ Tags</h4>
                  <div class="tech-stack">
                    <?php foreach (explode(',', $article['tags']) as $tag): ?>
                      <span class="tech-tag"><?= trim(htmlspecialchars($tag)) ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endif; ?>

              </div>
            </div>
            <?php endif; ?>
          </div>

          <div class="sidebar">
            <h3>📋 Informations</h3>

            <div class="info-item">
              <div class="info-label">Statut</div>
              <div class="info-value"><span class="status-badge">Publié</span></div>
            </div>

            <div class="info-item">
              <div class="info-label">Auteur</div>
              <div class="info-value"><?= htmlspecialchars($article['auteur']) ?></div>
            </div>

            <div class="info-item">
              <div class="info-label">Date de création</div>
              <div class="info-value"><?= date('d/m/Y', strtotime($article['cree_le'])) ?></div>
            </div>

            <?php if (!empty($article['modifie_le']) && $article['modifie_le'] != $article['cree_le']): ?>
            <div class="info-item">
              <div class="info-label">Dernière mise à jour</div>
              <div class="info-value"><?= date('d/m/Y', strtotime($article['modifie_le'])) ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($article['categorie'])): ?>
            <div class="info-item">
              <div class="info-label">Catégorie</div>
              <div class="info-value"><?= htmlspecialchars($article['categorie']) ?></div>
            </div>
            <?php endif; ?>

            <div class="project-links">
              <a href="?page=articles" class="project-link github">📚 Tous les articles</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  <?php endif; ?>

</div>

<?php include(__DIR__ . '/../parties/footer.php'); ?>
</body>
</html>