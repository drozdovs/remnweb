<?php
namespace RemnWeb;

use PDO;

class DB
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $name = $_ENV['DB_NAME'] ?? 'remnweb';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';
            $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";
            self::$instance = new PDO($dsn, $user, $pass);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::init();
        }
        return self::$instance;
    }

    private static function init(): void
    {
        $db = self::$instance;
        $db->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            blocked TINYINT(1) DEFAULT 0,
            trial_used TINYINT(1) DEFAULT 0,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $db->exec('CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $db->exec('CREATE TABLE IF NOT EXISTS plans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            trial TINYINT(1) DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $db->exec('CREATE TABLE IF NOT EXISTS subscriptions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            plan_id INT NOT NULL,
            status VARCHAR(20) NOT NULL,
            start_date DATETIME,
            end_date DATETIME,
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(plan_id) REFERENCES plans(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $db->exec('CREATE TABLE IF NOT EXISTS login_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            code VARCHAR(10) NOT NULL,
            expires_at DATETIME NOT NULL,
            FOREIGN KEY(user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $adminUser = $_ENV['ADMIN_USER'] ?? 'admin';
        $adminPass = $_ENV['ADMIN_PASS'] ?? 'change_me';
        $stmt = $db->prepare('SELECT COUNT(*) FROM admin_users');
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $hash = password_hash($adminPass, PASSWORD_BCRYPT);
            $db->prepare('INSERT INTO admin_users (username, password_hash) VALUES (:u, :p)')
               ->execute([':u' => $adminUser, ':p' => $hash]);
        }

        $stmt = $db->prepare('SELECT COUNT(*) FROM plans');
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $db->exec("INSERT INTO plans (name, price, trial) VALUES ('basic', 5.00, 0), ('pro', 10.00, 0), ('trial', 0.00, 1)");
        }
    }
}
