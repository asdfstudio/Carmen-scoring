<?php

namespace App;

use ReflectionClass;

use Illuminate\Database\Eloquent\Builder;

use Sofa\Eloquence\Eloquence;

use Auth;

trait RestrictsOrganization
{
    protected static function bootRestrictsOrganization()
		{

			static::addGlobalScope('organization', function(Builder $builder) {

          if(Auth::check())
					{
						$user = Auth::user();

            // No user, no filter
            if($user == false) return $builder;

            $organization_id = $user->organization_id;

            // No organization, no filter
            if($organization_id == false) return $builder;

						// Filter model
						$builder->where('organization_id', $organization_id);
					}
			});
		}





}
