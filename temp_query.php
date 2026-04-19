<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Http\Kernel");
$app->make("Illuminate\Contracts\Console\Kernel");

use Illuminate\Support\Facades\DB;

echo "=== DATABASE QUERY RESULTS ===\n";
echo "Count in buku table: " . DB::table("buku")->count() . "\n";
echo "Count in books table: " . DB::table("books")->count() . "\n";
echo "\nFirst record in books table:\n";
var_dump(DB::table("books")->first());
echo "\nFirst record in buku table:\n";
var_dump(DB::table("buku")->first());
