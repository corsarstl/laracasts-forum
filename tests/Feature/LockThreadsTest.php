<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_admins_may_not_lock_threads()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }
    
    /** @test */
    public function admins_can_lock_threads()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function admins_can_unlock_threads()
    {
        $this->signIn(factory('App\User')->states('admin')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => false]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }
    
    /** @test */
    public function locked_thread_cannot_receive_new_replies()
    {
        $this->signIn();

        $thread = create('App\Thread', ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}