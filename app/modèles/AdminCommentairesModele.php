<?php
require_once __DIR__ . '/../config/database.php';

class AdminCommentairesModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    
    public function all(?string $q = null): array
    {
        $params = [];
        $sql = "SELECT
                    c.id, c.video_id, c.user_id,
                    c.texte, c.created_at,
                    v.titre AS video_titre, v.youtube_id,
                    u.nom AS auteur, u.email AS user_email
                FROM commentaires c
                LEFT JOIN videos v ON v.id = c.video_id
                LEFT JOIN connexion u ON u.id = c.user_id";

        if ($q && trim($q) !== '') {
            $sql .= " WHERE (c.texte LIKE :q OR v.titre LIKE :q OR u.nom LIKE :q OR u.email LIKE :q)";
            $params[':q'] = '%' . trim($q) . '%';
        }

        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getStats(): array
    {
        $total = (int)$this->db->query("SELECT COUNT(*) FROM commentaires")->fetchColumn();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM commentaires WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stmt->execute();
        $recent = (int)$stmt->fetchColumn();

        return [
            'total' => $total,
            'recent' => $recent
        ];
    }
}
