<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Round;
use App\Division;

use Auth;

class RoundPolicy extends BasePolicy
{
    use HandlesAuthorization;

    protected $isAdmin = false;
    protected $isOrgAdmin = false;
    protected $isOrgUser = false;
    protected $roundStatus;

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

		}

    public function show(User $user, $round)
		{
      return $this->isOrgUser;
		}


		public function create(User $user, $round, $division=false)
		{
      if($this->isOrgAdmin AND $division->status_slug == 'active')
      {
        return true;
      }
		}

    public function update(User $user, $round)
		{
      if($this->isOrgAdmin AND $round->status_slug() == 'inactive')
      {
        return true;
      }
		}

		public function destroy(User $user, $round)
		{
      if($this->isOrgAdmin AND $round->status_slug() == 'inactive')
      {
        return true;
      }
		}

    public function activateScoring(User $user, Round $round)
    {
      if($round->sources()->where('is_completed', false)->count() > 0)
      {
        return false;
      }

      if($this->isOrgAdmin AND $round->status_slug() == 'inactive')
      {
        return true;
      }
    }

    public function deactivateScoring(User $user, Round $round)
    {
      if($this->isOrgAdmin AND $round->status_slug() == 'active')
      {
        return true;
      }
    }

    public function reactivateScoring(User $user, Round $round)
    {
      if($this->isOrgAdmin AND $round->status_slug() == 'completed' AND $round->division->status_slug() != 'finalized')
      {
        return true;
      }
    }

    public function completeScoring(User $user, Round $round)
    {
      if($this->isOrgAdmin AND $round->status_slug() == 'active')
      {
        return true;
      }
    }

    public function setPerformanceOrder(User $user, $round)
		{
      if($this->isOrgAdmin AND $round->status_slug() == 'inactive')
      {
        return true;
      }
		}
}
