<?php
namespace RemnWeb;

use PDO;

class Admin
{
    public static function login(string $username, string $password): bool
    {
        $db = DB::get();
        $stmt = $db->prepare('SELECT id, password_hash FROM admin_users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['password_hash'])) {
            $_SESSION['admin_id'] = $row['id'];
            return true;
        }
        return false;
    }

    public static function user(): ?array
    {
        if (isset($_SESSION['admin_id'])) {
            $db = DB::get();
            $stmt = $db->prepare('SELECT * FROM admin_users WHERE id = :id');
            $stmt->execute([':id' => $_SESSION['admin_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        return null;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_id']);
    }
}
