<?php
class PurchaseAPI {
    private ?PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getPurchases($startDate = '', $endDate = '') {
        $query = "SELECT p.*, s.name as stock_name FROM purchases p JOIN stocks s ON p.stock_id = s.id";
        $params = [];

        if ($startDate && $endDate) {
            $query .= " WHERE p.purchase_date BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPurchase($stock_id, $quantity, $price) {
        $this->pdo->beginTransaction();
        try {
            // Add purchase record
            $stmt = $this->pdo->prepare(
                "INSERT INTO purchases (stock_id, quantity, price, purchase_date) VALUES (?, ?, ?, NOW())"
            );
            $stmt->execute([$stock_id, $quantity, $price]);

            // Update stock quantity
            $stmt = $this->pdo->prepare("UPDATE stocks SET quantity = quantity + ? WHERE id = ?");
            $stmt->execute([$quantity, $stock_id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            error_log("Purchase failed: " . $e->getMessage());
            return false;
        }
    }
}
?>