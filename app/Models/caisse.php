<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class caisse extends Model
{
    use HasFactory;

    protected $fillable = [

        'nom',
        'prenom',
        'role',
        'salaire',
        'user_id',
        'matricule',
        'tel',
        'email',
        'sexe',
        'codeEtab',
        'session',

    ];
}
