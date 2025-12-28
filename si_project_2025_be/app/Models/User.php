<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property int $users_id
 * @property string $email
 * @property string $password
 * @property string|null $alt_email
 * @property string $name
 * @property string $surname
 * @property string|null $study_program
 * @property string|null $phone_number
 * @property Carbon $created_at
 * @property Carbon|null $last_login
 * @property int $role_id
 * @property int $address_id
 *
 * @property Address $address
 * @property Role $role
 * @property Collection|Internship[] $internships
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'users_id';
    public $timestamps = false;

    protected $casts = [
        'last_login' => 'datetime',
        'role_id' => 'int',
        'address_id' => 'int',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = [
        'email',
        'password',
        'alt_email',
        'name',
        'surname',
        'study_program',
        'phone_number',
        'last_login',
        'role_id',
        'address_id',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function internships()
    {
        return $this->hasMany(Internship::class, 'users_id', 'users_id');
    }
}
