<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 *
 * @property int $company_id
 * @property string $name
 * @property int $address_id
 *
 * @property Address $address
 * @property Collection|Internship[] $internships
 *
 * @package App\Models
 */
class Company extends Model
{
	protected $table = 'companies';
	protected $primaryKey = 'company_id';
	public $timestamps = false;

	protected $casts = [
		'address_id' => 'int'
	];

	protected $fillable = [
		'name',
		'address_id'
	];

	public function address()
	{
		return $this->belongsTo(Address::class, 'address_id', 'address_id');
	}

	public function internships()
	{
		return $this->hasMany(Internship::class, 'company_id', 'company_id');
	}
}
