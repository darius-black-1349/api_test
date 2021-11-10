<?php

namespace Tests\Feature\Api\v1\Thread;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnswerTest extends TestCase
{
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
}
