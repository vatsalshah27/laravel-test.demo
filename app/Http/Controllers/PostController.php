<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = null;
        //$posts = Post::all(); //fetch all blog posts from DB
        if(Auth::check()){
            $user_id = auth()->user()->id;
            $user = User::find($user_id);
            $posts = $user->blogs()->get();
        }
        return view('posts.index', [
            'posts' => $posts,
        ]); //returns the view with posts
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
        ##Declare variable
        $post_id = 0;
        $input = array();
        #Validate form data
        $rules = [
            'title' => 'required|unique:posts',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:2048',
            'published_at' => 'required', 
            //'is_published' => 'required'
        ];
    
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'unique' => 'The :attribute is already exists.',
            'image.max' => 'The :attribute has maximum 2 MB file size are allowed.',
            'mimes' => 'The :attribute should be image file with jpeg,jpg,png extensons are allows.',
        ];
    
        $this->validate($request, $rules, $customMessages);

        $post_id = Post::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'published_at' => $request->input('published_at'),
                'is_published' => 1,
                'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
                'user_id' => auth()->user()->id
            ])->id;
        ##Check If successfully data is inserted in a database 
        if($post_id > 0){
            #Image file upload in posts folder or not 
            if ($file = $request->file('image'))
            {
                $img = time().str_replace(' ', '', $file->getClientOriginalName());
                $file_name = $file->move('assets/images/posts',$img);
                if($file_name){
                    $post = Post::findOrFail($post_id);
                    //Get post data  
                    if($post) {
                        $post->image_path = $img;
                        $post->save();
                        #Redirect to post list page
                        return redirect()
                        ->route('posts.index')
                        ->with('success', 'Post information added successfully. Image uploaded and its information saved successfully.');
                    }else{
                        #Redirect to Post list page
                        return redirect()
                        ->route('posts.index')
                        ->with('error', 'Post information added successfully. Image uploaded successfully but its information not updated successfully.');
                    }
                }else{
                    #Redirect to Post list page
                    return redirect()
                    ->route('posts.index')
                    ->with('error', 'Post information added successfully. Image not uploaded.');
                }
            }else{
                #Redirect to Post list page
                return redirect()
                ->route('posts.create')
                ->with('error', 'Image file is missing. Please upload Post image file.');
            }
            
        }else{
            #Redirect to post list page
            return redirect()
            ->route('posts.create')
            ->with('error', 'Something went wrong. Please try after sometimes.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post','post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {   
        return view('posts.edit', compact('post','post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        
        ##Declare variable
        $post_id = $post->id;
        #Validate form data
        $rules = [
            'title' => 'required|unique:posts,title,' . $post_id,
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:2048',
            'published_at' => 'required', 
            'is_published' => 'required'
        ];
    
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'unique' => 'The :attribute is already exists.',
            'image.max' => 'The :attribute has maximum 2 MB file size are allowed.',
            'mimes' => 'The :attribute should be image file with jpeg,jpg,png extensons are allows.',
        ];
    
        $this->validate($request, $rules, $customMessages);
        #Get post details by post id
        if($post_id > 0){
            $data = Post::findOrFail($post_id);
        }
        #Image Upload
        if ($file = $request->file('image'))
        {
            $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/posts',$name);
            if(!empty($data) && isset($data->image_path) && $data->image_path != null)
            {
                if (file_exists(public_path().'/assets/images/posts/'.$data->image_path)) {
                    unlink(public_path().'/assets/images/posts/'.$data->image_path);
                }
            }
            $input['image_path'] = $name;
        }else{
            $input['image_path'] = $data->image_path;
        }
        
        #Update post details into posts table.
        Post::where('id', $post_id)
            ->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'published_at' => $request->input('published_at'),
                'is_published' => $request->input('is_published'),
                'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
                'user_id' => auth()->user()->id
            ]);
        #Redirect to post list page with message
        return redirect()
            ->route('posts.index')
            ->with('success', 'Post details updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post_id = $post->id;
        $data = array();
        #Get post details by post id
        if($post_id > 0){
            $data = Post::findOrFail($post_id);
        }

        if(!empty($data) && isset($data->image_path) && $data->image_path != null)
        {
            if (file_exists(public_path().'/assets/images/posts/'.$data->image_path)) {
                unlink(public_path().'/assets/images/posts/'.$data->image_path);
            }
        }

        $post->delete();
        return redirect()
            ->route('posts.index')
            ->with('success', 'Post has been deleted successfully.');
    }
}
