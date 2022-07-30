@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Home Page</h1>
    <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card text-bg-dark">
            <div class="card-header">
                <h5 class="card-title">Blogs</h5>
            </div>
            <div class="card-body">
              <p class="card-text">See all blogs and create your own blog</p>
              <a href="/blogs" class="btn btn-primary">Go to blogs</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3 ">
          <div class="card">
            <div class="card-header">
                <h5 class="card-title">Categories</h5>
            </div>
            <div class="card-body">
              <p class="card-text">Find available categories and create new categories</p>
              <a href="/categories" class="btn btn-primary">Go to categories</a>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
