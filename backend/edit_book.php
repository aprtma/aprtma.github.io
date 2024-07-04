<?php
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookId = htmlspecialchars($_POST['edit-book-id']);
        $bookName = htmlspecialchars($_POST['edit-book-name']);
        $bookPublisher = htmlspecialchars($_POST['edit-book-publisher']);
        $bookYear = htmlspecialchars($_POST['edit-book-year']);
        $bookCover = $_FILES['edit-book-cover'];

        $oldFilePath = "../buku/$bookId";
        $newFileName = "$bookName,$bookPublisher,$bookYear." . pathinfo($oldFilePath, PATHINFO_EXTENSION);
        $newFilePath = "../buku/$newFileName";

        if ($bookCover['size'] > 0) {
            $newFileName = "$bookName,$bookPublisher,$bookYear." . pathinfo($bookCover['name'], PATHINFO_EXTENSION);
            $newFilePath = "../buku/$newFileName";
            if (move_uploaded_file($bookCover['tmp_name'], $newFilePath)) {
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            } else {
                throw new Exception('Failed to upload new book cover');
            }
        } else {
            if (file_exists($oldFilePath)) {
                rename($oldFilePath, $newFilePath);
            } else {
                throw new Exception('Old book file not found');
            }
        }

        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
