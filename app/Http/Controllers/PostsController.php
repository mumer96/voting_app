<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Post;
use App\Http\Requests\StorePostRequest;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId = null)
    {
        $posts = Post::getPosts($userId ? decrypt($userId) : null);
        return view('user.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.add_post'); // or whatever your path is
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        // Create a new post
        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'user_id' => auth()->id()
        ]);
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        return view('user.edit_post', compact('post')); // or whatever your path is
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, string $id)
    {
        $post = Post::findOrFail($id);
        // Check if it's a "hide" action
        if ($request->has('is_hidden')) {
            $post->is_hidden = $request->input('is_hidden');
            $post->save();

            return redirect()->route('posts.index')->with('success', 'Post hidden successfully.');
        }
        
        // ensure the authenticated user owns the post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update post
        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'type' => $request->input('type'),
        ]);

        return redirect()->route('my.posts', ['userId' => encrypt(auth()->id())])
        ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $post->votes()->delete(); // delete votes first
        $post->delete();          // then delete post

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }

    public function addVote(Post $post)
    {
        $user = auth()->user();

        // Prevent multiple votes from the same user
        $alreadyVoted = $post->votes()->where('user_id', $user->id)->exists();

        if ($alreadyVoted) {
            return redirect()->back()->with('error', 'You have already voted for this post.');
        }
        // Prevent user from voting on their own post
        if ($post->user_id === $user->id) {
            return redirect()->back()->with('error', 'You cannot vote on your own post.');
        }

        $post->votes()->create([
            'user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Vote added successfully.');
    }

    public function topPosts()
    {
        $posts = Post::getPosts(null, true); // null for userId and true for topPosts check

            return view('user.index', compact('posts'));
        }
}
