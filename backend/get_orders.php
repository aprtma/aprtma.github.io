<?php
header('Content-Type: application/json');

$orders = [];
$dir = '../orders';

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && pathinfo($file, PATHINFO_EXTENSION) == 'txt') {
                $orderData = file_get_contents("$dir/$file");
                $orderLines = explode("\n", trim($orderData));
                $bookTitle = '';
                $name = '';

                foreach ($orderLines as $line) {
                    if (strpos($line, 'Book Title:') !== false) {
                        $bookTitle = trim(str_replace('Book Title:', '', $line));
                    } elseif (strpos($line, 'Name:') !== false) {
                        $name = trim(str_replace('Name:', '', $line));
                    }
                }

                $orders[] = [
                    'file' => $file,
                    'book_title' => $bookTitle,
                    'name' => $name
                ];
            }
        }
        closedir($dh);
    }
}

echo json_encode(['orders' => $orders]);
?>
