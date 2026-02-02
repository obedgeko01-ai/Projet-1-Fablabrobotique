<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets - Admin FABLAB</title>

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
                <input type="text" id="searchInput" placeholder="Rechercher un projet..." onkeyup="searchProjects()">
            </div>
        </header>

        <section class="dashboard">
            <h1><i class="fas fa-project-diagram"></i> Gestion des Projets</h1>

            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                    <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="openModal('create')">
                    <i class="fas fa-plus"></i> Nouveau Projet
                </button>
                <span class="stats-badge">
                    <i class="fas fa-folder"></i> <?= $total_projects ?> projet(s)
                </span>
            </div>

            <?php if (empty($projects)): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>Aucun projet pour le moment. Créez-en un pour commencer !</p>
                </div>
            <?php else: ?>
                <table id="projectsTable">
                    <thead>
                        <tr>
                            <th style="width: 120px; text-align: center;">Image</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Auteur</th>
                            <th>Technologies</th>
                            <th>Date</th>
                            <th style="width: 120px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                            <?php
                           
                            $imageSrc = '';
                            if (!empty($project['image_url'])) {
                                if (str_starts_with($project['image_url'], 'http://') || str_starts_with($project['image_url'], 'https://')) {
                                    $imageSrc = $project['image_url'];
                                } else {
                                    $imageSrc = '../public/images/projets/' . $project['image_url'];
                                }
                            }
                            ?>
                            <tr>
                                <td style="text-align: center; padding: 10px;">
                                    <?php if (!empty($imageSrc)): ?>
                                        <div class="image-container" style="display: inline-block; position: relative;">
                                            <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                                 alt="<?= htmlspecialchars($project['title']) ?>" 
                                                 class="project-image-thumb"
                                                 style="width: 100px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid var(--card-border); display: block;"
                                                 onerror="tryProxyImage(this, '<?= htmlspecialchars($imageSrc, ENT_QUOTES) ?>')">
                                            <div class="no-image-fallback" style="display: none; width: 100px; height: 70px; background: rgba(0, 175, 167, 0.1); border-radius: 8px; align-items: center; justify-content: center; flex-direction: column; color: var(--text-muted); font-size: 0.7rem; border: 2px dashed var(--card-border); padding: 5px; text-align: center;">
                                                <i class="fas fa-link" style="font-size: 1.2rem; margin-bottom: 3px;"></i>
                                                <span>URL enregistrée</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-image" style="display: inline-flex; width: 100px; height: 70px; background: rgba(0, 175, 167, 0.1); border-radius: 8px; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.5rem; border: 2px dashed var(--card-border);">
                                            <i class="fas fa-project-diagram"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong style="color: var(--primary-color);"><?= htmlspecialchars($project['title']) ?></strong></td>
                                <td style="color: var(--text-muted);"><?= htmlspecialchars(substr($project['description'], 0, 60)) ?>...</td>
                                <td><?= htmlspecialchars($project['auteur'] ?? 'N/A') ?></td>
                                <td style="color: var(--text-muted); font-size: 0.85rem;"><?= htmlspecialchars(substr($project['technologies'] ?? 'N/A', 0, 40)) ?></td>
                                <td><?= date('d/m/Y', strtotime($project['created_at'])) ?></td>
                                <td>
                                    <div class="table-actions" style="display: flex; gap: 8px; justify-content: center;">
                                        <button class="btn btn-warning btn-small" onclick='editProject(<?= $project["id"] ?>)' title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-small" onclick="deleteProject(<?= $project['id'] ?>, '<?= addslashes($project['title']) ?>')" title="Supprimer">
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


