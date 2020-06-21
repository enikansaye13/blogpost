<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Post;


class PostsController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'DESC')->get();
        return view('posts.index')->withposts($posts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required',
            'cover_image'=> 'image|nullable|max:1999'

        ]);
        //handling the file upload
            if($request->hasFile('cover_image'))
            {
                //get file name with extention
                $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
                //get just file
                $filename = pathinfo($filenamewithExt, PATHINFO_FILENAME);
                //get just the extension
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                //file to score
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                //upload image
                $path= $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
            }else{
                $fileNameToStore = 'no image.png';
            }

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'post created');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->withpost($post);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'unauthorised page.');
        }
        return view('posts.edit')->withpost($post);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required',
            'cover_image'=> 'image|nullable|max:1999'

            ]);
            //handling the file upload
                if($request->hasFile('cover_image'))
                {
                    //get file name with extention
                    $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
                    //get just file
                    $filename = pathinfo($filenamewithExt, PATHINFO_FILENAME);
                    //get just the extension
                    $extension = $request->file('cover_image')->getClientOriginalExtension();
                    //file to score
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    //upload image
                    $path= $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
               
                }        
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'post updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'unauthorised page.');
        }

        if($post->cover_image != 'no image.png'){
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post->delete();

        return redirect('/posts')->with('success', 'post Removed');

    }
}
