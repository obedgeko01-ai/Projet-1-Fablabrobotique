<?php

class AuthModele
{
    private PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createUser(string $nom, string $email, string $mot_de_passe, string $role = 'Utilisateur'): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $email, $mot_de_passe, $role]);
    }

    public function verifierConnexion(string $email, string $mot_de_passe): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            return $utilisateur;
        }
        return false;
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}