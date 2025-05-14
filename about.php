<?php
// File: about.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
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
            <a href="about.php" class="block py-2.5 px-4 rounded bg-gray-700"><i class="fas fa-info-circle mr-2"></i>About</a>
            <a href="logout.php" class="block py-2.5 px-4 rounded hover:bg-red-600"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8 w-full">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow">
            <h1 class="text-3xl font-bold mb-6">About Stock Manager</h1>
            <div class="space-y-4 text-gray-700">
                <p>This Stock Management System is a comprehensive solution built with PHP, PostgreSQL, and Tailwind CSS.</p>
                <p>Key features include:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Stock inventory management with CRUD operations</li>
                    <li>Staff management with role assignments</li>
                    <li>Purchase tracking and inventory updates</li>
                    <li>User authentication and authorization</li>
                    <li>Modern, responsive UI with Tailwind CSS</li>
                    <li>API-based architecture for maintainability</li>
                </ul>
                <p>Developed by: Oeng Sikeat(E7)</p>
                <img src="img/Profile.jpg" class="w-[120px] h-[160px] rounded-xl">
            </div>
        </div>
    </div>
</div>
</body>
</html>