<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $fillable = [
		'payment_name'
	];

	protected $primaryKey = 'payment_type_id';
}
