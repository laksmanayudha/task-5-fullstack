@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                    
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
                
                {{-- category form --}}
                <div>
                    <h3>Category Form</h3>
                    <form action="/category/create" method="POST" id="categoryForm">
                        @csrf
                        <div>
                            <input type="text" name="name" class="form-control" placeholder="category name..." id="categoryInput">
                            <button class="btn btn-primary w-100 mt-3" id="createButton">Create</button>
                            <button class="btn btn-secondary w-100 mt-3 d-none" type="reset" id="cancelButton">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="d-md-none mt-4">
            <div class="col-md-8 px-5">
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button class="btn btn-success editButton" value="{{ $category->id . '-' . $category->name }}">Edit</button>
                                    <form action="/category/delete/{{ $category->id }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const categoryForm = document.getElementById('categoryForm')
            const categoryInput = document.getElementById('categoryInput')
            const editButton = [...document.getElementsByClassName('editButton')]
            const cancelButton = document.getElementById('cancelButton')
            const createButton = document.getElementById('createButton')
            const categoryUrl = {
                insert: '/category/create/',
                update: '/category/update/'
            }

            const reset = () => {
                categoryInput.value = ""
                categoryForm.action = categoryUrl.insert
                cancelButton.classList.add('d-none')
                createButton.innerText = 'Create'
            }

            cancelButton.addEventListener('click', function(){
                reset()
            })

            editButton.forEach(button => {
                button.addEventListener('click', function(event){
                    const [id, name] = event.target.value.split('-')
                    categoryInput.value = name
                    categoryForm.action = categoryUrl.update + id
                    cancelButton.classList.remove('d-none')
                    createButton.innerText = 'Update'
                })
            })        

            categoryForm.action = categoryUrl.insert
        })

    </script>
@endsection