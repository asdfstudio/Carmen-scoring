<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use SoftDeletes;

		protected $dates = ['deleted_at'];

		protected $fillable = ['address', 'address_2', 'city', 'state', 'postal_code'];
    
    protected $states_list = array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming'
    );
    
    //
		public function subject()
		{
			return $this->morphTo();
		}

    public function city_state()
    {
      $str = '';

      if($this->city AND $this->state)
      {
        $str = $this->city . ', ' . $this->state_abbreviation();
      }
      elseif($this->city)
      {
        $str = $this->city;
      }
      elseif($this->state)
      {
        $str = $this->state_abbreviation();
      }

      return $str;
    }
    
    public function state_abbreviation()
    {
      if(empty($this->state)){
        return '';
      }
      
      $state_abbreviations = array_keys($this->states_list);
      
      if(in_array(strtoupper($this->state), $state_abbreviations)){
        return strtoupper($this->state);
      }
      
      if($abbreviated = array_search(ucfirst($this->state), $this->states_list)){
        return $abbreviated;
      }
      
      return '';
    }
    
    public function state_full_name()
    {
      if(empty($this->state)){
        return '';
      }
      
      $state_abbreviations = array_keys($this->states_list);
      
      if(in_array(strtoupper($this->state), $state_abbreviations)){
        return $this->states_list[strtoupper($this->state)];
      }
      
      if($abbreviated = array_search(ucfirst($this->state), $this->states_list)){
        return ucfirst($this->state);
      }
      
      return '';
    }
    
}
