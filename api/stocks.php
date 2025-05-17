<?php
class StockAPI {
    private ?PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getStocks($search = '', $filter = '') {
        $query = "SELECT * FROM stocks WHERE name LIKE ?";
        $params = ["%$search%"];

        if ($filter !== '') {
            $query .= " AND quantity >= ?";
            $params[] = $filter;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStock($name, $quantity) {
        $stmt = $this->pdo->prepare("INSERT INTO stocks (name, quantity) VALUES (?, ?)");
        return $stmt->execute([$name, $quantity]);
    }

    public function updateStock($id, $quantity) {
        $stmt = $this->pdo->prepare("UPDATE stocks SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $id]);
    }

    public function deleteStock($id) {
        $stmt = $this->pdo->prepare("DELETE FROM stocks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>