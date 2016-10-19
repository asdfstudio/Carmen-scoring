<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['address', 'address_2', 'city', 'state', 'postal_code'];

    //
		public function subject()
		{
			return $this->morphTo();
		}

    public function city_state()
    {
      $str = false;

      if($this->city AND $this->state)
      {
        $str = $this->city . ', ' . $this->state;
      }
      elseif($this->city)
      {
        $str = $this->city;
      }
      elseif($this->state)
      {
        $str = $this->state;
      }

      return $str;
    }
}
