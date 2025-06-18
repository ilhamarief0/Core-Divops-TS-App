<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */

    public function testUserCanAccessLogin(): void
    {
      $this->browse(function (Browser $browser) {
        $browser->visitRoute('login')
            ->assertSee('Login');
      });
    }

    public function testLogin(): void
    {
      $this->browse(function (Browser $browser) {

          $browser->visitRoute('login')
          // ->waitFor('#login-heading', 10)
          // ->assertVisible('#login-heading')

          // Gunakan selector eksplisit di sini:
          ->type('input[name="username"]', 'admindivops')
          ->type('input[name="password"]', '@9UpT4#ts')

          // Untuk tombol submit, gunakan ID jika ada, atau selector lain
          ->press('#kt_sign_in_submit') // Contoh menggunakan ID
        ->assertPathIs('/'); // Sesuaikan ini
    });
    }
}
