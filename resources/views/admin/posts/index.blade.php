@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="container">
                        <div class="card-header row justify-content-between">
                            <div>{{ __('Posts') }}</div>
                            <div><a href="{{ route('admin.posts.create') }}">Create</a></div>
                        </div>
                        <div class="card-body">
                            @foreach ($posts as $post)
                                <div class="row justify-content-between border-bottom my-3">
                                    <span>{{ $post->id }}</span>
                                    <a href="{{ route('admin.posts.show', $post->id) }}">{{ $post->title }}</a>
                                    <span>{{ $post->category->name }}</span>
                                    @foreach ($post->tags as $tag)
                                        <span>{{ $tag->name }}</span>
                                    @endforeach
                                    <div>
                                        <a href="{{ route('admin.posts.edit', $post->id) }}">Edit</a>

                                        <div>
                                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">DELETE</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="/admin">Back</a>
    </div>
@endsection
