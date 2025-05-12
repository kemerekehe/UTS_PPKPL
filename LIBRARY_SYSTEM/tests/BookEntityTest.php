<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../entities/Book.php';  // Pastikan path relatifnya benar

class BookEntityTest extends TestCase {
    
    // Menguji buku yang valid
    public function testValidBook() {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertTrue($book->isValid());  // Buku valid
    }

    // Menguji buku dengan judul kosong
    public function testInvalidBookWithEmptyTitle() {
        $book = new Book('', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertFalse($book->isValid());
    }

    // Menguji buku dengan quantity 0
    public function testInvalidBookWithZeroQuantity() {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 0);
        $this->assertFalse($book->isValid());
    }

    public function testInvalidDateInTheFuture() {
    // Tanggal masa depan
    $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2030-12-31', 5);
    $this->assertFalse($book->isValidDate($book->published_date));
}

    // Menguji isValidDate dengan format tanggal salah
    public function testInvalidDateWithSlash() {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '05-2021-01', 5);
        $this->assertFalse($book->isValidDate($book->published_date));
    }

    // Menguji isValidDate dengan tanggal yang tidak ada (30 Februari)
    public function testInvalidDateWithNonexistentDate() {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2021-02-30', 5);
        $this->assertFalse($book->isValidDate($book->published_date));  // Tanggal tidak ada
    }
}
?>
