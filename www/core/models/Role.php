<?php
declare(strict_types=1);

namespace Core\Models;

use Core\Utils\Database;
use PDO;

class Role
{
    public int $id;
    public string $name;

    /**
     * Récupère tous les rôles.
     *
     * @return array
     */
    public function getAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT id, name FROM roles ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouve un rôle par son nom.
     *
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, name FROM roles WHERE name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ?: null;
    }

    /**
     * Associe des rôles à un utilisateur.
     *
     * @param int $userId
     * @param array $roleIds
     * @return bool
     */
    public function assignRoles(int $userId, array $roleIds): bool
    {
        $db = Database::getInstance();

        // Supprimer les rôles existants
        $stmt = $db->prepare("DELETE FROM user_roles WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);

        // Insérer les nouveaux rôles
        $stmt = $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
        foreach ($roleIds as $roleId) {
            $stmt->execute([
                'user_id' => $userId,
                'role_id' => $roleId
            ]);
        }

        return true;
    }

    /**
     * Récupère les rôles assignés à un utilisateur.
     *
     * @param int $userId
     * @return array
     */
    public function getUserRoles(int $userId): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT r.id, r.name
            FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
