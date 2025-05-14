<?php
// File: purchases.php
session_start();
require_once 'config/database.php';
require_once 'api/purchases.php';
require_once 'api/stocks.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$purchaseAPI = new PurchaseAPI();
$stockAPI = new StockAPI();
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$purchases = $purchaseAPI->getPurchases($startDate, $endDate);
$stocks = $stockAPI->getStocks();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['purchase_action'])) {
    try {
        $purchaseAPI = new PurchaseAPI();
        switch ($_POST['purchase_action']) {
            case 'add':
                $purchaseAPI->addPurchase($_POST['stock_id'], $_POST['quantity'], $_POST['price']);
                break;
            case 'update':
                $purchaseAPI->updatePurchase($_POST['id'], $_POST['stock_id'], $_POST['quantity'], $_POST['price']);
                break;
            case 'delete':
                $purchaseAPI->deletePurchase($_POST['id']);
                break;
        }
        header("Location: purchases.php");
        exit();
    } catch (Exception $e) {
        $error = "Purchase operation failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Management</title>
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
            <a href="purchases.php" class="block py-2.5 px-4 rounded bg-gray-700"><i class="fas fa-shopping-cart mr-2"></i>Purchases</a>
            <a href="about.php" class="block py-2.5 px-4 rounded hover:bg-gray-700"><i class="fas fa-info-circle mr-2"></i>About</a>
            <a href="logout.php" class="block py-2.5 px-4 rounded hover:bg-red-600"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8 w-full">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Purchase Management</h1>

            <!-- Purchase Filter -->
            <form method="GET" class="flex gap-4 mb-6">
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>"
                       class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>"
                       class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </form>

            <!-- Purchase Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php foreach ($purchases as $purchase): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $purchase['id']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($purchase['stock_name']); ?></td>
                            <td class="px-6 py-4"><?php echo $purchase['quantity']; ?></td>
                            <td class="px-6 py-4">$<?php echo number_format($purchase['price'], 2); ?></td>
                            <td class="px-6 py-4"><?php echo $purchase['purchase_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Purchase Form -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Add New Purchase</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="purchase_action" value="add">
                    <div>
                        <label for="stock_id" class="block text-sm font-medium text-gray-700">Stock</label>
                        <select name="stock_id" id="stock_id"
                                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <?php foreach ($stocks as $stock): ?>
                                <option value="<?php echo $stock['id']; ?>">
                                    <?php echo htmlspecialchars($stock['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="quantity"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" step="0.01"
                               class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add Purchase
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>