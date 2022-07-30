@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Blogs Page</h1>

        {{-- alert --}}
        @if (session('alert'))
            @php
                $alert = session('alert')
            @endphp
            <div>
                <div class="alert alert-{{ $alert['type'] }}" role="alert">
                    {{ $alert['message'] }}
                </div>
            </div>
        @endif            

        {{-- button create blog --}}
        <div class="mb-3">
            <a href="/blog/form" class="text-decoration-none btn btn-primary">add new article</a>
        </div>

        {{-- blogs list --}}
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-md-6 col-lg-4">
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
                            <p class="card-text">
                                <small>{{ $post->category->name }}</small> ·
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </p>
                            <div>
                                <a href="/blog/formUpdate/{{ $post->id }}" class="btn btn-primary">Edit</a>
                                <form action="/blog/delete/{{ $post->id }}" class="d-inline-block" method="POST">
                                    @csrf
                                    <button class="btn btn-danger deleteButton">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>    
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script>
        window.onload = () => {
            const deleteButton = [...document.getElementsByClassName('deleteButton')]
        
            deleteButton.forEach(button => {
                button.addEventListener('click', (e) => {
                    if( !confirm('Apakah anda ingin menghapus artikel ini ?')){
                        e.preventDefault()
                    }
                })
            })
        }
    </script>
@endsection