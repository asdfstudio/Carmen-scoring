<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Judge;
use App\Director;
use App\Choreographer;
use App\School;

class Person extends Model
{
		use SoftDeletes;

		protected $table = 'people';

		protected $dates = ['deleted_at'];

		protected $fillable = ['first_name', 'last_name', 'email', 'tel'];

    //
		public function subject()
		{
			return $this->morphTo();
		}


		public function setTelAttribute($value)
		{
			// strip non-numbers from string and add US code to front
			if($value)
			{
				$this->attributes['tel'] = "+1" . preg_replace("/[^0-9]/", "", $value);
			}
		}

		public function getTelAttribute($value)
		{
			if($value == false) return $value;

			// Strip US code to front
			$value = str_replace("+1", "", $value);

			return "(".substr($value, 0, 3).") ".substr($value, 3, 3)."-".substr($value,6);
		}

		public function getFullNameAttribute()
		{
			return $this->first_name . ' ' . $this->last_name;
		}

    /**
     * The types that belong to the person (App\Judge, App\Director, App\Choreographer).
     */
    public function types()
    {
        return $this->belongsToMany('App\Type');
    }

    public function typeNames()
    {
      $types = $this->types;
      $type_names = array();
      
      foreach($types as $type){
        $type_names[] = str_replace('App\\', '', $type->name);
      }
      
      return $type_names;
    }

		public function getIsTypeAttribute($type)
		{
      return $this->types->contains('name', $type);
		}

		public function getIsJudgeAttribute()
		{
      // Changed relationship to "type" (Judge, Director, Choreographer) to be many-to-many.
			//return $this->person_type == 'App\Judge' ? true : false;
      return $this->getIsTypeAttribute('App\Judge');
		}

		public function getIsJudgeTextAttribute()
		{
			return $this->getIsJudgeAttribute() ? 'Judge' : false;
		}

		public function judge()
		{
      if($this->getIsJudgeAttribute()){
        return Judge::with('divisions', 'captions', 'comments')->find($this->id);
      } else {
        return null;
      }
		}

		public function getIsDirectorAttribute()
		{
      return $this->getIsTypeAttribute('App\Director');
		}

		public function getIsDirectorTextAttribute()
		{
      if($this->getIsDirectorAttribute()){
        return $this->getIsDirectorAttribute() ? 'Director' : false;
      } else {
        return null;
      }
		}

		public function director()
		{
      return Director::with('choirs')->find($this->id);
		}

		public function getIsChoreographerAttribute()
		{
      return $this->getIsTypeAttribute('App\Choreographer');
		}

		public function getIsChoreographerTextAttribute()
		{
			return $this->getIsChoreographerAttribute() ? 'Choreographer' : false;
		}

		public function choreographer()
		{
      if($this->getIsChoreographerAttribute()){
        return Choreographer::with('choirs')->find($this->id);
      } else {
        return null;
      }
		}

    public function choirs()
    {
      $choirs = array();
      $director = $this->director();
      $choreographer = $this->choreographer();
      
      if($director){
        foreach($director->choirs as $choir){
          if(!in_array($choir, $choirs)){
            $choirs[] = $choir;
          }
        }
      }
      
      if($choreographer){
        foreach($choreographer->choirs as $choir){
          if(!in_array($choir, $choirs)){
            $choirs[] = $choir;
          }
        }
      }
      
      return $choirs;
    }

    public function choirIds()
    {
      $choir_ids = array();
      $choirs = $this->choirs();
      
      foreach($choirs as $choir){
        if(!in_array($choir->id, $choir_ids)){
          $choir_ids[] = $choir->id;
        }
      }
      
      return $choir_ids;
    }

    public function choirNames()
    {
      $choir_names = array();
      $choirs = $this->choirs();
      
      foreach($choirs as $choir){
        if(!in_array($choir->name, $choir_names)){
          $choir_names[] = $choir->name;
        }
      }
      
      return $choir_names;
    }

    public function schools()
    {
      $schools = array();
      $school_ids = $this->schoolIds();
      
      foreach($school_ids as $id){
        $school = School::find($id);
        if(!empty($school)){
          $schools[] = $school;
        }
      }
      
      return $schools;
    }

    public function schoolIds()
    {
      $school_ids = array();
      $choirs = $this->choirs();
      
      foreach($choirs as $choir){
        if(!in_array($choir->school_id, $school_ids)){
          $school_ids[] = $choir->school_id;
        }
      }
      
      return $school_ids;
    }

    public function schoolNames()
    {
      $school_names = array();
      $choirs = $this->choirs();
      
      foreach($choirs as $choir){
        if($choir->school && !in_array($choir->school->name, $school_names)){
          $school_names[] = $choir->school->name;
        }
      }
      
      return $school_names;
    }

		public function user()
		{
			return $this->hasOne('App\User', 'person_id');
		}

}
