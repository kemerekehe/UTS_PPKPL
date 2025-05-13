<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/core/usecases/UpdateBook.php';

class UpdateBookTest extends TestCase
{
    private $bookRepositoryMock;
    private $updateBook;

    protected function setUp(): void
    {
        // Membuat mock untuk BookRepositoryImplement
        $this->bookRepositoryMock = $this->createMock(BookRepositoryImplement::class);
        $this->updateBook = new UpdateBook($this->bookRepositoryMock);
    }

    public function testExecuteUpdatesBook()
    {
        // Membuat objek buku yang valid
        $book = new Book('Updated Title', 'Updated Author', '2023-01-01', 10);
        $book->setId(1);

        // Mengatur ekspektasi bahwa update() dipanggil sekali dengan data buku yang tepat
        $this->bookRepositoryMock->expects($this->once())
            ->method('update')
            ->with($this->equalTo($book))
            ->willReturn(true);

        // Memanggil metode execute dan memastikan tidak ada pengecualian yang dilempar
        $this->updateBook->execute($book);
    }

    public function testExecuteThrowsExceptionOnError()
    {
        // Membuat objek buku yang valid
        $book = new Book('Updated Title', 'Updated Author', '2023-01-01', 10);
        $book->setId(1);

        // Mengatur ekspektasi bahwa update() akan melempar pengecualian
        $this->bookRepositoryMock->expects($this->once())
            ->method('update')
            ->will($this->throwException(new \Exception('Database error')));

        // Memastikan pengecualian dilemparkan ketika terjadi kesalahan
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error updating book: Database error");

        // Memanggil metode execute yang akan menyebabkan pengecualian
        $this->updateBook->execute($book);
    }
    
}


?>