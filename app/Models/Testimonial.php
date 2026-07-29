<?php

namespace App\Models;

class Testimonial extends BaseModel
{
    protected $fillable = [
        'name',
        'designation',
        'review',
        'rating',
        'image',
        'display_order',
        'card_class',
        'status',
    ];
}
