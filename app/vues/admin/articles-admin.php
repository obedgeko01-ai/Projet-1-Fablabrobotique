<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Articles - Admin FABLAB</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/admin.css">
</head>

<body>
<div class="admin-container">
    <aside class="sidebar">
        <div>
            <div class="sidebar-logo">
                <a href="?page=admin">
                    <img src="../public/images/ajc_logo_blanc.png" alt="AJC Logo">
                </a>
            </div>
            <?php include __DIR__ . '/../parties/sidebar.php'; ?>
        </div>
        <div class="sidebar-footer">
            <a href="?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </aside>

    <div class="main-content">
        <header class="admin-header">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Rechercher un article..." onkeyup="searchArticles()">
            </div>
        </header>

        <section class="dashboard">
            <h1><i class="fas fa-newspaper"></i> Gestion des Articles</h1>

            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                    <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="openModal('create')">
                    <i class="fas fa-plus"></i> Nouvel Article
                </button>
                <span class="stats-badge">
                    <i class="fas fa-file-alt"></i> <?= $total_articles ?> article(s)
                </span>
            </div>

            <?php if (empty($articles)): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>Aucun article pour le moment. Créez-en un pour commencer !</p>
                </div>
            <?php else: ?>
                <table id="articlesTable">
                    <thead>
                        <tr>
                            <th style="width: 120px; text-align: center;">Image</th>
                            <th>Titre</th>
                            <th>Extrait</th>
                            <th>Auteur</th>
                            <th>Date</th>
                            <th style="width: 120px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td style="text-align: center; padding: 10px;">
                                    <?php if (!empty($article['image_url'])): ?>
                                        <div class="image-container" style="display: inline-block; position: relative;">
                                            <!-- Essayer d'abord l'image directe -->
                                            <img src="<?= htmlspecialchars($article['image_url']) ?>" 
                                                 alt="<?= htmlspecialchars($article['titre']) ?>" 
                                                 class="article-image-thumb"
                                                 style="width: 100px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid var(--card-border); display: block;"
                                                 onerror="tryProxyImage(this, '<?= htmlspecialchars($article['image_url'], ENT_QUOTES) ?>')">
                                            <div class="no-image-fallback" style="display: none; width: 100px; height: 70px; background: rgba(0, 175, 167, 0.1); border-radius: 8px; align-items: center; justify-content: center; flex-direction: column; color: var(--text-muted); font-size: 0.7rem; border: 2px dashed var(--card-border); padding: 5px; text-align: center;">
                                                <i class="fas fa-link" style="font-size: 1.2rem; margin-bottom: 3px;"></i>
                                                <span>URL enregistrée</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-image" style="display: inline-flex; width: 100px; height: 70px; background: rgba(0, 175, 167, 0.1); border-radius: 8px; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.5rem; border: 2px dashed var(--card-border);">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong style="color: var(--primary-color);"><?= htmlspecialchars($article['titre']) ?></strong></td>
                                <td style="color: var(--text-muted);"><?= htmlspecialchars(substr($article['contenu'], 0, 80)) ?>...</td>
                                <td><?= htmlspecialchars($article['auteur']) ?></td>
                                <td><?= date('d/m/Y', strtotime($article['created_at'])) ?></td>
                                <td>
                                    <div class="table-actions" style="display: flex; gap: 8px; justify-content: center;">
                                        <button class="btn btn-warning btn-small" onclick='editArticle(<?= $article["id"] ?>)' title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-small" onclick="deleteArticle(<?= $article['id'] ?>, '<?= addslashes($article['titre']) ?>')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</div>


<div id="articleModal" class="modal">
  <div class="modal-content" style="max-width: 700px;">
    <div class="modal-header">
        <h2 id="modalTitle">Nouvel Article</h2>
        <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form id="articleForm" method="POST" action="?page=admin-articles">
        <input type="hidden" name="action" id="formAction" value="create">
        <input type="hidden" name="article_id" id="articleId">

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" required>
        </div>

        <div class="form-group">
            <label for="contenu">Contenu *</label>
            <textarea id="contenu" name="contenu" rows="8" required></textarea>
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" required>
        </div>

        <div class="form-group">
            <label for="image_url">URL de l'image</label>
            <input type="text" id="image_url" name="image_url" placeholder="https://exemple.com/image.jpg ou coller l'URL depuis votre recherche" oninput="previewImage(this.value)">
            
            <div style="background: rgba(0, 175, 167, 0.1); border: 1px solid rgba(0, 175, 167, 0.3); padding: 12px; border-radius: 8px; margin-top: 10px; font-size: 0.9rem; color: var(--primary-color);">
                <strong><i class="fas fa-check-circle"></i> Toutes les URLs d'images sont acceptées !</strong><br>
                • Vous pouvez coller n'importe quelle URL (Google, Brave, etc.)<br>
                • L'aperçu s'affiche grâce au système de proxy<br>
                • L'image s'affichera également sur le site public
            </div>
            
            <div id="imagePreviewContainer" style="margin-top: 15px; display: none;">
                <p style="color: var(--primary-color); font-weight: 600; margin-bottom: 10px;">
                    <i class="fas fa-eye"></i> Aperçu de l'image :
                </p>
                <div style="position: relative; display: inline-block;">
                    <img id="imagePreview" style="max-width: 100%; max-height: 200px; border-radius: 10px; border: 2px solid var(--primary-color);" alt="Aperçu">
                    <div id="imageLoadingSpinner" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.7); padding: 20px; border-radius: 10px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
                    </div>
                </div>
            </div>
            
            <div id="imagePreviewError" style="margin-top: 15px; display: none; background: rgba(255, 107, 107, 0.1); border: 1px solid rgba(255, 107, 107, 0.3); padding: 12px; border-radius: 8px; color: #ff6b6b;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Impossible de charger l'aperçu</strong><br>
                Vérifiez que l'URL est correcte. L'image sera quand même enregistrée et pourra s'afficher sur le site public.
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-danger" onclick="closeModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
  </div>
