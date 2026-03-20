<?php
require_once __DIR__ . '/../config/database.php';

class ArticleModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    public function getAllArticles(): array
    {
        $stmt = $this->db->query("SELECT * FROM articles ORDER BY cree_le DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}