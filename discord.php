<?php
// Your Discord webhook url
$webhookUrl = '';
// The directory where clip are uploaded
$directory = '';

function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}

if (!is_dir($directory)) {
    echo "The directory does not exist.", PHP_EOL;
    exit;
}

$files = glob_recursive($directory . '*.mp4');

if (empty($files)) {
    echo "No .mp4 files found. ", PHP_EOL;
    exit;
}

$oldestFile = null;
$oldestTimestamp = PHP_INT_MAX;

foreach ($files as $file) {
    $timestamp = filemtime($file);
    if ($timestamp < $oldestTimestamp) {
        $oldestTimestamp = $timestamp;
        $oldestFile = $file;
    }
}

$message = "Oldest .mp4 file found: $oldestFile";

$data = [
    'content' => $message,
    'file' => new CURLFile($oldestFile, 'video/mp4', basename($oldestFile)),
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error cURL: ' . curl_error($ch), PHP_EOL;
} else {
    echo "File sent successfully: $oldestFile", PHP_EOL;

    unlink($oldestFile);
    echo "File deleted: $oldestFile", PHP_EOL;
}

curl_close($ch);
