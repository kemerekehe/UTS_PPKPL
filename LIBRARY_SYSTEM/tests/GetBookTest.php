<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/core/usecases/GetBookById.php';

class GetBookTest extends TestCase
{
    private $bookRepository;
    private $getBookById;

    protected function setUp(): void
    {
        $this->bookRepository = $this->createMock(BookRepositoryImplement::class);
        $this->getBookById = new GetBookById($this->bookRepository);
    }

    public function testReadBook()
{
    $bookId = 1;
    $book = new Book('Title', 'Author', '2022-01-01', 5);

    $this->bookRepository->expects($this->once())
        ->method('findById')
        ->with($this->equalTo($bookId))
        ->willReturn($book);

    $result = $this->getBookById->execute($bookId);
    $this->assertEquals('Title', $result->getTitle());
}

    public function testReadBookNotFound()
    {
        $bookId = 999;

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($bookId))
            ->willReturn(null);

        $result = $this->getBookById->execute($bookId);
        $this->assertNull($result);
    }

    public function testReadBookException()
    {
        $bookId = 1;

        $this->bookRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($bookId))
            ->willThrowException(new Exception('Database error'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error getting book: Database error');

        $this->getBookById->execute($bookId);
    }
}
?>