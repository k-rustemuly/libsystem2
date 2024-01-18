<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

trait CustomModelRealation
{

    /**
     * Define a one-to-many relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $instance
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customHasMany(Model $instance, $foreignKey = null, $localKey = null): HasMany
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasMany(
            $instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey
        );
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $instance
     * @param  string|null  $foreignKey
     * @param  string|null  $ownerKey
     * @param  string|null  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customBelongsTo(Model $instance, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        if (is_null($foreignKey)) {
            $foreignKey = Str::snake($relation).'_'.$instance->getKeyName();
        }

        $ownerKey = $ownerKey ?: $instance->getKeyName();

        return $this->newBelongsTo(
            $instance->newQuery(), $this, $foreignKey, $ownerKey, $relation
        );
    }
    //public function belongsTo(BookStorageType::class);

}
