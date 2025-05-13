<?php

require_once __DIR__ . '/../../infrastructure/repositories/BookRepositoryImplement.php';

class UpdateBook
{
    private $bookRepository;

    public function __construct(BookRepositoryImplement $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute(Book $book)
    {
        try {
            $this->bookRepository->update($book);
        } catch (\Exception $e) {
            throw new \Exception("Error updating book: " . $e->getMessage());
        }
    }
}

?>
