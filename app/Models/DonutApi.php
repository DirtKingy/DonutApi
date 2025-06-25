<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonutApi extends Model
{
    use HasFactory;

    protected $table = 'donuts';

    protected $fillable = [
        'name',
        'seal_of_approval',
        'price'
    ];
}
