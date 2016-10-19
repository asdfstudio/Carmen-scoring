<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Competition;

class CompetitionPolicy
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


		public function showAll()
		{
				return false;
		}


		public function create(User $user)
		{
        if($user->isOrganizer() AND $user->organization_role == 'admin')
        {
          return true;
        }

        return false;
		}

    public function update(User $user, Competition $competition)
		{

        if($user->isOrganizer() AND $user->organization_role == 'admin' AND $user->organization_id === $competition->organization_id)
        {
          return true;
        }

        return false;
		}


    public function clone(User $user, Competition $competition)
		{

        if($user->isOrganizer() AND $user->organization_role == 'admin' AND $user->organization_id === $competition->organization_id)
        {
          return true;
        }

        return false;
		}


		public function destroy(User $user, Competition $competition)
		{
      if($user->isOrganizer() AND $user->organization_role == 'admin' AND $user->organization_id === $competition->organization_id)
      {
        return true;
      }

      return false;
		}


		public function show(User $user, Competition $competition)
		{
      if($user->isOrganizer() AND $user->organization_id === $competition->organization_id)
      {
        return true;
      }

      return false;
		}



}
