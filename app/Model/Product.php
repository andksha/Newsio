<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $status
 * @property int $user_id
 * @property int|null $manufacturer_id
 * @property int|null $warehouse_id
 * @property string $title
 * @property string $basic_info
 * @property string $short_description
 * @property string $description
 * @property string $capacity
 * @property string $transport_package
 * @property bool $customization
 * @property string $payment_terms
 * @property string $delivery
 * @property string $min_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\CategoryNestedSet[] $categories
 * @property-read int|null $categories_count
 */
class Product extends Model
{
    protected $table = 'products';

    public const STATUS_DRAFT = 0;
    public const STATUS_NOT_APPROVED = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_PUBLISHED = 3;

    public function userId(): int
    {
        return $this->user_id;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            CategoryNestedSet::class,
            'products_to_categories',
            'product_id',
            'category_id',
        );
    }

    public function customValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'product_id');
    }
}
