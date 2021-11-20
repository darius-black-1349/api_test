<?php

namespace Tests\Feature\Api\v1\Thread;

use App\Answer;
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



    // store answers_test

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

        $response->assertJson([

            'message' => 'answer submitted successfully'

        ]);

        $this->assertTrue($thread->answers()->where('content', 'FOO')->exists());
    }



    // update answers_test

    public function test_update_answer_should_be_validated()
    {
        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $answer = factory(Answer::class)->create();

        $response = $this->putJson(route('answers.update'), [$answer], []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(['content']);
    }


    public function test_update_answer_of_thread()
    {
        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $answer = factory(Answer::class)->create([

            'content' => 'FOO'

        ]);

        $response = $this->putJson(route('answers.update', [$answer]), [

            'content' => 'BAR',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([

            'message' => 'answer updated successfully'

        ]);

        $answer->refresh();
        $this->assertEquals('BAR', $answer->content);
    }


    public function test_delete_answer()
    {
        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $answer = factory(Answer::class)->create();

        $response = $this->delete(route('answers.destroy', [$answer]));

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([

            'message' => 'answer deleted successfully'

        ]);


        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());
    }

}