<div id="projectModal" class="modal">
  <div class="modal-content" style="max-width: 800px;">
    <div class="modal-header">
        <h2 id="modalTitle">Nouveau Projet</h2>
        <button class="close-modal" onclick="closeModal()">&times;</button>
    </div>

    <form id="projectForm" method="POST" action="?page=admin-projets" enctype="multipart/form-data">
        <input type="hidden" name="action" id="formAction" value="create">
        <input type="hidden" name="project_id" id="projectId">

        <div class="form-group">
            <label for="title">Titre du projet *</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description courte *</label>
            <textarea id="description" name="description" rows="3" required placeholder="Résumé du projet en quelques lignes..."></textarea>
        </div>

        <div class="form-group">
            <label for="auteur">Auteur *</label>
            <input type="text" id="auteur" name="auteur" placeholder="Nom de l'auteur du projet">
        </div>

        <div class="form-group">
            <label for="description_detailed">Description détaillée</label>
            <textarea id="description_detailed" name="description_detailed" rows="6" placeholder="Description complète du projet..."></textarea>
        </div>

        <div class="form-group">
            <label for="technologies">Technologies utilisées</label>
            <input type="text" id="technologies" name="technologies" placeholder="Ex: Python, Arduino, TensorFlow...">
        </div>

        <div class="form-group">
            <label for="features">Fonctionnalités principales</label>
            <textarea id="features" name="features" rows="3" placeholder="Séparez par | exemple: Navigation autonome|Détection d'obstacles"></textarea>
        </div>

        <div class="form-group">
            <label for="challenges">Défis rencontrés</label>
            <textarea id="challenges" name="challenges" rows="3" placeholder="Défis techniques et solutions..."></textarea>
        </div>

       
        <div class="form-group">
            <label for="image_url">URL d'image (externe)</label>
            <input type="text" id="image_url" name="image_url" placeholder="https://exemple.com/image.jpg" oninput="previewImage(this.value)">
            
            <div style="background: rgba(0, 175, 167, 0.1); border: 1px solid rgba(0, 175, 167, 0.3); padding: 12px; border-radius: 8px; margin-top: 10px; font-size: 0.9rem; color: var(--primary-color);">
                <strong><i class="fas fa-check-circle"></i> Toutes les URLs d'images sont acceptées !</strong><br>
                • Vous pouvez coller n'importe quelle URL (Google, Discord, Wikipedia, etc.)<br>
                • L'image s'affichera sur le site public
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
                Vérifiez que l'URL est correcte.
            </div>
        </div>

       
        <div class="form-group">
            <label>OU uploader une image locale (PNG, JPG...)</label>
            <input type="file" id="imageFile" name="image" accept="image/*" onchange="previewLocalImage(this)">
            <div style="background: rgba(0, 175, 167, 0.1); border: 1px solid rgba(0, 175, 167, 0.3); padding: 12px; border-radius: 8px; margin-top: 10px; font-size: 0.9rem; color: var(--primary-color);">
                <strong><i class="fas fa-upload"></i> Upload local</strong><br>
                • Accepte PNG, JPG, JPEG, GIF, WebP<br>
                • Si vous uploadez un fichier, il a priorité sur l'URL ci-dessus<br>
                • Le fichier sera sauvegardé dans <code>public/images/projets/</code>
            </div>

            <div id="localPreviewContainer" style="margin-top: 15px; display: none;">
                <p style="color: var(--primary-color); font-weight: 600; margin-bottom: 10px;">
                    <i class="fas fa-eye"></i> Aperçu du fichier :
                </p>
                <img id="localPreview" style="max-width: 100%; max-height: 200px; border-radius: 10px; border: 2px solid var(--primary-color);" alt="Aperçu local">
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


<script id="projectsData" type="application/json">
<?= json_encode($projects ?? []) ?>
</script>

<script>
const projectsData = JSON.parse(document.getElementById('projectsData').textContent);
let imageLoadTimeout = null;


function previewLocalImage(input) {
    const file = input.files[0];
    const container = document.getElementById('localPreviewContainer');
    const preview = document.getElementById('localPreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(file);

        
        document.getElementById('image_url').value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('imagePreviewError').style.display = 'none';
    } else {
        container.style.display = 'none';
    }
}


