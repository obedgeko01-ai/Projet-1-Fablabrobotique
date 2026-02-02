<?php
// app/modeles/AdminProjetsModele.php
require_once __DIR__ . '/../config/database.php';

class AdminProjetsModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    public function all(): array
    {
        $sql = "
            SELECT 
                id, 
                title, 
                description,
                CASE WHEN auteur = 11 THEN 'Fablabteam' ELSE auteur END AS auteur,
                description_detailed, 
                technologies, 
                image_url, 
                features, 
                challenges, 
                created_at, 
                updated_at
            FROM projects 
            ORDER BY created_at DESC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        
        $sql = "
            SELECT 
                id, 
                title, 
                description,
                CASE WHEN auteur = 11 THEN 'Fablabteam' ELSE auteur END AS auteur,
                description_detailed,
                technologies, 
                image_url, 
                features, 
                challenges,
                created_at, 
                updated_at
            FROM projects
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO projects
            (title, description, auteur, description_detailed, technologies, image_url, features, challenges, created_at, updated_at)
            VALUES 
            (:title, :description, :auteur, :description_detailed, :technologies, :image_url, :features, :challenges, NOW(), NOW())
        ";

        
        $auteur = empty($data['auteur']) ? 11 : (int)$data['auteur'];

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':title'                => $data['title'],
            ':description'          => $data['description'],
            ':auteur'               => $auteur,
            ':description_detailed' => $data['description_detailed'] ?? null,
            ':technologies'         => $data['technologies'] ?? null,
            ':image_url'            => $data['image_url'] ?? null,
            ':features'             => $data['features'] ?? null,
            ':challenges'           => $data['challenges'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE projects
            SET 
                title = :title,
                description = :description,
                auteur = :auteur,
                description_detailed = :description_detailed,
                technologies = :technologies,
                image_url = :image_url,
                features = :features,
                challenges = :challenges,
                updated_at = NOW()
            WHERE id = :id
        ";

       
        $auteur = empty($data['auteur']) ? 11 : (int)$data['auteur'];

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title'                => $data['title'],
            ':description'          => $data['description'],
            ':auteur'               => $auteur,
            ':description_detailed' => $data['description_detailed'] ?? null,
            ':technologies'         => $data['technologies'] ?? null,
            ':image_url'            => $data['image_url'] ?? null,
            ':features'             => $data['features'] ?? null,
            ':challenges'           => $data['challenges'] ?? null,
            ':id'                   => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
