<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;

    protected $table = 'contact_persons';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'internships_id',
    ];

    /**
     * Získa prax, ku ktorej patrí táto kontaktná osoba
     */
    public function internship()
    {
        return $this->belongsTo(Internship::class, 'internships_id', 'internships_id');
    }
}
