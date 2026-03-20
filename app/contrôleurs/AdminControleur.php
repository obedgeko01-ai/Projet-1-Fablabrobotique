<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modèles/AdminModele.php';

class AdminControleur
{
    private AdminModele $modele;

    public function __construct()
    {
        $this->modele = new AdminModele();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['utilisateur_role']) || strtolower($_SESSION['utilisateur_role']) !== 'admin') {
            header('Location: ?page=login');
            exit;
        }

        $projets       = $this->modele->getAllProjets();
        $total_projets = count($projets);

        require __DIR__ . '/../vues/admin/projets-admin.php';
    }
}