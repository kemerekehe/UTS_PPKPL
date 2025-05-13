<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/core/entities/Book.php';

class BookEntityTest extends TestCase
{
    public function testValidBook()
    {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertTrue($book->isValid());
    }

    public function testInvalidBookWithEmptyTitle()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Title cannot be empty");
        $book = new Book('', 'Andrea Hirata', '2005-06-01', 5);  // Judul kosong
    }

    public function testInvalidBookWithEmptyAuthor()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Author cannot be empty");
        $book = new Book('Laskar Pelangi', '', '2005-06-01', 5);  // Penulis kosong
    }

    public function testInvalidBookWithZeroQuantity()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Quantity must be greater than 0");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 0);  // Quantity = 0
    }

    public function testInvalidBookWithNegativeQuantity()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Quantity must be greater than 0");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', -5);  // Quantity negatif
    }

    public function testInvalidDateInTheFuture()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid published date");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2030-12-31', 5);  // Tanggal masa depan
    }

    public function testInvalidDateWithSlash()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Published date must be in YYYY-MM-DD format");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '05-2021-01', 5);  // Format tanggal salah
    }

    public function testInvalidDateWithWrongFormat()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Published date must be in YYYY-MM-DD format");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2021/01/01', 5);  // Format tanggal salah
    }

    public function testInvalidDateWithNonDateString()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Published date must be in YYYY-MM-DD format");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', 'not-a-date', 5);  // String bukan tanggal
    }

    public function testInvalidDateWithEmptyDate()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Published date cannot be empty");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '', 5);  // Tanggal kosong
    }

    public function testInvalidDateWithNonexistentDate()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid published date");
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2021-02-30', 5);  // Tanggal 30 Februari
    }

    // Test untuk getter dan setter

    public function testSetAndGetTitle()
    {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertEquals('Laskar Pelangi', $book->getTitle());

        $book->setTitle('Pulang');
        $this->assertEquals('Pulang', $book->getTitle());
    }

    public function testSetAndGetAuthor()
    {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertEquals('Andrea Hirata', $book->getAuthor());

        $book->setAuthor('Leila Chudori');
        $this->assertEquals('Leila Chudori', $book->getAuthor());
    }

    public function testSetAndGetQuantity()
    {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertEquals(5, $book->getQuantity());

        $book->setQuantity(10);
        $this->assertEquals(10, $book->getQuantity());

        $this->expectException(\Exception::class);
        $book->setQuantity(-1);
    }

    public function testSetAndGetPublishedDate()
    {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5);
        $this->assertEquals('2005-06-01', $book->getPublishedDate());

        $book->setPublishedDate('2022-01-01');
        $this->assertEquals('2022-01-01', $book->getPublishedDate());
    }

    // Test untuk panjang karakter judul dan penulis
    public function testSetAndGetLongTitle()
    {
        $longTitle = str_repeat('A', 255); // Judul yang sangat panjang (misalnya 255 karakter)
        $book = new Book($longTitle, 'Andrea Hirata', '2005-06-01', 5);
        $this->assertEquals($longTitle, $book->getTitle());

        // Mengubah judul
        $book->setTitle('New Title');
        $this->assertEquals('New Title', $book->getTitle());
    }

    public function testSetAndGetLongAuthor()
    {
        $longAuthor = str_repeat('A', 255); // Penulis yang sangat panjang (misalnya 255 karakter)
        $book = new Book('Laskar Pelangi', $longAuthor, '2005-06-01', 5);
        $this->assertEquals($longAuthor, $book->getAuthor());

        // Mengubah penulis
        $book->setAuthor('New Author');
        $this->assertEquals('New Author', $book->getAuthor());
    }

    public function testSetAndGetCurrentDateAsPublishedDate()
    {
        $currentDate = date('Y-m-d');
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', $currentDate, 5);
        $this->assertEquals($currentDate, $book->getPublishedDate());

        // Mengubah tanggal terbit
        $newDate = '2022-01-01';
        $book->setPublishedDate($newDate);
        $this->assertEquals($newDate, $book->getPublishedDate());
    }

    public function testSetAndGetId()
    {
        $book = new Book('Laskar Pelangi', 'Andrea Hirata', '2005-06-01', 5, 1);
        $this->assertEquals(1, $book->getId());

        $book->setId(10);
        $this->assertEquals(10, $book->getId());
    }

    // Test untuk validasi tambahan
    public function testValidBookWithLongTitle()
    {
        $longTitle = str_repeat('A', 300); // Judul yang sangat panjang (misalnya 300 karakter)
        $book = new Book($longTitle, 'Andrea Hirata', '2005-06-01', 5);
        $this->assertTrue($book->isValid()); // Validasi buku harus tetap benar meski judul panjang
    }
}
?>
