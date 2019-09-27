<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Person;

//use Auth;

class PersonPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


		public function before($user, $ability)
		{
			if($user->isAdmin())
			{
				return true;
			}
		}


    public function update(User $user, Person $person)
		{
      if($user->person->id == $person->id)
      {
        return true;
      }

      return false;
		}


}
