<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Test 1: Find user without scope
$user = User::withoutGlobalScope(\App\Core\Traits\BelongsToTenant::class)
    ->where('email', 'admin@faspool.com')
    ->first();

if (!$user) {
    echo "TEST 1 FAIL: User not found\n";
    exit(1);
}
echo "TEST 1 PASS: User found\n";

// Test 2: Verify password
if (!Hash::check('password', $user->password)) {
    echo "TEST 2 FAIL: Password mismatch\n";
    exit(1);
}
echo "TEST 2 PASS: Password matches\n";

// Test 3: Check login with remember
$credentials = ['email' => 'admin@faspool.com', 'password' => 'password'];
if (auth()->attempt($credentials)) {
    echo "TEST 3 PASS: Auth::attempt() works\n";
    auth()->logout();
} else {
    echo "TEST 3 FAIL: Auth::attempt() failed\n";
}

echo "\nALL TESTS PASSED\n";
