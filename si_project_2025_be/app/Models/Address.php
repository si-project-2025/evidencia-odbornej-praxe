<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @property int $address_id
 * @property string $country
 * @property string $city
 * @property string $zip_code
 * @property string $street
 * @property string $house_number
 *
 * @property Collection|Company[] $companies
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'address';
	protected $primaryKey = 'address_id';
	public $timestamps = false;

	protected $fillable = [
		'country',
		'city',
		'zip_code',
		'street',
		'house_number'
	];

	public function companies()
	{
		return $this->hasMany(Company::class, 'address_id', 'address_id');
	}

	public function users()
	{
		return $this->hasMany(User::class, 'address_id', 'address_id');
	}
}
