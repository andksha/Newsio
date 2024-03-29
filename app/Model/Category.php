<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNestedSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNestedSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNestedSet query()
 */
final class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'products_to_categories',
            'category_id',
            'product_id',
        );
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'category_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'categories_closures',
            'parent_id',
            'child_id'
        );
    }
}