<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $corsarstl = create('App\User', ['name' => 'corsarstl']);

        $this->singIn($corsarstl);

        $corsarstl2 = create('App\User', ['name' => 'corsarstl2']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => '@corsarstl look at this @john'
        ]);

        $this->json('post', $thread->path().'/replies', $reply->toArray());

        $this->assertCount(1, $corsarstl->notifications);
    }
}
