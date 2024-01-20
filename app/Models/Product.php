<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'image',
        'enterprise_id'
    ];

    protected $casts = [
        'image' => 'array',
    ];

    public function enterprises() {
        return $this->belongsTo(Enterprise::class);
    }
}
