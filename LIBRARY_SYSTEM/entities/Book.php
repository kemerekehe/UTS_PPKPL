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
    }

    public function isValid() {
    // Memastikan bahwa judul, penulis, dan quantity valid, dan tanggal terbit tidak kosong serta memiliki format yang valid
    return !empty($this->title) && 
           !empty($this->author) && 
           $this->quantity > 0 && 
           $this->isValidDate($this->published_date);
}

    public function isValidDate($date) {
    // Memastikan tanggal terbit memiliki format yang valid (YYYY-MM-DD)
    $d = DateTime::createFromFormat('Y-m-d', $date);
    $currentDate = new DateTime();
    if ($d > $currentDate) {
        echo "Error: The date '{$date}' is in the future\n";
        return false;
    }
    return $d && $d->format('Y-m-d') === $date; 
    }
}
?>