<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterEvent extends Model
{
    protected $fillable = [
		'type_event_name'
	];

	protected $primaryKey = 'master_event_id';
}
