<?php

class AddBook
{
    private $bookRepository;

    public function __construct(BookRepositoryImplement $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute(Book $book)
    {
        try {
            $this->bookRepository->save($book);
        } catch (\Exception $e) {
            throw new \Exception("Error adding book: " . $e->getMessage());
        }
    }
}

?>