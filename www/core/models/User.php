<?php

declare(strict_types=1);

namespace Core\Models;

use Core\Utils\Database;
use PDO;
use Exception;

class User
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public array $roles = [];

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = (int)($data['id'] ?? 0);
            $this->first_name = $data['first_name'] ?? '';
            $this->last_name = $data['last_name'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? '';
        }
    }

    /**
     * Récupère tous les utilisateurs avec leurs rôles.
     */
    public function getAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->query("
        SELECT u.id, u.first_name, u.last_name, u.email,
            STRING_AGG(r.name, ', ') AS roles
        FROM users u
        LEFT JOIN user_roles ur ON u.id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.id
        GROUP BY u.id
        ORDER BY u.id DESC
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Trouve un utilisateur par son ID.
     */
    public function findById(int $id): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, first_name, last_name, email FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Charger les rôles
            $this->id = (int)$user['id'];
            $this->first_name = $user['first_name'];
            $this->last_name = $user['last_name'];
            $this->email = $user['email'];
            $this->getRoles();
            $user['roles'] = $this->roles;

            return $user;
        }

        return null;
    }

    /**
     * Trouve un utilisateur par son email.
     */
    public function findByEmail(string $email): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, first_name, last_name, email, password FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Charger les rôles
            $this->id = (int)$user['id'];
            $this->first_name = $user['first_name'];
            $this->last_name = $user['last_name'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->getRoles();
            $user['roles'] = $this->roles;

            return $user;
        }

        return null;
    }

    /**
     * Crée un nouvel utilisateur.
     */
    public function create(array $data): ?int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
        $success = $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if ($success) {
            return (int)$db->lastInsertId();
        }

        return null;
    }

    /**
     * Met à jour un utilisateur existant.
     */
    public function update(int $id, array $data): bool
    {
        $db = Database::getInstance();

        $fields = [];
        $params = ['id' => $id];

        if (isset($data['first_name'])) {
            $fields[] = 'first_name = :first_name';
            $params['first_name'] = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $fields[] = 'last_name = :last_name';
            $params['last_name'] = $data['last_name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $params['password'] = $data['password'];
        }

        if (empty($fields)) {
            throw new Exception("Aucune donnée à mettre à jour.");
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprime un utilisateur.
     */
    public function delete(int $id): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Récupère les rôles de l'utilisateur.
     */
    public function getRoles(): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
        SELECT r.name 
        FROM roles r
        JOIN user_roles ur ON r.id = ur.role_id
        WHERE ur.user_id = :user_id
    ");
        $stmt->execute(['user_id' => $this->id]);
        $this->roles = $stmt->fetchAll(\PDO::FETCH_COLUMN); // Renvoie un tableau unidimensionnel
    }


    /**
     * Assigne des rôles à l'utilisateur.
     *
     * @param array $roleIds
     * @return bool
     */
    public function assignRoles(array $roleIds): bool
    {
        $roleModel = new Role();
        return $roleModel->assignRoles($this->id, $roleIds);
    }

    /**
     * Met à jour l'utilisateur et ses rôles.
     *
     * @param array $data
     * @param array $roleIds
     * @return bool
     */
    public function updateWithRoles(array $data, array $roleIds): bool
    {
        $updated = $this->update($this->id, $data);
        if ($updated) {
            return $this->assignRoles($roleIds);
        }
        return false;
    }

    /**
     * Crée un nouvel utilisateur et lui assigne des rôles.
     *
     * @param array $data
     * @param array $roleIds
     * @return int|null
     */
    public function createWithRoles(array $data, array $roleIds): ?int
    {
        $userId = $this->create($data);
        if ($userId) {
            $this->id = $userId;
            $this->assignRoles($roleIds);
            return $userId;
        }
        return null;
    }
}
