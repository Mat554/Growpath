<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// --- TAMBAHKAN BARIS INI UNTUK VERCEL ---
// Mengarahkan folder storage ke /tmp yang diizinkan oleh Vercel
$app->useStoragePath($_ENV['APP_STORAGE'] ?? '/tmp/storage');
// ----------------------------------------

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);