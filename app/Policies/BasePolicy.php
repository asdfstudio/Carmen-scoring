<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;

use Auth;

class BasePolicy
{
    use HandlesAuthorization;

    protected $isAdmin = false;
    protected $isOrgAdmin = false;
    protected $isOrgUser = false;
    protected $orgId = false;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
      if(Auth::user()->isAdmin())
			{
				$this->isAdmin = true;
        $this->isOrgAdmin = true;
        $this->isOrgUser = true;
			}
      elseif(Auth::user()->isOrganizer())
      {
        if(Auth::user()->organization_role == 'admin')
        {
          $this->isOrgAdmin = true;
          $this->isOrgUser = true;
        }
        else {
          $this->isOrgUser = true;
        }
      }
      $this->orgId = Auth::user()->organization_id;
    }

		public function before($user, $ability)
		{
      // May want to get rid of this, at least
      // for those models where admin can access
      // from as an org admin
			if($this->isAdmin)
			{
				return true;
			}
		}

		public function showAll()
		{
      return $this->isOrgUser;
		}

    public function show(User $user, $model)
		{
      return $this->isOrgUser;
		}


		public function create($model)
		{
      if($this->isOrgAdmin)
      {
        return true;
      }
		}

    public function update(User $user, $model)
		{
      if($this->isOrgAdmin)
      {
        return true;
      }
		}

		public function destroy(User $user, $round)
		{
      if($this->isOrgAdmin)
      {
        return true;
      }
		}

}
