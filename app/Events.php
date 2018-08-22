<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $fillable = [
		'event_name', 'event_place', 'event_start', 'event_finish', 'event_type_id', 'user_id',
	];

	protected $primaryKey = 'event_id';
}
