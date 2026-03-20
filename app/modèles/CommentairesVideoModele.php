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
                    c.id, c.texte, c.cree_le, c.utilisateur_id, c.video_id,
                    u.nom AS auteur,
                    u.photo AS user_photo
                FROM commentaires c
                INNER JOIN utilisateurs u ON u.id = c.utilisateur_id
                WHERE c.video_id = :video_id
                ORDER BY c.cree_le DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':video_id' => $videoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $videoId, int $userId, string $texte): int|false
    {
        $stmt = $this->db->prepare(
            "INSERT INTO commentaires (video_id, utilisateur_id, texte, cree_le)
             VALUES (:video_id, :utilisateur_id, :texte, NOW())"
        );
        $ok = $stmt->execute([
            ':video_id'        => $videoId,
            ':utilisateur_id'  => $userId,
            ':texte'           => $texte,
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM commentaires WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}