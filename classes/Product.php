<?php

class Product {
    private $db;
    private $table = 'products';
    
    public $id;
    public $name;
    public $price;
    public $category;
    public $description;
    public $created_at;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll() {
        try {
            $query = 'SELECT * FROM ' . $this->table . ' ORDER BY id DESC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }
    
    public function getById($id) {
        try {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch(PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }
    
    public function create() {
        if (!$this->validate()) {
            return false;
        }
        
        try {
            $query = 'INSERT INTO ' . $this->table . '
                    (name, price, category, description)
                    VALUES
                    (:name, :price, :category, :description)';
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':description', $this->description);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }
    
    public function update() {
        if (!$this->validate()) {
            return false;
        }
        
        try {
            $query = 'UPDATE ' . $this->table . '
                    SET name = :name, price = :price, category = :category, description = :description
                    WHERE id = :id';
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':description', $this->description);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }
    
    public function delete($id) {
        try {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }
    
    public function getByCategory() {
        try {
            $query = 'SELECT category, COUNT(*) as count FROM ' . $this->table . ' GROUP BY category';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            throw new Exception('Database Error: ' . $e->getMessage());
        }
    }
    
    private function validate() {
        if (empty($this->name) || strlen($this->name) < 3) {
            throw new Exception('Product name must be at least 3 characters');
        }
        
        if (empty($this->price) || !is_numeric($this->price) || $this->price <= 0) {
            throw new Exception('Price must be a positive number');
        }
        
        if (empty($this->category) || strlen($this->category) < 2) {
            throw new Exception('Category must be at least 2 characters');
        }
        
        if (empty($this->description) || strlen($this->description) < 5) {
            throw new Exception('Description must be at least 5 characters');
        }
        
        return true;
    }
}
?>
