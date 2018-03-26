<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    protected static function boot()
    {
        parent::boot();

//        now see 'replies_count' in 'threads'

//        static::addGlobalScope('replyCount', function ($builder) {
//            $builder->withCount('replies');
//        });

        static::deleting(function ($thread) {
//            $thread->replies->each(function ($reply) {
//                $reply->delete();
//            });
            $thread->replies->each->delete();
        });
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    public function notifySubscribers($reply)
    {
//        VARIANT 1

        $this->subscriptions
            ->filter(function ($sub) use ($reply) {
                return $sub->user_id != $reply->user_id;
            })
            ->each(function ($sub) use ($reply) {
                $sub->user->notify(new ThreadWasUpdated($this, $reply));
            });
//            or using “higher order” messages for collections
//          ->each->notify($reply);

//        VARIANT 2

//        foreach ($this->subscriptions as $subscription) {
//            if($subscription->user_id != $reply->user_id) {
//                $subscription->user->notify(new ThreadWasUpdated($this, $reply));
//            }
//        }
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * @param null $userId
     * @return Thread
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ? : auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ? : auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
