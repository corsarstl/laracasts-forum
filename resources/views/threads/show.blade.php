@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-8 col-sm-8" v-cloak>
                    @include('threads.question')

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
                </div>

                <div class="col-4 col-sm-4">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <p>
                                This thread was published
                                <b>{{ $thread->created_at->diffForHumans() }}</b>
                                by
                                <a href="{{ route('profile', $thread->creator->name) }}">
                                    {{ $thread->creator->name }}
                                </a>,
                                and currently has
                                <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            </p>

                            <p v-cloak>
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"
                                                  v-if="signedIn"></subscribe-button>

                                <button class="btn btn-default"
                                        v-show="authorize('isAdmin')"
                                        @click="toggleLock"
                                        v-text="locked ? 'Unlock' : 'Lock'">Lock</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection