<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'type'];

    /**
     * Get all posts associated with this platform.
     * This relationship includes the platform_status in the pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_platforms')
            ->withPivot('platform_status')
            ->withTimestamps();
    }

    /**
     * Get all users associated with this platform.
     * This relationship includes the active status in the pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Platform::class, 'user_platform')
            ->withPivot('active')
            ->withTimestamps();
    }
}
