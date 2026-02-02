<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Services\WordImportService;

// Mock class nếu cần hoặc dùng class thật nếu autoload hoạt động
// Vì đây là script chạy CLI riêng, ta cần đảm bảo autoload
// Giả sử chạy từ root project với `php tests/debug_import.php`

$service = new WordImportService();

$text = "Nút số 8 là:
Nút 1.
Nút 2.
Nút 3.
Nút 4.
Đáp án: b
Phần: Phần 1 – Lý Thuyết tổng hợp
Độ khó: 1
Danh mục: Thủy nghiệp cơ bản
---
Nút dẹt dễ mở là:
Nút 1.
Nút 2.
Nút 3.
Nút 4.
Đáp án: c
Phần: Phần 1 – Lý Thuyết tổng hợp
Độ khó: 1
Danh mục: Thủy nghiệp cơ bản
---";

echo "--- START DEBUG ---\n";
echo "Input length: " . strlen($text) . "\n";

$blocks = $service->parseText($text);

echo "Found " . count($blocks) . " blocks (questions).\n";

foreach ($blocks as $i => $q) {
    echo "Block $i:\n";
    echo "  Question: " . $q['question'] . "\n";
    echo "  Answers: A={$q['option_a']}, B={$q['option_b']}\n";
    echo "  Result: " . ($q['question'] ? 'OK' : 'FAIL') . "\n";
    echo "----------------\n";
}

if (count($blocks) === 2 && strpos($blocks[0]['question'], 'Nút số 8 là') !== false) {
    echo "SUCCESS: Logic parseText hoạt động đúng với chuỗi mẫu.\n";
} else {
    echo "FAILURE: Logic parseText không hoạt động như mong đợi.\n";
    // Check regex fallback logic inside parseText logic simulation
}
