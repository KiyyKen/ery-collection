<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'size',
        'price',
        'stock',
        'unit',
        'predicted_label',
        'last_classified_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'last_classified_at' => 'datetime',
        ];
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(Distribution::class);
    }
}