</div>


<script id="articlesData" type="application/json">
<?= json_encode($articles ?? []) ?>
</script>

<script>

const articlesData = JSON.parse(document.getElementById('articlesData').textContent);

let imageLoadTimeout = null;


function tryProxyImage(img, originalUrl) {
   
    const proxyUrl = '../app/proxy-image.php?url=' + encodeURIComponent(originalUrl);
    img.src = proxyUrl;
    
    
    img.onerror = function() {
        img.style.display = 'none';
        const fallback = img.nextElementSibling;
        if (fallback && fallback.classList.contains('no-image-fallback')) {
            fallback.style.display = 'flex';
        }
    };
}


function previewImage(url) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const errorDiv = document.getElementById('imagePreviewError');
    const spinner = document.getElementById('imageLoadingSpinner');
    
   
    if (imageLoadTimeout) {
        clearTimeout(imageLoadTimeout);
    }
    
   
    container.style.display = 'none';
    errorDiv.style.display = 'none';
    
    if (url && url.trim() !== '') {
        
        spinner.style.display = 'block';
        container.style.display = 'block';
        preview.style.opacity = '0.3';
        
        
        imageLoadTimeout = setTimeout(() => {
            spinner.style.display = 'none';
            preview.style.opacity = '1';
            container.style.display = 'none';
            errorDiv.style.display = 'block';
            console.log('⏱️ Timeout: Impossible de charger l\'aperçu');
        }, 5000);
        
        
        preview.src = url;
        
        preview.onerror = function() {
           
            console.log('ℹ️ Image directe échouée, tentative avec le proxy...');
            const proxyUrl = '../app/proxy-image.php?url=' + encodeURIComponent(url);
            preview.src = proxyUrl;
            
         
            preview.onerror = function() {
                clearTimeout(imageLoadTimeout);
                spinner.style.display = 'none';
                preview.style.opacity = '1';
                container.style.display = 'none';
                errorDiv.style.display = 'block';
                console.error('❌ Impossible de charger l\'image même via le proxy');
            };
        };
        
        preview.onload = function() {
            clearTimeout(imageLoadTimeout);
            spinner.style.display = 'none';
            preview.style.opacity = '1';
            errorDiv.style.display = 'none';
            console.log('✅ Aperçu chargé avec succès');
        };
    }
}

function openModal(action, article = null) {
    const modal = document.getElementById('articleModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('articleForm');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const errorDiv = document.getElementById('imagePreviewError');

    if (action === 'create') {
        title.textContent = "Nouvel Article";
        document.getElementById('formAction').value = 'create';
        form.reset();
        previewContainer.style.display = 'none';
        errorDiv.style.display = 'none';
    } else {
        title.textContent = "Modifier l'article";
        document.getElementById('formAction').value = 'update';
        document.getElementById('articleId').value = article.id;
        document.getElementById('titre').value = article.titre;
        document.getElementById('contenu').value = article.contenu;
        document.getElementById('auteur').value = article.auteur;
        document.getElementById('image_url').value = article.image_url || '';
        
       
        if (article.image_url) {
            previewImage(article.image_url);
        }
    }
    modal.classList.add('active');
}

function editArticle(id) {
    const article = articlesData.find(a => a.id == id);
    if (article) {
        openModal('edit', article);
    } else {
        console.error('Article non trouvé:', id);
        alert('❌ Erreur: Article introuvable');
    }
}

function closeModal() { 
    document.getElementById('articleModal').classList.remove('active');
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('imagePreviewError').style.display = 'none';
    if (imageLoadTimeout) {
        clearTimeout(imageLoadTimeout);
    }
}

function deleteArticle(id, titre) {
    if (confirm(`⚠️ Êtes-vous sûr de vouloir supprimer l'article "${titre}" ?\n\nCette action est irréversible.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?page=admin-articles';
        form.innerHTML = `<input type="hidden" name="action" value="delete">
                          <input type="hidden" name="article_id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

function searchArticles() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#articlesTable tbody tr');
    let visibleCount = 0;
    
    rows.forEach(r => {
        const titre = r.children[1].textContent.toLowerCase();
        const auteur = r.children[3].textContent.toLowerCase();
        const isVisible = titre.includes(val) || auteur.includes(val);
        r.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleCount++;
    });
}


document.getElementById('articleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});


document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

console.log('📰 Page Articles Admin chargée -', articlesData.length, 'article(s)');
console.log('🔧 Système de proxy activé pour les images');
</script>
</body>
</html>
