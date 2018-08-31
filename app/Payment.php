<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $fillable = [
			'payment_submitted', 'payment_verified', 'payment_verifier', 'img_file_proof', 'text_file_proof', 'user_id', 'payment_session_id', 'payment_type_id', 'user_id'
		];
	
	protected function getKeyForSaveQuery()
	{		
		$primaryKeyForSaveQuery = array(count($this->primaryKey));
		
		foreach ($this->primaryKey as $i => $pKey) {
			$primaryKeyForSaveQuery[$i] = isset($this->original[$this->getKeyName()[$i]])
			            ? $this->original[$this->getKeyName()[$i]]
			            : $this->getAttribute($this->getKeyName()[$i]);
		}
		
		return $primaryKeyForSaveQuery;		
    }
    
	protected function setKeysForSaveQuery(Builder $query)
	    {
		$query
		            ->where('payment_session_id', '=', $this->getAttribute('payment_session_id'))
		            ->where('payment_type_id', '=', $this->getAttribute('payment_type_id'))
		            ->where('user_id', '=', $this->getAttribute('user_id'));
		return $query;
    }
        	
	protected $primaryKey = ['payment_session_id', 'payment_type_id', 'user_id'];
	
	public $incrementing = false;
}
