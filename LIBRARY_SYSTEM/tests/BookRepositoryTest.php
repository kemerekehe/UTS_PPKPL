<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/infrastructure/repositories/BookRepositoryImplement.php';
require_once __DIR__ . '/../src/core/repositories/BookRepositoryInterface.php';
require_once __DIR__ . '/../src/core/entities/Book.php';

class BookRepositoryTest extends TestCase
{
    private $bookRepository;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
    }

    public function testSaveValidBook()
    {
        $book = new Book('Title', 'Author', '2022-01-01', 5);

        $this->bookRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($book));

        $this->bookRepository->save($book);
    }

    public function testSaveBookSQLException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("SQL error");
        $book = new Book('Title', 'Author', '2022-01-01', 5);
        $this->bookRepository->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \Exception("SQL error")));
        $this->bookRepository->save($book);
    }

    public function testSaveBookSQLFailure()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $pdo->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willThrowException(new PDOException("Database error"));
        $book = new Book('Title', 'Author', '2022-01-01', 5);
        $bookRepository = new BookRepositoryImplement($pdo);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error saving book: Database error");  // Memastikan pesan exception sesuai
        $bookRepository->save($book);
    }

    public function testFindBookById()
    {
        $bookId = 1;
        $book = new Book('Title', 'Author', '2022-01-01', 5);
        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($bookId))
            ->willReturn($book);
        $result = $this->bookRepository->findById($bookId);
        $this->assertNotNull($result);
        $this->assertEquals('Title', $result->getTitle());
    }

    public function testDeleteBook()
    {
        $bookId = 1;
        $this->bookRepository->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($bookId));
        $this->bookRepository->delete($bookId);
    }

    public function testDeleteBookNotFound()
    {
        $bookId = 9999;
        $this->bookRepository->expects($this->once())
            ->method('delete')
            ->with($this->equalTo($bookId));
        $this->bookRepository->delete($bookId);
    }

    public function testDeleteBookSQLException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("SQL error");
        $bookId = 1;
        $this->bookRepository->expects($this->once())
            ->method('delete')
            ->will($this->throwException(new \Exception("SQL error")));
        $this->bookRepository->delete($bookId);
    }

    public function testFindBookByIdNotFound()
    {
        $bookId = 9999;

        // Mengubah perilaku mock untuk tes ini
        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($bookId))
            ->willReturn(null);  // Tidak ditemukan

        $result = $this->bookRepository->findById($bookId);
        $this->assertNull($result);
    }

    public function testFindBookByIdInvalid()
    {
        $bookId = 'invalid_id';

        // Mengubah perilaku mock untuk tes ini
        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($bookId))
            ->willReturn(null);  // Tidak ditemukan

        $result = $this->bookRepository->findById($bookId);
        $this->assertNull($result);
    }

    public function testFindBookByIdEmpty()
    {
        $bookId = '';

        // Mengubah perilaku mock untuk tes ini
        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($bookId))
            ->willReturn(null);  // Tidak ditemukan

        $result = $this->bookRepository->findById($bookId);
        $this->assertNull($result);
    }

    public function testUpdateBook()
    {
        $book = new Book('Title', 'Author', '2022-01-01', 5, 1);

        $this->bookRepository->expects($this->once())
            ->method('update')
            ->with($this->equalTo($book));

        $this->bookRepository->update($book);
    }

    public function testUpdateBookNotFound()
    {
        $book = new Book('Title', 'Author', '2022-01-01', 5, 9999);

        // Mengubah perilaku mock untuk tes ini
        $this->bookRepository->expects($this->once())
            ->method('update')
            ->with($this->equalTo($book));

        $this->bookRepository->update($book);
    }
    public function testUpdateBookSQLException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("SQL error");
        $book = new Book('Title', 'Author', '2022-01-01', 5, 1);
        $this->bookRepository->expects($this->once())
            ->method('update')
            ->will($this->throwException(new \Exception("SQL error")));
        $this->bookRepository->update($book);
    }

    public function testFindAllBooks()
    {
        $books = [
            new Book('Title1', 'Author1', '2022-01-01', 5),
            new Book('Title2', 'Author2', '2022-02-01', 10),
        ];

        $this->bookRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($books);

        $result = $this->bookRepository->findAll();

        $this->assertCount(2, $result);
        $this->assertEquals('Title1', $result[0]->getTitle());
    }

    public function testFindAllBooksEmpty()
    {
        $this->bookRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $result = $this->bookRepository->findAll();
        $this->assertCount(0, $result);
    }

    public function testFindAllBooksSQLException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("SQL error");
        $this->bookRepository->expects($this->once())
            ->method('findAll')
            ->will($this->throwException(new \Exception("SQL error")));
        $this->bookRepository->findAll();
    }
}
