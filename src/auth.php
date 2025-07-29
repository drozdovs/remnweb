<?php
namespace RemnWeb;

use PDO;

class Auth
{
    public static function sendCode(string $email): bool
    {
        $db = DB::get();
        $stmt = $db->prepare('SELECT id, blocked FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $insert = $db->prepare('INSERT INTO users (email, created_at) VALUES (:e, :c)');
            $insert->execute([':e' => $email, ':c' => date('c')]);
            $userId = (int)$db->lastInsertId();
        } else {
            $userId = (int)$user['id'];
            if ((int)$user['blocked'] === 1) {
                return false;
            }
        }

        $code = random_int(100000, 999999);
        $expires = date('c', time() + 300);
        $db->prepare('INSERT INTO login_codes (user_id, code, expires_at) VALUES (:u, :c, :e)')
            ->execute([':u' => $userId, ':c' => $code, ':e' => $expires]);

        $mailer = new Mailer();
        return $mailer->send($email, 'Your login code', "Your code: $code");
    }

    public static function verifyCode(string $email, string $code): bool
    {
        $db = DB::get();
        $stmt = $db->prepare('SELECT id FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false;
        }
        if ((int)$user['blocked'] === 1) {
            return false;
        }
        $userId = (int)$user['id'];
        $stmt = $db->prepare('SELECT * FROM login_codes WHERE user_id = :u AND code = :c ORDER BY id DESC LIMIT 1');
        $stmt->execute([':u' => $userId, ':c' => $code]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && strtotime($row['expires_at']) >= time()) {
            $db->prepare('DELETE FROM login_codes WHERE id = :id')->execute([':id' => $row['id']]);
            $_SESSION['user_id'] = $userId;
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (isset($_SESSION['user_id'])) {
            $db = DB::get();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute([':id' => $_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        return null;
    }
}
