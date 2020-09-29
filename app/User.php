<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;
    //use RestrictsOrganization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'person_id', 'organization_id', 'organization_role', 'is_admin','_redirect','voted','petl_point'
    ];

  /**
   * Voted campaign ID list in array
   * @var string[]
   */
    protected $casts = [
      'voted' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByEmail', function(Builder $builder) {
					$builder->orderBy('email', 'ASC');
				});
    }


		public function organization()
		{
      //if(!empty(1)){
      //  $current_url = url()->current();
      //}

      // Automatically change an admin's "organization_id" to the current organization.
      // This avoids errors when admins jump from one org to another via direct URL
      // instead of navigating through the web interface.
      //if(Auth::user()->isAdmin()){
        //Auth::user()->organization_id = $this->getKey();
      //}
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
        return $this->person->isJudge();
      }

      return false;
		}

		public function isAdmin()
		{
			return $this->is_admin;
		}

		public function isSuperAdmin($user_self = null)
		{
      // A user is a superadmin if they are listed as such in /config/auth.php.  They can also
      // be considered a superadmin in the context editing their own information, so a user
      // object or ID can be passed as an argument for comparison.  If the argument turns out
      // to be this user, then this user is a superadmin in that context.
      $user_self_id = is_object($user_self) && get_class($user_self) === 'App\User' ? $user_self->id : $user_self;
			return in_array($this->id, config('auth.superadmins')) || ($this->isAdmin() && $this->id === $user_self_id);
		}

    public function getIsAdminTextAttribute()
		{
			return $this->is_admin ? 'Admin' : '';
		}


    public function getDisplayNameAttribute()
    {
      if($this->person)
      {
        return $this->person->full_name;
      }

      return $this->email;
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
