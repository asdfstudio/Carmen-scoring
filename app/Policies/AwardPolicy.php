<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Award;
use App\Competition;
use App\Division;
use App\Round;

class AwardPolicy extends BasePolicy
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




    public function createForDivision(User $user, Division $division)
    {
      if ($division) {
        if ($this->isOrgAdmin AND !empty($division->competition->organization_id) AND $division->competition->organization_id === $this->orgId)
        {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return $this->isOrgAdmin;
      }
    }

    public function createForCompetition(User $user, Competition $competition)
    {
      if ($competition) {
        if ($this->isOrgAdmin AND !empty($competition->organization_id) AND $competition->organization_id === $this->orgId)
        {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return $this->isOrgAdmin;
      }
    }

    public function createForRound(User $user, Round $round)
    {
      if($round) {
        if($this->isOrgAdmin AND !empty($round->competition->organization_id) AND $round->competition->organization_id === $this->orgId)
        {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return $this->isOrgAdmin;
      }
    }

    public function update(User $user, $award)
    {
      if($this->isOrgAdmin AND $this->orgId === $award->organization_id) {
        return true;
      }
    }

    public function destroy(User $user, $award)
    {
      if($this->isOrgAdmin AND $this->orgId === $award->organization_id) {
        return true;
      }
    }


    public function assign($award, Division $division)
    {
        if($this->isOrgAdmin AND $this->orgId === $award->organization_id) {
          return true;
        }
    }

    public function manage($award, Division $division)
    {
        if($this->isOrgAdmin AND $this->orgId === $award->organization_id) {
          return true;
        }
    }

}
