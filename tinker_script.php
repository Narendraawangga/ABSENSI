<?php
echo "\n--- DB STATS ---\n";
echo "Total Users: " . \App\Models\User::count() . "\n";
if (class_exists('\App\Models\Intern')) {
    echo "Total Interns: " . \App\Models\Intern::count() . "\n";
    echo "Intern Names: " . \App\Models\Intern::pluck('name')->implode(', ') . "\n";
}
echo "----------------\n";
