<?php

namespace Tests\Feature\Api\v1\Thread;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_answers()
    {
        $response = $this->get(route('answers.index'));

        $response->assertStatus(200);
    }


    public function test_create_answer_should_be_validate()
    {
        $response = $this->postJson(route('answers.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(['content', 'thread_id']);
    }


    public function test_can_submit_new_answer_for_thread()
    {

        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        $thread = factory(Thread::class)->create();
        $response = $this->postJson(route('answers.store', [

            'content' => 'FOO',
            'thread_id' => $thread->id

        ]));

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertTrue($thread->answers()->where('content', 'FOO')->exists());
    }
}
