<?php
require_once __DIR__ . '/../config/database.php';

class AdminModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    public function getAllProjets(): array
    {
        $stmt = $this->db->query("SELECT * FROM projets ORDER BY cree_le DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatistiques(): array
    {
        return [
            'total_projets'       => $this->db->query("SELECT COUNT(*) FROM projets")->fetchColumn(),
            'total_articles'      => $this->db->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
            'total_utilisateurs'  => $this->db->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn(),
        ];
    }
}