<?php
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookName = htmlspecialchars($_POST['new-book-name']);
        $bookPublisher = htmlspecialchars($_POST['new-book-publisher']);
        $bookYear = htmlspecialchars($_POST['new-book-year']);
        $bookCover = $_FILES['book-cover'];

        $fileName = "$bookName,$bookPublisher,$bookYear." . pathinfo($bookCover['name'], PATHINFO_EXTENSION);
        $targetDir = '../buku/';
        $targetFile = $targetDir . basename($fileName);

        if (move_uploaded_file($bookCover['tmp_name'], $targetFile)) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('Failed to upload book cover');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
