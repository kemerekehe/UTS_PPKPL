<?php

require_once __DIR__ . '/../../infrastructure/repositories/BookRepositoryImplement.php';

class GetAllBooks
{
    private $bookRepository;

    public function __construct(BookRepositoryImplement $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute()
    {
        try {
            return $this->bookRepository->findAll();
        } catch (\Exception $e) {
            throw new \Exception("Error getting books: " . $e->getMessage());
        }
    }
}
?>