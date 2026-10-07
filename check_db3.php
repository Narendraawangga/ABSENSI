<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n--- USERS WITH DELETED AT ---\n";
$users = Illuminate\Support\Facades\DB::table('users')->select('id', 'name', 'deleted_at')->get();
foreach($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Deleted: {$user->deleted_at}\n";
}
echo "----------------\n";
