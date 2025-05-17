<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } catch (Exception $e) {
        $error = "Login failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login to Stock Manager</h2>
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username"
                   placeholder="Enter username"
                   class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password"
                   placeholder="Enter password"
                   class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        <button type="submit"
                class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-sign-in-alt mr-2"></i>Login
        </button>
    </form>
    <p class="mt-4 text-center text-sm">
        Don't have an account?
        <a href="signup.php" class="text-blue-600 hover:underline">Sign up</a>
    </p>
</div>
</body>
</html>
?>