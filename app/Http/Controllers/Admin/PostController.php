<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Post;
use App\Category;
use App\Tag;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::all();

        return view('admin.posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create',compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title'=> 'required|max:250',
            'content'=>'required|min:5',
            'category_id'=>'exists:categories,id',
            'tags'=>'exists:tags,id'
        ],
        // messaggi di errori nel caso condizioni sopra nn verificate
        [
            'title.required'=>'Titolo deve essere valorizzato',
            'title.max'=>'Hai superato i 250 caratteri',
            'content.min'=>'Non hai inserito sufficienti caratteri',
            'category_id.exist'=>'Categoria selezionata non esiste',
            'tags'=>'Tag non esiste'
        ]);
        $postData = $request->all();
        $newPost = new Post();
        $newPost->fill($postData);

        $newPost->slug= Post::convertToSlug($newPost->title);
        // add tags
        $newPost->save();
        if(array_key_exists('tags', $postData)){
            $newPost->tags()->sync($postData['tags']);
        }
        return redirect()->route('admin.posts.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        if(!$post){
            abort(404);
        }
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        if(!$post){
            abort(404);
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit',compact('post','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        // simil store
        $request->validate([
            'title'=> 'required|max:250',
            'content'=>'required|min:5',
            'category_id'=>'exists:categories,id',
            'tags'=>'exists:tags,id'
        ],
        // messaggi di errori nel caso condizioni sopra nn verificate
        [
            'title.required'=>'Titolo deve essere valorizzato',
            'title.max'=>'Hai superato i 250 caratteri',
            'content.min'=>'Non hai inserito sufficienti caratteri',
            'category_id.exist'=>'Categoria selezionata non esiste',
            'tags'=>'Tag non esiste'
        ]);

        $postData = $request->all();
        $post->fill($postData);

        $post->slug= Post::convertToSlug($post->title);
        if(array_key_exists('tags', $postData)){
            $newPost->tags()->sync($postData['tags']);
        }else{
            $post->tags()->sync([]);
        }
        $post->update();
        return redirect()->route('admin.posts.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        if($post){
            $post->tags()->sync([]);
            $post->delete();
        }

        return redirect()->route('admin.posts.index');
    }
}
