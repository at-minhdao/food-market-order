<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;

class DeleteUserTest extends DuskTestCase
{
    use DatabaseTransactions;

    /**
     * A Dusk test show content.
     *
     * @return void
     */
    public function testContent()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->resize(1920, 1080)
                ->visit('/users')
                ->assertSee("User's Table Data")
                ->screenshot('testContent');
        });
    }

    /**
     * A Dusk test delete success.
     *
     * @return void
     */
    public function testDeleteSuccess()
    {
        $this->browse(function (Browser $browser) {
            factory(User::class, 5)->create();
            $browser->loginAs(1)
                ->resize(1920, 1080)
                ->visit('/users')
                ->click('#btn-delete-2')
                ->acceptDialog()
                ->assertSee('Delete Successfully!')
                ->screenshot('testDeleteSuccess');
        });
    }

    /**
     * A Dusk test delete current admin user login.
     *
     * @return void
     */
    public function testDeleteCurrentUser()
    {
        $this->browse(function (Browser $browser) {
            factory(User::class, 5)->create();
            $browser->loginAs(1)
                ->resize(1920, 1080)
                ->visit('/users')
                ->click('#btn-delete-1')
                ->acceptDialog()
                ->assertSee('Cannot delete current user!')
                ->screenshot('testDeleteCurrentUser');
        });
    }

    /**
     * A Dusk test delete success.
     *
     * @return void
     */
    public function testDeleteFail()
    {
        $this->browse(function (Browser $browser) {
            DB::table('users')->insert([
                'full_name' => 'test',
                'email' => 'test'.'@gmail.com',
                'password' => bcrypt('123456'),
                'is_admin' => 0,
            ]);
            $browser->loginAs(1)
                ->resize(1920, 1080)
                ->visit('/users');
            DB::table('users')->delete(2);
            $browser->click('#btn-delete-2')
                ->acceptDialog()
                ->assertSee('Delete Error!')
                ->screenshot('testDeleteError');
        });
    }
}
