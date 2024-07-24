<?php

class Book extends Product {
    private $weight;

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price, 'Book');
        $this->weight = $weight;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function save($db) {
        $query = "INSERT INTO products (sku, name, price, type, weight) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $this->sku);
        $stmt->bindParam(2, $this->name);
        $stmt->bindParam(3, $this->price);
        $stmt->bindParam(4, $this->type);
        $stmt->bindParam(5, $this->weight);
        $stmt->execute();
    }

    public function getSpecificAttribute() {
        return "Weight: {$this->weight} Kg";
    }
}

?>
