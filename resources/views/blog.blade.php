@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-3">
            <a href="/blog/form" class="text-decoration-none btn btn-primary">add new article</a>
        </div>
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <a href="">
                            @isset($post->image)
                                <img src="{{ $post->image }}" class="card-img-top" alt="...">
                            @endisset
                            @empty($post->image)
                                <img src="https://dummyimage.com/400x400/b0b0b0/000000&text=No+image" class="card-img-top" alt="...">
                            @endempty
                        </a>
                        <div class="card-body">
                            <a href="" class="text-decoration-none text-black">
                                <h5 class="card-title">{{ $post->title }}</h5>
                            </a>
                            <p class="card-text">{{ $post->excerpt() }}</p>
                            <p class="card-text"><small class="text-muted">{{ $post->created_at->diffForHumans() }}</small></p>
                        </div>
                    </div>
                </div>    
            @endforeach
        </div>
    </div>
@endsection