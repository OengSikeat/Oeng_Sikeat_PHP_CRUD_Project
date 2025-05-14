<?php
class StaffAPI {
    private ?PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getStaff($search = '') {
        $stmt = $this->pdo->prepare("SELECT * FROM staff WHERE name ILIKE ?");
        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStaff($name, $role, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO staff (name, role, email) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $role, $email]);
    }

    public function updateStaff($id, $name, $role, $email) {
        $stmt = $this->pdo->prepare("UPDATE staff SET name = ?, role = ?, email = ? WHERE id = ?");
        return $stmt->execute([$name, $role, $email, $id]);
    }

    public function deleteStaff($id) {
        $stmt = $this->pdo->prepare("DELETE FROM staff WHERE id = ?");
        return $stmt->execute([$id]);
    }
}