<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 *
 * @property int $status_id
 * @property string $type
 *
 * @property Collection|Internship[] $internships
 *
 * @package App\Models
 */
class Status extends Model
{
	protected $table = 'status';
	protected $primaryKey = 'status_id';
	public $timestamps = false;

	protected $fillable = [
		'type'
	];

	public function internships()
	{
		return $this->hasMany(Internship::class, 'status_id', 'status_id');
	}
}