function previewImage(url) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const errorDiv = document.getElementById('imagePreviewError');
    const spinner = document.getElementById('imageLoadingSpinner');
    
    if (imageLoadTimeout) clearTimeout(imageLoadTimeout);
    
    container.style.display = 'none';
    errorDiv.style.display = 'none';

    
    document.getElementById('imageFile').value = '';
    document.getElementById('localPreviewContainer').style.display = 'none';
    
    if (url && url.trim() !== '') {
        spinner.style.display = 'block';
        container.style.display = 'block';
        preview.style.opacity = '0.3';
        
        imageLoadTimeout = setTimeout(() => {
            spinner.style.display = 'none';
            preview.style.opacity = '1';
            container.style.display = 'none';
            errorDiv.style.display = 'block';
        }, 5000);
        
        preview.src = url;
        
        preview.onerror = function() {
            const proxyUrl = '../app/proxy-image.php?url=' + encodeURIComponent(url);
            preview.src = proxyUrl;
            preview.onerror = function() {
                clearTimeout(imageLoadTimeout);
                spinner.style.display = 'none';
                preview.style.opacity = '1';
                container.style.display = 'none';
                errorDiv.style.display = 'block';
            };
        };
        
        preview.onload = function() {
            clearTimeout(imageLoadTimeout);
            spinner.style.display = 'none';
            preview.style.opacity = '1';
            errorDiv.style.display = 'none';
        };
    }
}

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

function openModal(action, project = null) {
    const modal = document.getElementById('projectModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('projectForm');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const localPreviewContainer = document.getElementById('localPreviewContainer');
    const errorDiv = document.getElementById('imagePreviewError');

    // Réinitialiser les préviews
    previewContainer.style.display = 'none';
    localPreviewContainer.style.display = 'none';
    errorDiv.style.display = 'none';

    if (action === 'create') {
        title.textContent = "Nouveau Projet";
        document.getElementById('formAction').value = 'create';
        form.reset();
    } else {
        title.textContent = "Modifier le projet";
        document.getElementById('formAction').value = 'update';
        document.getElementById('projectId').value = project.id;
        document.getElementById('title').value = project.title;
        document.getElementById('description').value = project.description;
        document.getElementById('auteur').value = project.auteur || '';
        document.getElementById('description_detailed').value = project.description_detailed || '';
        document.getElementById('technologies').value = project.technologies || '';
        document.getElementById('features').value = project.features || '';
        document.getElementById('challenges').value = project.challenges || '';
        document.getElementById('image_url').value = project.image_url || '';
        
        
        if (project.image_url) {
            if (project.image_url.startsWith('http://') || project.image_url.startsWith('https://')) {
                previewImage(project.image_url); 
            } else {
             
                document.getElementById('localPreview').src = '../public/images/projets/' + project.image_url;
                localPreviewContainer.style.display = 'block';
                document.getElementById('image_url').value = ''; 
            }
        }
    }
    modal.classList.add('active');
}

function editProject(id) {
    const project = projectsData.find(p => p.id == id);
    if (project) {
        openModal('edit', project);
    } else {
        alert('❌ Projet introuvable');
    }
}

function closeModal() { 
    document.getElementById('projectModal').classList.remove('active');
    document.getElementById('imagePreviewContainer').style.display = 'none';
    document.getElementById('localPreviewContainer').style.display = 'none';
    document.getElementById('imagePreviewError').style.display = 'none';
    if (imageLoadTimeout) clearTimeout(imageLoadTimeout);
}

function deleteProject(id, titre) {
    if (confirm(`⚠️ Êtes-vous sûr de vouloir supprimer le projet "${titre}" ?\n\nCette action est irréversible.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?page=admin-projets';
        form.innerHTML = `<input type="hidden" name="action" value="delete">
                          <input type="hidden" name="project_id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

function searchProjects() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#projectsTable tbody tr');
    rows.forEach(r => {
        const titre = r.children[1].textContent.toLowerCase();
        const description = r.children[2].textContent.toLowerCase();
        r.style.display = (titre.includes(val) || description.includes(val)) ? '' : 'none';
    });
}

document.getElementById('projectModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

console.log('🚀 Page Projets Admin chargée -', projectsData.length, 'projet(s)');
</script>
</body>
</html>