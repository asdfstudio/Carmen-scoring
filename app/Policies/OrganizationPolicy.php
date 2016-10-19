<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Organization;

class OrganizationPolicy
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


		public function create()
		{
				return false;
		}


		public function destroy()
		{
				return false;
		}


		public function show(User $user, Organization $organization)
		{
				return $user->organization_id === $organization->id;
		}


		public function update(User $user, Organization $organization)
		{
        if($user->isOrganizer() AND $user->organization_role == 'admin' AND $user->organization_id === $organization->id)
        {
          return true;
        }

        return false;
		}


		public function addPeople(User $user, Organization $organization)
		{
				return $user->organization_id === $organization->id;
		}


		public function addPlaces(User $user, Organization $organization)
		{
				return $user->organization_id === $organization->id;
		}


		public function addUsers(User $user, Organization $organization)
		{
				return $user->organization_id === $organization->id;
		}


}
