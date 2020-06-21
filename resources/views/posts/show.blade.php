@extends('layouts.app')

    @section('content')
    <a href="/posts" class="btn btn-primary offset-1">Go Back</a>
    <div class="row offset-1">
        <div class="col-md-10 pt-3" >
            <img src="/storage/cover_image/{{ $post->cover_image }}" style="width: 100%">

        </div>
        <div class="col-md-10">
             <h1>{{$post->title}}</h1>
             <p>{{$post->body}}</p>
             <hr>    
            <small>written on {{$post->created_at}}</small>
    

        </div>

    </div>
        

        <hr>
        @if (!Auth::guest())
            @if(Auth::user()->id == $post->user_id)
    
                <div class="display-flex">


                    {!! Form::open(['action' => ['PostsController@destroy', $post->id], 'method'=>'POST']) !!}

                        <a href="/posts/{{ $post->id }}/edit" class="btn btn-primary "> Edit Post</a>

                        
                        {{ Form::submit('Delete', ['class'=> 'btn btn-danger '])}}

                        {{ Form::hidden('_method', 'DELETE') }}


                    {!! Form::close() !!}

                </div>
            @endif

        @endif

       
    @endsection