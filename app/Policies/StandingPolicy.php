<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Division;
use App\Standing;

use Auth;

class StandingPolicy extends BasePolicy
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


    public function update(User $user, $standing)
		{
      if($standing == false) return false;

      if($this->isOrgAdmin AND $standing->division->status_slug() != 'finalized')
      {
        return true;
      }
		}

}
