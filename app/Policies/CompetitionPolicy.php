<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Competition;

use Auth;

class CompetitionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

		public function before($user, $ability)
		{
			/*if($user->isAdmin())
			{
				return true;
			}*/
		}

    public function update(User $user, $competition)
		{
      if($user->isAdmin())
			{
				return true;
			}
      elseif($this->isOrgAdmin AND $competition->is_completed == false)
      {
        return true;
      }
		}

    public function createDivision(User $user, $competition)
		{
      if($user->isAdmin())
			{
				return true;
			}
      elseif($this->isOrgAdmin AND $competition->is_completed == false)
      {
        return true;
      }
		}


    public function replicate(User $user, Competition $competition)
		{
      if($this->isOrgAdmin)
      {
        return true;
      }
		}

    public function closeCompetition(User $user, Competition $competition)
    {
      if($this->isOrgAdmin AND $competition->is_completed == false)
      {
        return true;
      }
    }

    public function activateCompetition(User $user, Competition $competition)
    {
      if($this->isOrgAdmin AND $competition->is_completed)
      {
        return true;
      }
    }

    public function archiveCompetition(User $user, Competition $competition)
    {
      if($this->isOrgAdmin AND $competition->is_completed AND $competition->is_archived == false)
      {
        return true;
      }
    }


}
