<?php

namespace Modules\Blogs\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blogs\Traits\CompanyScoped;

/**
 * @property string $image
 * @property string $comment_date
 */
class BlogComment extends Model
{
    use SoftDeletes;
    use CompanyScoped;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'post_id',
        'name',
        'email',
        'image',
        'comment',
        'comment_date',
        'user_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }
}
