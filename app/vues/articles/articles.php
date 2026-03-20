<?php
include(__DIR__ . '/../parties/header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Articles - FABLAB</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/header.css">
  <link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/article.css">
</head>
 
<body class="project-page">
 
<div class="particles" id="particles"></div>
 
<section class="hero-section">
  <h1 class="hero-title">Nos Articles</h1>
  <p class="hero-subtitle">Découvrez nos articles récents sur la technologie, l'innovation et le bien-être.</p>
</section>
 
<section class="section-recherche">
  <div class="search-filter">
    <input type="text" id="searchInput" placeholder=" Rechercher un article...">
    <select id="categoryFilter">
      <option value="all">Toutes les catégories</option>
    </select>
  </div>
</section>
 
<?php if (in_array($roleUtilisateur, ['Éditeur', 'Editeur', 'Admin'])): ?>
  <div class="article-action">
    <a href="?page=article_creation" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i> Créer un article
    </a>
  </div>
<?php endif; ?>
 
<main class="featured-section">
  <h2 class="section-title">Articles Récents</h2>
  <div class="projects-grid">
    <?php if (empty($articles)): ?>
      <div class="no-articles">
        <div class="no-articles-icon">
          <i class="fas fa-newspaper"></i>
        </div>
        <h3>Aucun article disponible</h3>
        <p>Il n'y a pas encore d'articles publiés. Revenez bientôt !</p>
      </div>
    <?php else: ?>
      <?php foreach ($articles as $article): ?>
        <div class="project-card">
          <div class="project-image">
            <?php if (!empty($article['image_url'])): ?>
              <?php
                $imageUrl = $article['image_url'];
                if (!preg_match('/^https?:\/\//i', $imageUrl)) {
                    $imageUrl = $GLOBALS['baseUrl'] . $imageUrl;
                }
              ?>
              <img src="<?= htmlspecialchars($imageUrl) ?>"
                   alt="<?= htmlspecialchars($article['titre']) ?>"
                   loading="lazy"
                   onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="image-fallback" style="display:none; width:100%; height:100%; background:linear-gradient(135deg,#00afa7,#008f88); align-items:center; justify-content:center;">
                <i class="fas fa-newspaper" style="font-size:3rem; color:white; opacity:0.7;"></i>
              </div>
            <?php else: ?>
              <div style="width:100%; height:100%; background:linear-gradient(135deg,#00afa7,#008f88); display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-newspaper" style="font-size:3rem; color:white; opacity:0.7;"></i>
              </div>
            <?php endif; ?>
          </div>
 
          <div class="project-content">
            <h3 class="project-title"><?= htmlspecialchars($article['titre']) ?></h3>
            <p class="project-description">
              <?php
                $excerpt = substr(strip_tags($article['contenu']), 0, 120);
                echo htmlspecialchars($excerpt) . (strlen($article['contenu']) > 120 ? '...' : '');
              ?>
            </p>
            <div class="project-tags">
              <span class="tag">✍️ <?= htmlspecialchars($article['auteur']) ?></span>
              <span class="tag">📅 <?= date('d/m/Y', strtotime($article['cree_le'])) ?></span>
            </div>
            <div class="action-buttons">
              <a href="?page=article-detail&id=<?= $article['id'] ?>" class="btn btn-primary">
                <i class="fas fa-book-open"></i> Lire l'article
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>
 
<script>
function createParticles() {
  const particles = document.getElementById('particles');
  for (let i = 0; i < 50; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.top = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 6 + 's';
    particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
    particles.appendChild(particle);
  }
}
document.addEventListener('DOMContentLoaded', createParticles);
 
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const categoryFilter = document.getElementById("categoryFilter");
  const cards = document.querySelectorAll(".project-card");
 
  function filtrer() {
    const searchText = searchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value.toLowerCase();
 
    cards.forEach(card => {
      const title = card.querySelector(".project-title").textContent.toLowerCase();
      const description = card.querySelector(".project-description").textContent.toLowerCase();
      const category = card.dataset.categorie?.toLowerCase() || "autre";
 
      const matchTexte = title.includes(searchText) || description.includes(searchText);
      const matchCategorie = selectedCategory === "all" || category === selectedCategory;
 
      card.style.display = (matchTexte && matchCategorie) ? "block" : "none";
    });
  }
 
  searchInput.addEventListener("input", filtrer);
  categoryFilter.addEventListener("change", filtrer);
});
</script>
 
<?php include(__DIR__ . '/../parties/footer.php'); ?>
</body>
</html>