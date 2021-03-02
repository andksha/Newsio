<?php

namespace Newsio\Model;

/**
 * Newsio\Model\Operation
 *
 * @property int $id
 * @property int $operation_type
 * @property int $model_type
 * @property int $model_id
 * @property mixed $model
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereUpdatedAt($value)
 */
final class Operation extends BaseModel
{
    protected $table = 'history_of_operations';

    protected $fillable = ['model'];

    protected $visible = [
        'operation_type',
        'model_type',
        'model_id',
        'model',
    ];

    // OT - operation type
    public const OT_CREATED = 0;

    // MT - model type
    public const MT_EVENT = 0;

}