<?php
require_once __DIR__ . '/../config/database.php';

class ProfilModele
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDatabase();
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updatePhoto(int $id, string $photo): void
    {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET photo = ? WHERE id = ?");
        $stmt->execute([$photo, $id]);
    }
}