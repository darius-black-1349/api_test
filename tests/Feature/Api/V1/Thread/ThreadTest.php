<?php

namespace Tests\Feature\Api\V1\Thread;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_all_threads_should_be_accessible()
    {
        $response = $this->get(route('threads.index'));

        $response->assertStatus(200);
    }

    public function test_thread_should_be_accessible_by_slug()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get(route('threads.show', [$thread->slug]));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_thread_should_be_validated()
    {
        $response = $this->postJson(route('threads.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_can_create_thread()
    {

        // $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->create());

        $response = $this->postJson(route('threads.store'), [
            'title' => 'FOO',
            'conten' => 'BAR',
            'channel_id' => factory(Channel::class)->create()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
