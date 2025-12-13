<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Internship
 *
 * @property int $internships_id
 * @property string $semester
 * @property int $year
 * @property bool $is_paid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property int $users_id
 * @property int $company_id
 * @property int $status_id
 * @property int $garant_id
 *
 * @property Company $company
 * @property Status $status
 * @property User $user
 * @property User $garant
 * @property Collection|Document[] $documents
 * @property Collection|ContactPerson[] $contactPersons
 *
 * @package App\Models
 */

class Internship extends Model
{
    protected $table = 'internships';
    protected $primaryKey = 'internships_id';

    protected $casts = [
        'year' => 'int',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'users_id' => 'int',
        'company_id' => 'int',
        'status_id' => 'int',
        'garant_id' => 'int',
        'contact_person_id' => 'int',
        'is_paid' => 'boolean',
    ];

    protected $fillable = [
        'semester',
        'year',
        'start_at',
        'end_at',
        'users_id',
        'company_id',
        'status_id',
        'garant_id',
        'contact_person_id',
        'is_paid',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    public function garant()
    {
        return $this->belongsTo(User::class, 'garant_id', 'users_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'internships_id', 'internships_id');
    }

    public function contactPerson()
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id', 'id');
    }
}
