<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/core/usecases/GetAllBooks.php';

class GetAllBooksTest extends TestCase
{
    private $bookRepository;
    private $getAllBooks;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryImplement::class);
        $this->getAllBooks = new GetAllBooks($this->bookRepository);
    }

    public function testGetAllBooks()
    {
        $books = [
            new Book('Book 1', 'Author 1', '2022-01-01', 5),
            new Book('Book 2', 'Author 2', '2022-01-02', 3)
        ];
        $this->bookRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($books);
        $result = $this->getAllBooks->execute();
        $this->assertCount(2, $result);
        $this->assertEquals('Book 1', $result[0]->getTitle());
        $this->assertEquals('Author 1', $result[0]->getAuthor());
    }

    public function testGetAllBooksEmpty()
    {
        $this->bookRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $result = $this->getAllBooks->execute();
        $this->assertCount(0, $result);
    }

    public function testGetAllBooksException()
    {
        $this->bookRepository->expects($this->once())
            ->method('findAll')
            ->will($this->throwException(new Exception('Database error')));
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error getting books: Database error');
        $this->getAllBooks->execute();
    }
}

?>