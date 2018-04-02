@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :data-replies-count="{{ $thread->replies_count }}" :data-locked="{{ $thread->locked }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-8 col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">

                                <img src="{{ $thread->creator->avatar_path }}"
                                     alt="{{ $thread->creator->name }}"
                                     width="50" height="50"
                                     class="mr-1">
                                
                                <span class="flex">
                                    <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                                    posted: <b>{{ $thread->title }}</b>
                                </span>

                                @can('update', $thread)
                                    <form action="{{ $thread->path() }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-link">Delete Thread</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="panel-body">
                            {{ $thread->body }}
                        </div>
                    </div>

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
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                                <button class="btn btn-default" v-show="authorize('isAdmin') && ! locked" @click="locked = true">Lock</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection