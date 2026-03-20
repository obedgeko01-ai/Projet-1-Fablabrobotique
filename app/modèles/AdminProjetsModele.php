<?php
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
        $sql = "SELECT 
                    id, titre, description,
                    CASE WHEN auteur = 11 THEN 'Fablabteam' ELSE auteur END AS auteur,
                    description_detaillee, technologies, image_url,
                    fonctionnalites, defis, cree_le, modifie_le
                FROM projets
                ORDER BY cree_le DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT 
                    id, titre, description,
                    CASE WHEN auteur = 11 THEN 'Fablabteam' ELSE auteur END AS auteur,
                    description_detaillee, technologies, image_url,
                    fonctionnalites, defis, cree_le, modifie_le
                FROM projets
                WHERE id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO projets
                (titre, description, auteur, description_detaillee, technologies, image_url, fonctionnalites, defis, cree_le, modifie_le)
                VALUES
                (:titre, :description, :auteur, :description_detaillee, :technologies, :image_url, :fonctionnalites, :defis, NOW(), NOW())";

        $auteur = empty($data['auteur']) ? 11 : (int)$data['auteur'];

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titre'               => $data['titre'],
            ':description'         => $data['description'],
            ':auteur'              => $auteur,
            ':description_detaillee' => $data['description_detaillee'] ?? null,
            ':technologies'        => $data['technologies'] ?? null,
            ':image_url'           => $data['image_url'] ?? null,
            ':fonctionnalites'     => $data['fonctionnalites'] ?? null,
            ':defis'               => $data['defis'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE projets
                SET titre=:titre, description=:description, auteur=:auteur,
                    description_detaillee=:description_detaillee, technologies=:technologies,
                    image_url=:image_url, fonctionnalites=:fonctionnalites, defis=:defis,
                    modifie_le=NOW()
                WHERE id=:id";

        $auteur = empty($data['auteur']) ? 11 : (int)$data['auteur'];

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'               => $data['titre'],
            ':description'         => $data['description'],
            ':auteur'              => $auteur,
            ':description_detaillee' => $data['description_detaillee'] ?? null,
            ':technologies'        => $data['technologies'] ?? null,
            ':image_url'           => $data['image_url'] ?? null,
            ':fonctionnalites'     => $data['fonctionnalites'] ?? null,
            ':defis'               => $data['defis'] ?? null,
            ':id'                  => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM projets WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}