<?php
require_once __DIR__ . '/../../core/repositories/BookRepositoryInterface.php';
require_once __DIR__ . '/../../core/entities/Book.php';

class BookRepositoryImplement implements BookRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Book $book)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO books (title, author, published_date, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$book->getTitle(), $book->getAuthor(), $book->getPublishedDate(), $book->getQuantity()]);
        } catch (PDOException $e) {
            throw new \Exception("Error saving book: " . $e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return new Book($data['title'], $data['author'], $data['published_date'], $data['quantity'], $data['id']);
            }

            return null; // Book not found
        } catch (PDOException $e) {
            throw new \Exception("Error finding book by ID: " . $e->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM books");
            $books = [];
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $books[] = new Book($data['title'], $data['author'], $data['published_date'], $data['quantity'], $data['id']);
            }
            return $books;
        } catch (PDOException $e) {
            throw new \Exception("Error finding all books: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new \Exception("Error deleting book: " . $e->getMessage());
        }
    }

    public function update(Book $book)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE books SET title = ?, author = ?, published_date = ?, quantity = ? WHERE id = ?");
            $stmt->execute([$book->getTitle(), $book->getAuthor(), $book->getPublishedDate(), $book->getQuantity(), $book->id]);
        } catch (PDOException $e) {
            throw new \Exception("Error updating book: " . $e->getMessage());
        }
    }
}
