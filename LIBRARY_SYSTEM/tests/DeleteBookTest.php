<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/core/usecases/DeleteBook.php';

class DeleteBookTest extends TestCase
{
    private $bookRepositoryMock;
    private $deleteBook;

    protected function setUp(): void
    {
        // Membuat mock untuk BookRepositoryImplement
        $this->bookRepositoryMock = $this->createMock(BookRepositoryImplement::class);
        $this->deleteBook = new DeleteBook($this->bookRepositoryMock);
    }

    public function testExecuteDeletesBook()
    {
        // ID buku yang akan dihapus
        $bookId = 1;

        // Mengatur ekspektasi bahwa delete() dipanggil sekali dengan ID buku yang tepat
        $this->bookRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($bookId))
            ->willReturn(true);

        // Memanggil metode execute dan memastikan tidak ada pengecualian yang dilempar
        $this->deleteBook->execute($bookId);
    }

    public function testExecuteThrowsExceptionOnError()
    {
        // ID buku yang akan dihapus
        $bookId = 1;

        // Mengatur ekspektasi bahwa delete() akan melempar pengecualian
        $this->bookRepositoryMock->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \Exception('Database error')));

        // Memastikan pengecualian dilemparkan ketika terjadi kesalahan
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error deleting book: Database error");

        // Memanggil metode execute yang akan menyebabkan pengecualian
        $this->deleteBook->execute($bookId);
    }
}

?>