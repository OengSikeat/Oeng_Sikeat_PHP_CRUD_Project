<?php
session_start();
require_once 'config/database.php';
require_once 'api/stocks.php';
require_once 'api/staff.php';
require_once 'api/purchases.php';

if (!isset($_SESSION['user_id']) && !in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'signup.php', 'about.php'])) {
    header('Location: login.php');
    exit();
}

$stockAPI = new StockAPI();
$staffAPI = new StaffAPI();
$purchaseAPI = new PurchaseAPI();

// Handle stock actions
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['stock_action'])) {
    try {
        switch ($_POST['stock_action']) {
            case 'add':
                $stockAPI->addStock($_POST['name'], $_POST['quantity']);
                break;
            case 'update':
                $stockAPI->updateStock($_POST['id'], $_POST['quantity']);
                break;
            case 'delete':
                $stockAPI->deleteStock($_POST['id']);
                break;
        }
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $error = "Stock operation failed: " . $e->getMessage();
    }
}

// Handle staff actions
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['staff_action'])) {
    try {
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

// Handle purchase actions
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['purchase_action'])) {
    try {
        if ($purchaseAPI->addPurchase($_POST['stock_id'], $_POST['quantity'], $_POST['price'])) {
            header("Location: purchases.php");
            exit();
        } else {
            $error = "Purchase failed";
        }
    } catch (Exception $e) {
        $error = "Purchase operation failed: " . $e->getMessage();
    }
}

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';
$stocks = $stockAPI->getStocks($search, $filter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management System</title>
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
            <a href="staff.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-users mr-2"></i>Staff</a>
            <a href="purchases.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-shopping-cart mr-2"></i>Purchases</a>
            <a href="about.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-info-circle mr-2"></i>About</a>
            <a href="logout.php" class="block py-2.5 px-4 rounded hover:bg-red-600"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8 w-full">
        <div class="max-w-6xl mx-auto">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <h1 class="text-3xl font-bold mb-6">Stock Management</h1>

            <!-- Stock Filter -->
            <form method="GET" class="flex gap-4 mb-6">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                       placeholder="Search by name"
                       class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="number" name="filter" value="<?php echo htmlspecialchars($filter); ?>"
                       placeholder="Min quantity"
                       class="w-32 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </form>

            <!-- Stock Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php foreach ($stocks as $stock): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $stock['id']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($stock['name']); ?></td>
                            <td class="px-6 py-4"><?php echo $stock['quantity']; ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" class="inline-flex gap-2">
                                    <input type="hidden" name="id" value="<?php echo $stock['id']; ?>">
                                    <input type="number" name="quantity"
                                           placeholder="New Qty"
                                           class="w-24 p-1 border rounded-lg"
                                           required>
                                    <input type="hidden" name="stock_action" value="update">
                                    <button type="submit"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $stock['id']; ?>">
                                    <input type="hidden" name="stock_action" value="delete">
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

            <!-- Add Stock Form -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Add New Stock</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="stock_action" value="add">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="quantity"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add Stock
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>