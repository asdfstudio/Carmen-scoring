<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Judge;

class JudgePolicy
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
		
		
		public function show(User $user, Judge $judge)
		{
				return false;
		}
		
		
		public function update()
		{
				return false;
		}
		
}
