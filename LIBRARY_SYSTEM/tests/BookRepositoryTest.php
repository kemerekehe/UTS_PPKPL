<?php
use PHPUnit\Framework\TestCase;

class BookRepositoryTest extends TestCase
{
    private $bookRepository;
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void
    {
        // Membuat mock untuk objek PDO dan PDOStatement
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);

        // Membuat objek BookRepositoryImplement dengan PDO mock
        $this->bookRepository = new BookRepositoryImplement($this->pdoMock);
    }

    public function testSaveValidBook()
    {
        // Membuat objek buku yang valid
        $book = new Book('Title', 'Author', '2022-01-01', 5);

        // Menyeting ekspektasi bahwa 'save' akan dipanggil sekali dengan objek buku tersebut
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([$book->getTitle(), $book->getAuthor(), $book->getPublishedDate(), $book->getQuantity()]))
            ->willReturn(true);

        // Memanggil metode save dan memastikan bahwa tidak ada pengecualian yang dilempar
        $this->bookRepository->save($book);
    }

    public function testSaveBookSQLException()
    {
        // Menguji jika terjadi SQL exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("SQL error");

        // Menyeting ekspektasi bahwa akan terjadi pengecualian SQL saat mencoba menyimpan
        $book = new Book('Title', 'Author', '2022-01-01', 5);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \Exception("SQL error")));

        // Memanggil metode save yang akan melempar pengecualian
        $this->bookRepository->save($book);
    }

    public function testSaveBookSQLFailure()
    {
        // Menguji kegagalan koneksi database
        $book = new Book('Title', 'Author', '2022-01-01', 5);

        // Membuat mock untuk PDOStatement dengan eksekusi yang gagal
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willThrowException(new PDOException("Database error"));

        // Memastikan bahwa pengecualian dilemparkan
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error saving book: Database error");

        $this->bookRepository->save($book);
    }

    public function testFindByIdReturnsBookWhenFound()
    {
        // Data yang diharapkan dari query
        $expectedData = [
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'published_date' => '2005-06-01',
            'quantity' => 5,
            'id' => 1
        ];

        // Menyeting mock untuk metode prepare dan execute
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->with([1])->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn($expectedData);

        // Memanggil fungsi findById dan memeriksa objek yang dikembalikan
        $book = $this->bookRepository->findById(1);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('Laskar Pelangi', $book->getTitle());
        $this->assertEquals('Andrea Hirata', $book->getAuthor());
        $this->assertEquals('2005-06-01', $book->getPublishedDate());
        $this->assertEquals(5, $book->getQuantity());
        $this->assertEquals(1, $book->getId());
    }

    public function testFindByIdReturnsNullWhenBookNotFound()
    {
        // Menyeting mock untuk query yang tidak menemukan buku
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->with([999])->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn(false); // Tidak ditemukan

        // Memanggil fungsi findById dengan ID yang tidak ada
        $book = $this->bookRepository->findById(999);

        // Memastikan bahwa null dikembalikan
        $this->assertNull($book);
    }

    public function testFindByIdThrowsExceptionOnDatabaseError()
    {
        // Menyeting mock untuk metode execute yang melempar pengecualian
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->will($this->throwException(new PDOException('Database error')));

        // Memastikan pengecualian dilempar saat ada kesalahan pada database
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error finding book by ID: Database error");

        // Memanggil fungsi findById yang menyebabkan pengecualian
        $this->bookRepository->findById(1);
    }

    public function testFindAllReturnsBooks()
    {
        // Data yang diharapkan dari query
        $expectedData = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'published_date' => '2005-06-01',
                'quantity' => 5,
                'id' => 1
            ],
            [
                'title' => 'Pulang',
                'author' => 'Leila Chudori',
                'published_date' => '2012-05-12',
                'quantity' => 3,
                'id' => 2
            ]
        ];

        // Menyeting mock untuk metode prepare dan execute
        $this->pdoMock->method('query')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturnOnConsecutiveCalls(
            $expectedData[0],  // Kembalikan buku pertama pada pemanggilan pertama
            $expectedData[1],  // Kembalikan buku kedua pada pemanggilan kedua
            false              // Setelah itu, kembalikan false untuk menandakan tidak ada data lagi
        );

        // Memanggil fungsi findAll dan memastikan bahwa dua buku dikembalikan
        $books = $this->bookRepository->findAll();

        // Memastikan hasilnya adalah array dengan dua objek buku
        $this->assertCount(2, $books);
        $this->assertInstanceOf(Book::class, $books[0]);
        $this->assertEquals('Laskar Pelangi', $books[0]->getTitle());
        $this->assertEquals('Pulang', $books[1]->getTitle());
    }

    public function testFindAllReturnsEmptyArrayWhenNoBooksFound()
    {
        // Menyeting mock untuk query yang tidak menemukan buku
        $this->pdoMock->method('query')->willReturn($this->stmtMock);
        $this->stmtMock->method('fetch')->willReturn(false);  // Tidak ada data

        // Memanggil fungsi findAll dan memastikan bahwa array kosong dikembalikan
        $books = $this->bookRepository->findAll();

        // Memastikan bahwa array yang dikembalikan kosong
        $this->assertCount(0, $books);
    }

    public function testFindAllThrowsExceptionOnDatabaseError()
    {
        // Menyeting mock untuk query yang melempar pengecualian
        $this->pdoMock->method('query')->willThrowException(new PDOException('Database error'));

        // Memastikan bahwa pengecualian dilempar ketika terjadi kesalahan pada database
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error finding all books: Database error");

        // Memanggil fungsi findAll yang akan menyebabkan pengecualian
        $this->bookRepository->findAll();
    }

    public function testDeleteBook()
{
    // Membuat objek buku yang valid
    $book = new Book('Title', 'Author', '2022-01-01', 5, 1); // Pastikan ID sudah diset di sini

    // Menyeting ekspektasi bahwa 'delete' akan dipanggil sekali dengan ID buku tersebut
    $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
    $this->stmtMock->expects($this->once())
        ->method('execute')
        ->with($this->equalTo([$book->getId()])) // Menggunakan getId() untuk mendapatkan ID buku
        ->willReturn(true);

    // Memanggil metode delete dan memastikan bahwa tidak ada pengecualian yang dilempar
    $this->bookRepository->delete($book->getId()); // Memastikan hanya ID yang dikirim, bukan objek buku
}

    public function testDeleteBookSQLException()
    {
        // Menguji jika terjadi SQL exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("SQL error");

        // Menyeting ekspektasi bahwa akan terjadi pengecualian SQL saat mencoba menghapus
        $book = new Book('Title', 'Author', '2022-01-01', 5, 1);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new \Exception("SQL error")));

        // Memanggil metode delete yang akan melempar pengecualian
        $this->bookRepository->delete($book);
    }

    public function testDeleteBookSQLFailure()
    {
        // Menguji kegagalan koneksi database
        $book = new Book('Title', 'Author', '2022-01-01', 5, 1);

        // Membuat mock untuk PDOStatement dengan eksekusi yang gagal
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willThrowException(new PDOException("Database error"));

        // Memastikan bahwa pengecualian dilemparkan
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error deleting book: Database error");

        $this->bookRepository->delete($book);
    }
    
     public function testUpdateBook()
    {
        // Membuat objek buku yang valid
        $book = new Book('Title', 'Author', '2022-01-01', 5);
        $book->setId(1); // ID buku yang akan diperbarui

        // Menyeting ekspektasi bahwa prepare dan execute dipanggil dengan data yang benar
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([
                $book->getTitle(),
                $book->getAuthor(),
                $book->getPublishedDate(),
                $book->getQuantity(),
                $book->getId()
            ]))
            ->willReturn(true);

        // Memanggil fungsi update dan memastikan tidak ada pengecualian yang dilempar
        $this->bookRepository->update($book);
    }

    public function testUpdateThrowsExceptionOnDatabaseError()
    {
        // Membuat objek buku yang valid
        $book = new Book('Title', 'Author', '2022-01-01', 5);
        $book->setId(1); // ID buku yang akan diperbarui

        // Menyeting mock untuk query yang melempar pengecualian saat execute
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([
                $book->getTitle(),
                $book->getAuthor(),
                $book->getPublishedDate(),
                $book->getQuantity(),
                $book->getId()
            ]))
            ->will($this->throwException(new PDOException('Database error')));

        // Memastikan pengecualian dilemparkan dengan pesan yang sesuai
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error updating book: Database error");

        // Memanggil fungsi update yang akan menyebabkan pengecualian
        $this->bookRepository->update($book);
    }
}
?>
