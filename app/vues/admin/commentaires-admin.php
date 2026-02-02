<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
    header('Location: ?page=login');
    exit;
}

$GLOBALS['baseUrl'] = $GLOBALS['baseUrl'] ?? '/Fablabrobot/public/';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Commentaires WebTV</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $GLOBALS['baseUrl'] ?>css/admin.css">
</head>

<body>
<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-logo">
                <a href="?page=admin">
                    <img src="<?= $GLOBALS['baseUrl'] ?>images/ajc_logo_blanc.png" alt="AJC">
                </a>
            </div>
            <?php require __DIR__ . '/../parties/sidebar.php'; ?>
        </div>
        <div class="sidebar-footer">
            <a href="?page=logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- CONTENU -->
    <div class="main-content">

        <!-- Dashboard wrapper -->
        <div class="dashboard">

            <h1>
                <i class="fas fa-comments"></i>
                Gestion des Commentaires WebTV
            </h1>

            <!-- Messages flash -->
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type'] ?? 'info') ?>">
                    <i class="fas fa-<?= $_SESSION['message_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="cards">
                <div class="card">
                    <h3><i class="fas fa-comments"></i> Total</h3>
                    <div class="card-value"><?= $stats['total'] ?? 0 ?></div>
                    <p>Commentaires au total</p>
                </div>

                <div class="card">
                    <h3><i class="fas fa-calendar-week"></i> Cette semaine</h3>
                    <div class="card-value"><?= $stats['recent'] ?? 0 ?></div>
                    <p>Derniers 7 jours</p>
                </div>
            </div>

            <!-- Barre de recherche -->
            <div class="action-buttons">
                <form method="get" style="flex: 1; max-width: 500px;">
                    <input type="hidden" name="page" value="admin-comments">
                    <div class="search-bar">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="🔍 Rechercher dans les commentaires, auteurs, vidéos..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                        >
                    </div>
                </form>

                <?php if (!empty($_GET['q'])): ?>
                    <a href="?page=admin-comments" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </div>

            <!-- Table des commentaires -->
            <div class="articles-table">
                <div class="table-header">
                    <h2>
                        <i class="fas fa-list"></i>
                        Liste des commentaires
                        <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 400;">
                            (<?= count($commentaires) ?>)
                        </span>
                    </h2>
                </div>

                <?php if (!empty($commentaires)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th width="200">Auteur</th>
                                <th width="250">Vidéo</th>
                                <th>Commentaire</th>
                                <th width="150">Date</th>
                                <th width="100" style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commentaires as $c): ?>
                                <tr>
                                    <td style="color: var(--text-muted);">#<?= (int)$c['id'] ?></td>
                                    
                                    <td>
                                        <strong style="color: var(--primary-color);">
                                            <?= htmlspecialchars($c['auteur'] ?? 'Inconnu') ?>
                                        </strong>
                                        <?php if (!empty($c['user_email'])): ?>
                                            <br>
                                            <small style="color: var(--text-muted);">
                                                <?= htmlspecialchars($c['user_email']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <?php if (!empty($c['video_titre'])): ?>
                                            <a href="?page=webtv&video=<?= (int)$c['video_id'] ?>" 
                                               target="_blank" 
                                               style="color: var(--primary-color); text-decoration: none;">
                                                <i class="fas fa-video"></i>
                                                <?= htmlspecialchars($c['video_titre']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted);">—</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <div style="max-width: 400px; overflow: hidden; text-overflow: ellipsis;">
                                            <?= htmlspecialchars($c['texte'] ?? '') ?>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <small style="color: var(--text-muted);">
                                            <?= !empty($c['created_at']) ? date('d/m/Y H:i', strtotime($c['created_at'])) : '—' ?>
                                        </small>
                                    </td>
                                    
                                    <td style="text-align: center;">
                                        <form method="post" style="display: inline;" onsubmit="return confirm('Supprimer ce commentaire définitivement ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-small" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: 80px 20px; text-align: center;">
                        <i class="fas fa-comments" style="font-size: 4rem; color: var(--text-muted); opacity: 0.5; margin-bottom: 20px;"></i>
                        <p style="color: var(--text-muted); font-size: 1.1rem;">
                            <?php if (!empty($_GET['q'])): ?>
                                Aucun commentaire trouvé pour "<?= htmlspecialchars($_GET['q']) ?>"
                            <?php else: ?>
                                Aucun commentaire pour le moment
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>