<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'user_id'
    ];

    public function user()  {
        return $this->belongsTo(User::class);
    }
    public function votes() {
        return $this->hasMany(Vote::class);
    }
    /**
     * Get the user that owns the post.
     */
    // Scope to get posts by user with vote count
    public function scopeGetPosts($query, $userId, $topPosts = null)
    {
        // If userId is provided, fetch posts for that specific user. Otherwise, exclude the authenticated user's posts.
        return $query->with('votes')
            ->withCount('votes')
            ->when($userId, function ($query, $userId) {
                return $query->where('user_id', $userId); // Condition to fetch posts for the provided userId
            }, function ($query) {
                return $query->where('user_id', '!=', auth()->id()); // Condition to fetch posts excluding the authenticated user's posts
            })
            ->when($topPosts, function ($query) {
                return $query->orderByDesc('votes_count')->take(10); // Order by the vote count in descending order
            }, function ($query) {
                return $query->orderByDesc('created_at'); // Order by the vote count in descending order
            })
            ->when(!auth()->user()->is_admin, function ($query) {
                return $query->where('is_hidden', 0); // Only show visible posts for non-admins
            })
        ->get();
    }
}
