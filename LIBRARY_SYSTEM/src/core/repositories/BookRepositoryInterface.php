<?php

require_once __DIR__ . '/../entities/Book.php';

interface BookRepositoryInterface
{
    public function save(Book $book);            // Add: Menyimpan buku baru
    public function findById($id);               // Read: Menemukan buku berdasarkan ID
    public function findAll();                   // Browse: Menemukan semua buku
    public function update(Book $book);          // Edit: Memperbarui buku yang ada
    public function delete($id);                 // Delete: Menghapus buku berdasarkan ID
}
?>