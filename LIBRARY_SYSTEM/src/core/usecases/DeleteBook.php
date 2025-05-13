<?php

require_once __DIR__ . '/../../infrastructure/repositories/BookRepositoryImplement.php';

class DeleteBook
{
    private $bookRepository;

    public function __construct(BookRepositoryImplement $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute($id)
    {
        try {
            $this->bookRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception("Error deleting book: " . $e->getMessage());
        }
    }
}

?>