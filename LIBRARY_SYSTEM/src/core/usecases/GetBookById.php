<?php

require_once __DIR__ . '/../../infrastructure/repositories/BookRepositoryImplement.php';

class GetBookById
{
    private $bookRepository;

    public function __construct(BookRepositoryImplement $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute($id)
    {
        try {
            return $this->bookRepository->findById($id);
        } catch (Exception $e) {
            throw new Exception("Error getting book: " . $e->getMessage());
        }
    }
}
