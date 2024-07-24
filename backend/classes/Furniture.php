<?php

class Furniture extends Product {
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price, 'Furniture');
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getLength() {
        return $this->length;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function save($db) {
        $query = "INSERT INTO products (sku, name, price, type, height, width, length) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $this->sku);
        $stmt->bindParam(2, $this->name);
        $stmt->bindParam(3, $this->price);
        $stmt->bindParam(4, $this->type);
        $stmt->bindParam(5, $this->height);
        $stmt->bindParam(6, $this->width);
        $stmt->bindParam(7, $this->length);
        $stmt->execute();
    }

    public function getSpecificAttribute() {
        return "Dimensions: {$this->height} x {$this->width} x {$this->length}";
    }
}

?>
