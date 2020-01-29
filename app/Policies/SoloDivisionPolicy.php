<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\SoloDivision;

use Auth;

class SoloDivisionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    protected $isAdmin = false;
    protected $isOrgAdmin = false;
    protected $isOrgUser = false;
    protected $soloSoloDivisionStatus;


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


		public function create($user)
		{
      if($this->isOrgAdmin)
      {
        return true;
      }
		}

    public function update(User $user, $soloSoloDivision)
		{
      if($this->isAdmin)
      {
        return true;
      }
      elseif($this->isOrgAdmin AND $soloSoloDivision->status_slug == 'active' AND $soloSoloDivision->competition->is_completed == false)
      {
        return true;
      }
		}

		public function destroy(User $user, $soloSoloDivision)
		{
      if($this->isOrgAdmin AND $soloSoloDivision->status_slug == 'active')
      {
        return true;
      }
		}

    public function activateScoring(User $user, SoloDivision $soloSoloDivision)
    {
      if($this->isAdmin AND $soloSoloDivision->status_slug != 'active')
      {
        return true;
      } elseif($this->isOrgAdmin AND $soloSoloDivision->status_slug == 'completed')
      {
        return true;
      }
    }

    public function finalizeScoring(User $user, SoloDivision $soloSoloDivision)
    {
      if($this->isOrgAdmin AND $soloSoloDivision->status_slug == 'completed')
      {
        return true;
      }
    }


    public function completeScoring(User $user, SoloDivision $soloSoloDivision)
    {
      if($this->isOrgAdmin AND $soloSoloDivision->status_slug == 'active')
      {
        return true;
      }
    }

    public function viewFinalStandings(User $user, SoloDivision $soloSoloDivision)
    {
      if($this->isOrgAdmin OR $soloSoloDivision->status_slug == 'finalized')
      {
        return true;
      }
    }
}
