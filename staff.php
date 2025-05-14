<?php
// File: staff.php
session_start();
require_once 'config/database.php';
require_once 'api/staff.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$staffAPI = new StaffAPI();
$search = $_GET['search'] ?? '';
$staff = $staffAPI->getStaff($search);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['staff_action'])) {
    try {
        $staffAPI = new StaffAPI();
        switch ($_POST['staff_action']) {
            case 'add':
                $staffAPI->addStaff($_POST['name'], $_POST['role'], $_POST['email']);
                break;
            case 'update':
                $staffAPI->updateStaff($_POST['id'], $_POST['name'], $_POST['role'], $_POST['email']);
                break;
            case 'delete':
                $staffAPI->deleteStaff($_POST['id']);
                break;
        }
        header("Location: staff.php");
        exit();
    } catch (Exception $e) {
        $error = "Staff operation failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 fixed h-full">
        <div class="text-2xl font-bold text-center">Stock Manager</div>
        <nav>
            <a href="index.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-home mr-2"></i>Stocks</a>
            <a href="staff.php" class="block py-2.5 px-4 rounded bg-gray-700"><i class="fas fa-users mr-2"></i>Staff</a>
            <a href="purchases.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-shopping-cart mr-2"></i>Purchases</a>
            <a href="about.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-info-circle mr-2"></i>About</a>
            <a href="logout.php" class="block py-2.5 px-4 rounded hover:bg-red-600"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8 w-full">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Staff Management</h1>

            <!-- Staff Filter -->
            <form method="GET" class="mb-6">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search by name"
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </form>

            <!-- Staff Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php foreach ($staff as $member): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $member['id']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($member['name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($member['role']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($member['email']); ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" class="inline-flex gap-2">
                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                    <input type="text" name="name"
                                           value="<?php echo htmlspecialchars($member['name']); ?>"
                                           class="w-32 p-1 border rounded-lg"
                                           required>
                                    <input type="text" name="role"
                                           value="<?php echo htmlspecialchars($member['role']); ?>"
                                           class="w-32 p-1 border rounded-lg"
                                           required>
                                    <input type="email" name="email"
                                           value="<?php echo htmlspecialchars($member['email']); ?>"
                                           class="w-32 p-1 border rounded-lg"
                                           required>
                                    <input type="hidden" name="staff_action" value="update">
                                    <button type="submit"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                    <input type="hidden" name="staff_action" value="delete">
                                    <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 ml-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Staff Form -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Add New Staff</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="staff_action" value="add">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <input type="text" name="role" id="role"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add Staff
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>