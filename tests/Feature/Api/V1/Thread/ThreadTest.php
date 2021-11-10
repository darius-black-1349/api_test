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

    use RefreshDatabase;
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

        Sanctum::actingAs(factory(Thread::class)->create());

        $thread = factory(Thread::class)->create();

        $response = $this->get(route('threads.show', [$thread->slug]));

        $response->assertStatus(Response::HTTP_OK);
    }


    // store thread test

    public function test_create_thread_should_be_validated()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $response = $this->postJson(route('threads.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_can_create_thread()
    {

        // $this->withoutExceptionHandling();

        Sanctum::actingAs(factory(User::class)->create());

        $response = $this->postJson(route('threads.store'), [
            'title' => 'FOO',
            'content' => 'BAR',
            'channel_id' => factory(Channel::class)->create()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }


    //update thread test

    public function test_edit_thread_should_be_validated()
    {
        Sanctum::actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $response = $this->putJson(route('threads.update', [$thread]), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_can_update_thread()
    {

        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $thread = factory(Thread::class)->create([

            'title' => 'FOO',
            'conten' => 'BAR',
            'channel_id' => factory(Channel::class)->create()->id,
            'user_id' => $user->id
        ]);

        $response = $this->putJson(route('threads.store', [$thread]), [
            'title' => 'BAR',
            'conten' => 'BAR',
            'channel_id' => factory(Channel::class)->create()->id,
        ]);

        $thread->refresh(); // cause to barricade repetetive request to database to get id and refresh database factory details

        $this->assertSame('BAR', $thread->title);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_can_best_answer_id_for_thread()
    {

        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $thread = factory(Thread::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->putJson(route('threads.store', [$thread]), [

            'best_answer_id' => 1,
        ]);

        $thread->refresh(); // cause to barricade repetetive request to database to get id and refresh database factory details

        $this->assertSame( '1', $thread->best_answer_id);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_can_delete_thread()
    {

        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $thread = factory(Thread::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->delete(route('threads.destroy', [$thread->id]));

        $response->assertStatus(Response::HTTP_OK);
    }
}
