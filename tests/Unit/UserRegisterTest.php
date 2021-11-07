<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRegisterTest extends TestCase
{

    use RefreshDatabase;


    public function registerRoleAndOermissions()
    {
        $roleInDatabase = Role::where('name', config('permission.default_roles')[0]);

        if ($roleInDatabase->count() > 1) {
            foreach (config('permission.default_roles') as $role) {

                Role::create([

                    'name' => $role

                ]);
            }
        }


        $permissionInDatabase = Permission::where('name', config('permission.default_permissions')[0]);

        if ($permissionInDatabase->count() > 1) {
            foreach (config('permission.default_permissions') as $permission) {

                Permission::create([

                    'name' => $permission

                ]);
            }
        }
    }


    //register TESTS
    public function test_register_should_be_validate()
    {
        $response = $this->postJson(route('auth.register'));

        $response->assertStatus(422);
    }

    public function test_new_user_can_register()
    {

        $this->registerRoleAndOermissions();

        $response = $this->postJson(route('auth.register'), [

            'name' => 'daius',
            'email' => 'darius1349@gmail.com',
            'password' => '123456789',

        ]);

        $response->assertStatus(201);
    }



    //Login TESTS
    public function test_login_should_be_validate()
    {
        $response = $this->postJson(route('auth.login'));

        $response->assertStatus(422);
    }

    public function test_user_can_login_with_true_credentials()
    {

        $user = factory(User::class)->create();

        $response = $this->postJson(route('auth.login'), [

            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(201);
    }


    //LOGOUT TESTS

    public function test_log_user_can_logout()
    {

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson(route('auth.logout'));

        $response->assertStatus(200);
    }



    //USER TEST

    public function test_show_user_info_if_logged_in()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('auth.user'));

        $response->assertStatus(200);
    }
}
