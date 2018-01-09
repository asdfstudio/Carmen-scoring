<?php

namespace App;

use App\Scopes\OrderByNameScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penalty extends Model
{
		use SoftDeletes;
		use RestrictsOrganization;

		protected $dates = ['deleted_at'];

		protected $fillable = ['name', 'description', 'amount', 'apply_per_judge', 'organization_id'];

		protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByNameScope);
    }


		public function organization()
		{
			return $this->belongsTo('App\Organization');
		}

    public function divisions()
		{
			return $this->belongsToMany('App\Division');
		}

		public function choirs()
		{
			return $this->belongsToMany('App\Choir');
		}

		public function rounds()
		{
			return $this->belongsToMany('App\Round', 'choir_penalty');
		}


		public function getAmountAttribute($value)
		{
			return substr($value, 0, -1);
		}


		public function apply_per_judge()
		{
			return $this->apply_per_judge ? 'Yes' : 'No';
		}

		public function apply_per_judge_text()
		{
			return $this->apply_per_judge ? 'applied per judge' : 'applied to total';
		}

		public function amount_text()
		{
			return $this->amount .' point penalty ' . $this->apply_per_judge_text();
		}
}
