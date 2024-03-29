<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'description',
        'image',
        'owner',
        'products',
        'lat',
        'long',
    ];

    protected $casts = [
        'image' => 'json',
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner');
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
