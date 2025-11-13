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
 * @property int $hours_total
 * @property int $year
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $end_at
 * @property int $users_id
 * @property int $company_id
 * @property int $status_id
 *
 * @property Company $company
 * @property Status $status
 * @property User $user
 * @property Collection|Document[] $documents
 *
 * @package App\Models
 */
class Internship extends Model
{
    protected $table = 'internships';
    protected $primaryKey = 'internships_id';

    protected $casts = [
        'hours_total' => 'int',
        'year' => 'int',
        'end_at' => 'datetime',
        'users_id' => 'int',
        'company_id' => 'int',
        'status_id' => 'int',
        'garant_id' => 'int',
    ];

    protected $fillable = [
        'semester',
        'hours_total',
        'year',
        'end_at',
        'users_id',
        'company_id',
        'status_id',
        'garant_id',
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

    public function contactPersons()
    {
        return $this->hasMany(ContactPerson::class, 'internships_id', 'internships_id');
    }
}
