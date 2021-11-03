<?php

namespace Tests\Unit;

use App\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelTest extends TestCase
{

    use RefreshDatabase;

    public function test_all_channels_should_be_accessible()
    {
       $response = $this->get(route('channel.all'));

       $response->assertStatus(200);
    }

    public function test_channel_should_be_validated()
    {
        $response = $this->postJson(route('channel.create'), []);

        $response->assertStatus(422);
    }

    public function test_channel_can_be_created()
    {
        $response = $this->postJson(route('channel.create'), [

            'name' => 'laravel'

        ]);

        $response->assertStatus(201);
    }


    public function test_channel_update_should_be_validated()
    {
        $response = $this->json('PUT', route('channel.update'), []);

        $response->assertStatus(422);
    }

    public function test_channel_update()
    {

        $channel = factory(Channel::class)->create();

        $response = $this->json('PUT', route('channel.update'), [

            'id' => $channel->id,
            'name' => 'vuejs'
        ]);

        $updatedChannel = Channel::find($channel->id);

        $response->assertStatus(200);
        $this->assertEquals('vuejs', $updatedChannel->name);
    }

}
