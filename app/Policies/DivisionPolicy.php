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

    public function before($user, $ability)
    {

    }

    public function show(User $user, $round)
    {
        return $this->isOrgUser;
    }


    public function create($competition)
    {
        if($this->isOrgAdmin AND $competition)
        {
            return true;
        }
    }

    public function update(User $user, $division)
    {
        if($this->isAdmin)
        {
            return true;
        }
        elseif($this->isOrgAdmin AND $division->status_slug() == 'activated' AND $division->competition->is_completed == false)
        {
            return true;
        }
    }

    public function destroy(User $user, $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'activated')
        {
            return true;
        }
    }

    public function activateScoring(User $user, $division)
    {
        return ($this->isOrgAdmin AND $division->isNew() AND $division->status_slug == 'deactivated');
    }

    public function deactivateScoring(User $user, $division)
    {
        return ($this->isOrgAdmin AND $division->status_slug == 'activated');
    }

    public function reactivateScoring(User $user, $division)
    {
        return ($this->isOrgAdmin AND !$division->isNew() AND $division->status_slug == 'deactivated');
    }

    public function completeScoring(User $user, $division)
    {
        $scoresMissing = $division->isMissingScores();

        if ($this->isOrgAdmin AND !$scoresMissing AND $division->status_slug() != 'completed') {
            return true;
        }
    }

    public function finalizeScoring(User $user, $division)
    {
        return ($this->isOrgAdmin AND $division->status_slug() == 'completed');
    }

    public function addChoir(User $user, Division $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'activated' AND $division->competition->is_completed == false)
        {
            return true;
        }
    }

    public function removeChoir(User $user, Division $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'activated')
        {
            return true;
        }
    }

    public function viewFinalStandings(User $user, Division $division)
    {
        if($this->isOrgAdmin OR $division->status_slug() == 'finalized')
        {
            return true;
        }
    }


    public function createPenalty(User $user, Division $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'activated')
        {
            return true;
        }
    }

    public function assignPenalty(User $user, Division $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'activated')
        {
            return true;
        }
    }

    public function managePenalties(User $user, Division $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'activated')
        {
            return true;
        }
    }

    // -dg-
    public function viewResults(User $user, Division $division)
    {
        if($this->isOrgAdmin AND $division->status_slug() == 'finalized')
        {
            return true;
        }
    }
}
