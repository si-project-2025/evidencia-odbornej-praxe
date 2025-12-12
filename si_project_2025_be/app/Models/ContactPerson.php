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
        'company_id',
    ];

    /**
     * Získa prax, ku ktorej patrí táto kontaktná osoba
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
