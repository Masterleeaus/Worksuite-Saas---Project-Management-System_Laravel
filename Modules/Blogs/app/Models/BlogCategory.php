<?php

namespace Modules\Blogs\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Blogs\Traits\CompanyScoped;

class BlogCategory extends Model
{
    use SoftDeletes;
    use CompanyScoped;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'status',
        'language_id',
        'parent_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the posts for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\Modules\Blogs\app\Models\BlogPost, \Modules\Blogs\app\Models\BlogCategory>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(\Modules\Blogs\app\Models\BlogPost::class, 'category');
    }

}
