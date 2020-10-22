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


		public function create($competition=false)
		{
      if($this->isOrgAdmin AND $competition)
      {
        return true;
      }
		}

    public function update(User $user, $round)
		{
      if($this->isOrgAdmin AND $round->status_slug() != 'published')
      {
        return true;
      }
		}

		public function destroy(User $user, $round)
		{
      if($this->isOrgAdmin AND $round->status_slug() != 'published')
      {
        return true;
      }
		}

    public function importJudges(User $user, Round $round)
    {
        if($this->isOrgAdmin AND $round->status_slug() == 'active' AND $round->competition->is_completed == false)
        {
            return true;
        }
    }

    public function createJudge(User $user, Round $round)
    {
        if($this->isOrgAdmin AND $round->status_slug() == 'active' AND $round->competition->is_completed == false)
        {
            return true;
        }
    }

    public function updateJudge(User $user, Round $round)
    {
        if($this->isOrgAdmin AND $round->status_slug() == 'active' AND $round->competition->is_completed == false)
        {
            return true;
        }
    }

    public function removeJudge(User $user, Round $round)
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
