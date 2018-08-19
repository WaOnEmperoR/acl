<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSession extends Model
{
    protected $fillable = [
		'payment_session_name', 'payment_start_date', 'payment_finish_date'
	];

	protected $primaryKey = 'payment_session_id';
}
