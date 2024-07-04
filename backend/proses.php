<?php
header('Content-Type: application/json'); // Pastikan header JSON ditetapkan

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $bookTitle = htmlspecialchars($_POST['book-title']);
        $name = htmlspecialchars($_POST['name']);

        // Cari buku berdasarkan judul
        $dir = '../buku';
        $bookFound = false;

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        $fileName = pathinfo($file, PATHINFO_FILENAME);
                        $bookDetails = explode(',', $fileName);
                        if (count($bookDetails) == 3 && strtolower($bookDetails[0]) == strtolower($bookTitle)) {
                            $bookFound = true;
                            break;
                        }
                    }
                }
                closedir($dh);
            }
        }

        if ($bookFound) {
            $orderData = "Book Title: $bookTitle\nName: $name\n";
            $orderFile = '../orders/' . time() . '.txt';

            if (file_put_contents($orderFile, $orderData)) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to save order');
            }
        } else {
            throw new Exception('Book not found');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
