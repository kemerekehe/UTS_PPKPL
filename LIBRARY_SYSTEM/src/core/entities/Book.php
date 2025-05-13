<?php

class Book {
    public $id;
    public $title;
    public $author;
    public $published_date;
    public $quantity;

    public function __construct($title, $author, $published_date, $quantity, $id = null) {
        $this->title = $title;
        $this->author = $author;
        $this->published_date = $published_date;
        $this->quantity = $quantity;
        $this->id = $id;

        try {
        // Memanggil isValid untuk memeriksa validitas data
        $this->isValid();
    } catch (\Exception $e) {
        // Meneruskan pengecualian yang dilempar oleh isValid() tanpa mengubahnya
        throw $e;  // Tidak ada perubahan pada pengecualian yang dilemparkan
    }
    }

    // Getter for Title
    public function getTitle()
    {
        return $this->title;
    }

    // Setter for Title
    public function setTitle($title)
    {
        $this->title = $title;
    }

    // Getter for Author
    public function getAuthor()
    {
        return $this->author;
    }

    // Setter for Author
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    // Getter for Published Date
    public function getPublishedDate()
    {
        return $this->published_date;
    }

    // Setter for Published Date
    public function setPublishedDate($published_date)
    {
        $this->published_date = $published_date;
    }

    // Getter for Quantity
    public function getQuantity()
    {
        return $this->quantity;
    }

    // Setter for Quantity
    public function setQuantity($quantity)
    {
        if ($quantity < 0) {
            throw new \Exception('Quantity must be a positive integer.');
        }
        $this->quantity = $quantity;
    }

    // Getter for ID
    public function getId()
    {
        return $this->id;
    }

    // Setter for ID
    public function setId($id)
    {
        $this->id = $id;
    }

    public function isValid() {
        if (empty($this->title)) {
            throw new \Exception("Title cannot be empty");
        }
        if (empty($this->author)) {
            throw new \Exception("Author cannot be empty");
        }
        if ($this->quantity <= 0) {
            throw new \Exception("Quantity must be greater than 0");
        }
        if (empty($this->published_date)) {
            throw new \Exception("Published date cannot be empty");
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->published_date)) {
            throw new \Exception("Published date must be in YYYY-MM-DD format");
        }
        if (!$this->isValidDate($this->published_date)) {
            throw new \Exception("Invalid published date");
        }
        return true;
    }

    public function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    $currentDate = new DateTime();
    if ($d > $currentDate) {
        return false;
    }
    return $d && $d->format('Y-m-d') === $date; 
    }
}
?>