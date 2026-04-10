<?php

namespace Modules\ServiceManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\ServiceManagement\Traits\CompanyScoped;

class Tag extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = ['tag'];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\TagFactory::new();
    }
}