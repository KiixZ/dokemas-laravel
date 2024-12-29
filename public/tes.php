<?php

$publicPath = __DIR__;
$storagePath = realpath(__DIR__ . '/../storage/app/public');

echo "Public directory: $publicPath<br>";
echo "Is writable: " . (is_writable($publicPath) ? 'Yes' : 'No') . "<br>";

echo "Storage directory: $storagePath<br>";
echo "Is writable: " . (is_writable($storagePath) ? 'Yes' : 'No') . "<br>";

echo "Trying to write to public directory...<br>";
$testFile = $publicPath . '/test.txt';
if (file_put_contents($testFile, 'Test')) {
    echo "Success! File created: $testFile<br>";
    unlink($testFile);
} else {
    echo "Failed to write to public directory<br>";
}

echo "Trying to write to storage directory...<br>";
$testFile = $storagePath . '/test.txt';
if (file_put_contents($testFile, 'Test')) {
    echo "Success! File created: $testFile<br>";
    unlink($testFile);
} else {
    echo "Failed to write to storage directory<br>";
}

