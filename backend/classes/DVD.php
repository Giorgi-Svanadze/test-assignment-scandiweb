<?php

class DVD extends Product {
    private $size;

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price, 'DVD');
        $this->size = $size;
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function save($db) {
        $query = "INSERT INTO products (sku, name, price, type, size) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $this->sku);
        $stmt->bindParam(2, $this->name);
        $stmt->bindParam(3, $this->price);
        $stmt->bindParam(4, $this->type);
        $stmt->bindParam(5, $this->size);
        $stmt->execute();
    }

    public function getSpecificAttribute() {
        return "Size: {$this->size} MB";
    }
}

?>
