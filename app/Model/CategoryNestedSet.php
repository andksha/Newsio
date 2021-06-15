<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property int $left
 * @property int $right
 * @property int $depth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNestedSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNestedSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryNestedSet query()
 */
final class CategoryNestedSet extends Model
{
    protected $table = 'categories1';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'depth',
        'left',
        'right'
    ];

//    public function children(): HasMany
//    {
//        return $this->hasMany(Category::class, 'parent_id');
//    }

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
}