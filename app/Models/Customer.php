<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_document',
        'number_document',
        'name',
        'email',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
