<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n--- DB STATS ---\n";
echo "Total Users: " . \App\Models\User::count() . "\n";
$users = \App\Models\User::with('role')->get();
foreach($users as $user) {
    echo "User: {$user->name}, Role: " . ($user->role ? $user->role->name : 'None') . "\n";
}
echo "----------------\n";
