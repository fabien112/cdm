<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoyenneTrimestres extends Model
{
    use HasFactory;

    protected $fillable = [

        'trimestre_id',
        'classe_id',
        'student_id',
        'valeur',
        'mention',
        'codeEtab',
        'session'
    ];

    public function student () {
        return $this->belongsTo(Student::class);
    }
}
