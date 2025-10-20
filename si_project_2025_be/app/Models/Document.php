<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Document
 *
 * @property int $document_id
 * @property string $type
 * @property string $file_name
 * @property bool $is_verified
 * @property int $internships_id
 * @property Carbon $created_at
 *
 * @property Internship $internship
 *
 * @package App\Models
 */
class Document extends Model
{
	protected $table = 'documents';
	protected $primaryKey = 'document_id';
	public $timestamps = false;

	protected $casts = [
		'is_verified' => 'bool',
		'internships_id' => 'int'
	];

	protected $fillable = [
		'type',
		'file_name',
		'is_verified',
		'internships_id'
	];

	public function internship()
	{
		return $this->belongsTo(Internship::class, 'internships_id', 'internships_id');
	}
}
