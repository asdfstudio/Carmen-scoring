<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Division;

use Auth;

class DivisionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    protected $isAdmin = false;
    protected $isOrgAdmin = false;
    protected $isOrgUser = false;
    protected $divisionStatus;


    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    public function show(User $user, $round)
		{
      return $this->isOrgUser;
		}


		public function create(User $user, $division, $extra = false)
		{
      if($this->isOrgAdmin)
      {
        return true;
      }
		}

    public function update(User $user, $division)
		{
      if($this->isOrgAdmin AND $division->status_slug() == 'inactive')
      {
        return true;
      }
		}

		public function destroy(User $user, $division)
		{
      if($this->isOrgAdmin AND $division->status_slug() == 'inactive')
      {
        return true;
      }
		}

    public function activateScoring(User $user, Division $division)
    {
      if($this->isOrgAdmin AND $division->status_slug() == 'inactive')
      {
        return true;
      }
    }

    public function deactivateScoring(User $user, Division $division)
    {
      if($this->isOrgAdmin AND $division->status_slug() == 'active')
      {
        return true;
      }
    }

    public function reactivateScoring(User $user, Division $division)
    {
      if($this->isOrgAdmin AND $division->status_slug() == 'completed')
      {
        return true;
      }
    }

    public function completeScoring(User $user, Division $division)
    {
      if($this->isOrgAdmin AND $division->status_slug() == 'active')
      {
        return true;
      }
    }


    public function importJudges(User $user, Division $division)
    {
      if($this->isOrgAdmin AND $division->status_slug() == 'inactive')
      {
        return true;
      }
    }

    public function createJudge(User $user, Division $division)
    {
      if($this->isOrgAdmin AND $division->status_slug() == 'inactive')
      {
        return true;
      }
    }
}
