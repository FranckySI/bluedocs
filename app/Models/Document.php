<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    // Autorise Laravel à remplir ces colonnes d'un coup
    protected $fillable =[
        'title',
        'slug',
        'content',
        'user_name',
    ];

}
