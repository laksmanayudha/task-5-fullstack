@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="w-75 mx-auto">

            {{-- errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            
            {{-- form --}}
            <div class="">
                <form action="/blog/create" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 d-flex justify-content-between">
                        <div>
                            <h1>Blog Form</h1>
                        </div>
                        <div>
                            <a href="" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary">Create</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Blog title" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" name="category_id">
                            <option selected>Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" placeholder="Blog content here..."></textarea>
                    </div>
                    <div class="mb-3" style="max-width: 500px;">
                        <label for="file" class="form-label">Upload image</label>
                        <input class="form-control" type="file" name="image" id="file">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection