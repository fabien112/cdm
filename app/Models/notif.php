<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notif extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu',
        'titre',
        'parent_id',
        'statut'

    ];
}
