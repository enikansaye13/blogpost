@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <a href="/posts/create" class="btn btn-primary mb-3">Create Post</a>
                        <h3>Your blog post</h3>
                        @if(!empty($posts))
                            <table class="table table-striped">
                                <tr>
                                    <th>Title</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                @foreach ($posts as $post)
                                    <tr>
                                        <th>{{ $post->title }}</th>
                                        <th><a href="/posts/{{$post->id}}/edit" class="btn btn-primary">Edit</a> </th>
                                        <th>
                                            {!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method'=>'POST']) !!}                    
                                            
                                            {{ Form::submit('Delete', ['class'=> 'btn btn-danger '])}}
                    
                                            {{ Form::hidden('_method', 'DELETE') }}
                    
                    
                                        {!! Form::close() !!}
                                        </th>
                                        {{-- <th><a href="/posts/delete/{{ $post->id }}" class="btn btn-danger">Delete</th> --}}
                                    </tr>
                                @endforeach
                            </table>

                        @else
                            <h3>You have no Posts.</h3>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
