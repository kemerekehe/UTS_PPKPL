<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/core/usecases/AddBook.php';

class AddBookTest extends TestCase
{
    private $bookRepositoryMock;
    private $addBook;

    protected function setUp(): void
    {
        // Membuat mock untuk BookRepositoryImplement
        $this->bookRepositoryMock = $this->createMock(BookRepositoryImplement::class);
        $this->addBook = new AddBook($this->bookRepositoryMock);
    }

    public function testExecuteAddsBook()
    {
        // Membuat objek buku yang valid
        $book = new Book('Title', 'Author', '2022-01-01', 5);

        // Mengatur ekspektasi bahwa save() dipanggil sekali dengan data buku yang tepat
        $this->bookRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->equalTo($book))
            ->willReturn(true);

        // Memanggil metode execute dan memastikan tidak ada pengecualian yang dilempar
        $this->addBook->execute($book);
    }

    public function testExecuteThrowsExceptionOnError()
    {
        // Membuat objek buku yang valid
        $book = new Book('Title', 'Author', '2022-01-01', 5);

        // Mengatur ekspektasi bahwa save() akan melempar pengecualian
        $this->bookRepositoryMock->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \Exception('Database error')));

        // Memastikan pengecualian dilemparkan ketika terjadi kesalahan
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error adding book: Database error");

        // Memanggil metode execute yang akan menyebabkan pengecualian
        $this->addBook->execute($book);
    }
}


?>