<?php
require_once __DIR__ . '/../config/database.php';

class CommentairesVideoModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

 
    public function listForVideo(int $videoId): array
    {
        $sql = "SELECT 
                    c.id, c.texte, c.created_at, c.user_id, c.video_id,
                    u.nom AS auteur,
                    u.photo AS user_photo
                FROM commentaires c
                INNER JOIN connexion u ON u.id = c.user_id
                WHERE c.video_id = :video_id
                ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':video_id' => $videoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function create(int $videoId, int $userId, string $texte): bool
    {
        $sql = "INSERT INTO commentaires (video_id, user_id, texte, created_at)
                VALUES (:video_id, :user_id, :texte, NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':video_id' => $videoId,
            ':user_id' => $userId,
            ':texte' => $texte
        ]);
    }

  
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
