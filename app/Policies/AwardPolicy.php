<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
use App\Award;
use App\Division;

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




		public function create(User $user)
		{
      if($division)
      {
        if($this->isOrgAdmin AND $division->competition->organization_id === $this->orgId)
        {
          return true;
        }
      }
      else
      {
        return $this->isOrgAdmin;
      }
		}

    public function update(User $user, $award)
		{
      if($this->isOrgAdmin AND $this->orgId === $award->organization_id)
      {
        return true;
      }
		}



		public function destroy(User $user, $award)
		{
      if($this->isOrgAdmin AND $this->orgId === $award->organization_id)
      {
        return true;
      }
		}


    public function assign(User $user, $award, Division $division)
		{
        if($this->isOrgAdmin AND $division->status_slug == 'completed')
        {
          return true;
        }
		}

    public function manage(User $user, $award, Division $division)
		{
        if($this->isOrgAdmin AND $division->status_slug == 'active')
        {
          return true;
        }
		}



}
