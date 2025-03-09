<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'features',
        'max_projects',
        'max_users',
        'max_storage_gb',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'json',
        'max_projects' => 'integer',
        'max_users' => 'integer',
        'max_storage_gb' => 'integer',
        'is_active' => 'boolean',
    ];
}
