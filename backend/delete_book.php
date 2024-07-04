<?php
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['file'])) {
            $file = htmlspecialchars($input['file']);
            $filePath = "../buku/$file";

            if (file_exists($filePath)) {
                unlink($filePath);
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('File not found');
            }
        } else {
            throw new Exception('Invalid input');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
