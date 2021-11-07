<?php

namespace Tests\Unit;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ChannelTest extends TestCase
{

    use RefreshDatabase;


    public function registerRolesAndPemissions()
    {
        $roleInDatabase = Role::where('name', config('permission.default_roles')[0]);

        if ($roleInDatabase->count() > 1) {
            foreach (config('permission.default_roles') as $role) {

                Role::create([

                    'name' => $role

                ]);
            }
        }

        //permissions creating

        $permissionInDatabase = Permission::where('name', config('permission.default_permissions')[0]);

        if ($permissionInDatabase->count() > 1) {
            foreach (config('permission.default_permissions') as $permission) {

                Permission::create([

                    'name' => $permission

                ]);
            }
        }
    }


    public function test_all_channels_should_be_accessible()
    {
        $response = $this->get(route('channel.all'));

        $response->assertStatus(200);
    }

    public function test_channel_should_be_validated()
    {

        $this->registerRolesAndPemissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('channel management');

        $response = $this->actingAs($user)->postJson(route('channel.create'), []);

        $response->assertStatus(422);
    }

    public function test_channel_can_be_created()
    {

        $this->registerRolesAndPemissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('channel management');


        $response = $this->actingAs($user)->postJson(route('channel.create'), [

            'name' => 'laravel'

        ]);

        $response->assertStatus(201);
    }


    public function test_channel_update_should_be_validated()
    {
        $this->registerRolesAndPemissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('channel management');

        $response = $this->actingAs($user)->json('PUT', route('channel.update'), []);

        $response->assertStatus(422);
    }

    public function test_channel_update()
    {

        $this->registerRolesAndPemissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('channel management');

        $channel = factory(Channel::class)->create();

        $response = $this->actingAs($user)->json('PUT', route('channel.update'), [

            'id' => $channel->id,
            'name' => 'vuejs'
        ]);

        $updatedChannel = Channel::find($channel->id);

        $response->assertStatus(200);
        $this->assertEquals('vuejs', $updatedChannel->name);
    }

    public function test_channel_delete_should_be_validated()
    {
        $this->registerRolesAndPemissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('channel management');

        $response = $this->actingAs($user)->json('DELETE', route('channel.delete'));

        $response->assertStatus(422);
    }

    public function test_delete_channel()
    {
        $this->registerRolesAndPemissions();

        $user = factory(User::class)->create();
        $user->givePermissionTo('channel management');

        $channel = factory(Channel::class)->create();

        $response = $this->actingAs($user)->json('DELETE', route('channel.delete'), [

            'id' => $channel->id

        ]);

        $response->assertStatus(200);
    }
}
