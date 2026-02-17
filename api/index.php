<?php

/**
 * Vercel Serverless Entry Point — পাহাড়ি স্বাদ
 *
 * Handles:
 *  - Creating writable directories in /tmp (Vercel has read-only filesystem)
 *  - Copying the pre-seeded SQLite database to /tmp on cold start
 *  - Overriding Laravel storage/bootstrap paths to /tmp
 *  - Bootstrapping and handling the HTTP request
 */

define('LARAVEL_START', microtime(true));

// ── 1. Create writable directories in /tmp ───────────────────────────────────
foreach ([
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
] as $dir) {
    is_dir($dir) || mkdir($dir, 0755, true);
}

// ── 2. Copy pre-seeded SQLite to /tmp on cold start ──────────────────────────
$dbDest   = '/tmp/database.sqlite';
$dbSource = __DIR__ . '/../database/database.sqlite';

if (!file_exists($dbDest) || filesize($dbDest) === 0) {
    copy($dbSource, $dbDest);
    chmod($dbDest, 0664);
}

// ── 3. Override DB path env before app boots ─────────────────────────────────
putenv("DB_DATABASE=$dbDest");
$_ENV['DB_DATABASE']    = $dbDest;
$_SERVER['DB_DATABASE'] = $dbDest;

// ── 4. Register Composer autoloader ──────────────────────────────────────────
require __DIR__ . '/../vendor/autoload.php';

// ── 5. Bootstrap Laravel ─────────────────────────────────────────────────────
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Point storage → /tmp/storage so all writes go there
$app->useStoragePath('/tmp/storage');

// ── 6. Handle the HTTP request ───────────────────────────────────────────────
$app->handleRequest(\Illuminate\Http\Request::capture());
