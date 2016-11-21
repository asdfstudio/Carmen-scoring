<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //use RestrictsOrganization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'person_id', 'organization_id', 'organization_role','is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


		public function organization()
		{
			return $this->belongsTo('App\Organization');
		}

		public function person()
		{
			return $this->belongsTo('App\Person');
		}


		public function isOrganizer()
		{
			return $this->organization_id;
		}

		public function isJudge()
		{
      if($this->person)
      {
        return $this->person->person_type == 'App\Judge';
      }

      return false;
		}

		public function isAdmin()
		{
			return $this->is_admin;
		}

    //public function organization_role()
    //{
      //if(!empty($this->organization_role))
        //return ucfirst($this->organization_role);
      //else {
      //  return false;
      //}
    //}
}
