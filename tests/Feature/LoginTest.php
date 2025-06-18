<?php

// Use statements tetap di bagian paling atas
use function Pest\Laravel\{get, post};
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase; // Tetap diperlukan karena digunakan oleh uses()
// use Illuminate\Foundation\Testing\WithFaker; // Tetap jika digunakan
use Illuminate\Support\Facades\Hash; // Tetap jika menggunakan Hash::make()
// use Tests\TestCase; // Tidak lagi diperlukan karena tidak ada kelas yang extends ini

// Terapkan trait atau base class menggunakan uses() di level atas file
// Ini akan menghubungkan RefreshDatabase (dan secara implisit context TestCase)
// ke semua test it() dan test() di file ini.
uses(RefreshDatabase::class);

// --- Mulai Penulisan Test Pest dengan Sintaks Fungsional ---

// Test untuk rendering halaman login
it('renders the login page', function () {
    // Menggunakan helper get() dari Pest
    $response = get('/login'); // Pastikan '/login' adalah URL yang benar

    $response->assertStatus(200); // Memastikan status HTTP 200 (OK)
    $response->assertSee('Login'); // Memastikan teks 'Login' ada di halaman
    // Menggunakan assertSee untuk elemen spesifik dalam HTML sumber
    $response->assertSee('<form');
    // --- Sesuaikan dengan nama input di HTML: username, bukan email ---
    $response->assertSee('name="username"'); // Memastikan ada input username
    $response->assertSee('name="password"'); // Memastikan ada input password
    // --- Opsional: pastikan tombol submit ada ---
    $response->assertSee('<button type="submit"');
    // Atau cek ID-nya jika ada: assertSee('id="kt_sign_in_submit"')
});

// Test untuk login berhasil
it('allows a user to login with valid credentials', function () {
    // Membuat user menggunakan factory
    $user = User::factory()->create([
        // --- Sesuaikan field identifikasi dengan 'username' ---
        'username' => 'testuser', // Ganti 'test@example.com'
        // Password yang akan digunakan untuk login (dalam bentuk plain text untuk request post)
        'password' => Hash::make($password = 'password123'),
        // Tambahkan field lain yang diperlukan oleh factory User, misal 'email' jika ada
        'email' => 'test@example.com',
    ]);

    // Menggunakan helper post() dari Pest
    $response = post('/login', [ // Pastikan '/login' adalah URL POST untuk proses login
        // --- Sesuaikan nama field dengan input di HTML: username, bukan email ---
        'username' => 'testuser',
        'password' => $password, // Gunakan plain text password yang disimpan
    ]);

    // Assertion untuk memastikan user berhasil diautentikasi
    // Metode $this->assertAuthenticated() tersedia karena uses(RefreshDatabase::class)
    // secara implisit menghubungkan dengan TestCase Laravel.
    $this->assertAuthenticated();

    // Assertion untuk memastikan redirect setelah login
    // --- GANTI '/home' jika URL redirect setelah sukses login berbeda ---
    $response->assertRedirect('/home');
});

// Test untuk login gagal
it('prevents a user from logging in with invalid credentials', function () {
    // Membuat user valid (opsional jika test ini hanya cek kredensial salah pada form kosong)
    $user = User::factory()->create([
        'username' => 'existinguser',
        'password' => Hash::make('correctpassword'),
        'email' => 'existing@example.com',
    ]);

    // --- Skenario 1: Username terdaftar, password salah ---
    $responseWrongPassword = post('/login', [
        'username' => 'existinguser',
        'password' => 'wrongpassword', // Password salah
    ]);

    $responseWrongPassword->assertStatus(302); // Login gagal biasanya redirect (status 302)
    $this->assertGuest(); // Memastikan user tidak diautentikasi (masih guest)
    // --- Cek pesan error yang diflash ke session. Sesuaikan key dan pesan jika perlu. ---
    // Umumnya key yang diflash adalah 'username' atau 'email', tergantung implementasi LoginController/Request
    $responseWrongPassword->assertSessionHas('username'); // Memastikan ada pesan error di session untuk field 'username'

    // --- Skenario 2: Username tidak terdaftar ---
    $responseWrongUser = post('/login', [
        'username' => 'nonexistentuser', // Username tidak ada
        'password' => 'anypassword',
    ]);

    $responseWrongUser->assertStatus(302); // Redirect
    $this->assertGuest(); // Masih guest
    // --- Cek pesan error yang diflash ke session. Sesuaikan key. ---
    $responseWrongUser->assertSessionHas('username'); // Atau key lain yang digunakan

    // Anda bisa menambahkan skenario input kosong dan menguji assertSessionHasErrors
    // jika validasi form dilakukan sebelum autentikasi.
});

// Opsional: Test untuk user yang sudah login tidak bisa akses halaman login
it('redirects authenticated user from login page', function () {
    // Buat user dan login menggunakan helper actingAs() yang tersedia dari TestCase
    $user = User::factory()->create();
    $this->actingAs($user);

    // Coba akses halaman login
    $response = get('/login'); // Pastikan URL halaman login benar

    // Harusnya di-redirect dari halaman login jika sudah login
    // --- GANTI '/home' jika URL redirect untuk user terautentikasi berbeda ---
    $response->assertRedirect('/home');
});

// --- Tidak ada definisi kelas di sini ---
