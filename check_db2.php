<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n--- INTERN PROFILES ---\n";
$profiles = Illuminate\Support\Facades\DB::table('intern_profiles')->get();
foreach($profiles as $profile) {
    echo "User ID: {$profile->user_id}, Division: {$profile->division}, Uni: {$profile->university}\n";
}

$users = Illuminate\Support\Facades\DB::table('users')->get();
foreach($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Username: {$user->username}\n";
}
echo "----------------\n";
