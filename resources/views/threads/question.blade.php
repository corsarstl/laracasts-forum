{{--Editing the question--}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="panel-footer">
        <div class="level">
            <button class="bnt btn-xs btn-primary level-item" @click="update" v-show="editing">Update</button>
            <button class="bnt btn-xs level-item" @click="resetForm">Cancel</button>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{--Viewing the question--}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">

            <img src="{{ $thread->creator->avatar_path }}"
                 alt="{{ $thread->creator->name }}"
                 width="50" height="50"
                 class="mr-1">

            <span class="flex">
                <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                posted: <b v-text="title"></b>
            </span>
        </div>
    </div>

    <div class="panel-body" v-text="body"></div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="bnt btn-xs" @click="editing = true">Edit</button>
    </div>
</div>