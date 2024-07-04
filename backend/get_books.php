<?php
header('Content-Type: application/json');

$books = [];
$dir = '../buku';

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $bookDetails = explode(',', $fileName);
                if (count($bookDetails) == 3) {
                    $books[] = [
                        'file' => $file,
                        'name' => $bookDetails[0],
                        'publisher' => $bookDetails[1],
                        'year' => $bookDetails[2]
                    ];
                }
            }
        }
        closedir($dh);
    }
}

echo json_encode(['books' => $books]);
?>
