<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use RemnWeb\Auth;
use RemnWeb\Billing;
use RemnWeb\DB;

$user = Auth::user();

if (!$user) {
    echo "Not logged in";
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>RemnVPN Billing</title>
</head>
<body>
<h1>Welcome, <?php echo htmlspecialchars($user['email']); ?></h1>
<p><a href="logout.php">Logout</a></p>
<?php
    $plans = DB::get()->query('SELECT name FROM plans')->fetchAll(PDO::FETCH_COLUMN);
?>
<form action="subscribe.php" method="post">
    <label>Select Plan:
        <select name="plan">
            <?php foreach ($plans as $p): ?>
            <option value="<?php echo htmlspecialchars($p); ?>"><?php echo htmlspecialchars($p); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Subscribe</button>
</form>
</body>
</html>
