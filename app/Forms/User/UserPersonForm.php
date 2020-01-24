<?php

namespace App\Forms\User;

use Kris\LaravelFormBuilder\Form;

class UserPersonForm extends Form
{
  
  protected $formOptions = [
    'class' => 'user-person-form',
    'organization' => null
  ];
  
  protected $primary = null;
  
  protected $primary_type = null;
  
  protected $secondary = null;
  
  protected $secondary_type = null;
  
  protected $person = null;
  
  protected $user = null;
  
  protected $mode = 'Create';
  
  public function buildForm()
  {
    
    // Determine if we are primarily working with a User or a Person.
    $this->primary = $this->getModel();
    $this->primary_type = str_replace('App\\', '', get_class($this->primary));
    
    // Get complementary Person data for a User or vice versa.
    if($this->primary_type === 'User'){
      $this->user = $this->primary;
      $this->person = $this->primary->person;
      $this->secondary = $this->person;
      $this->secondary_type = $this->secondary ? str_replace('App\\', '', get_class($this->secondary)) : null;
    } elseif($this->primary_type === 'Person'){
      $this->user = $this->primary->user;
      $this->person = $this->primary;
      $this->secondary = $this->user;
      $this->secondary_type = $this->secondary ? str_replace('App\\', '', get_class($this->secondary)) : null;
    }
    
    // Create or Edit? By default we assume create, but if the primary model already has
    // an ID set, that means we are editing an existing record.
    if($this->primary->id){
      $this->mode = 'Edit';
    }
    
    $this->formOptions['class'] .= strtolower(' ' . $this->mode . '-' . $this->primary_type);
    
    if(!$this->user || !$this->user->id){
      $this->formOptions['class'] .= ' no-user';
    }
    
    // Is the current user an admin?
    $i_am_admin = auth()->user()->isAdmin();
    $i_am_org_admin = !empty($this->formOptions['organization']) && auth()->user()->organization_role === 'admin' && auth()->user()->organization_id === $this->formOptions['organization'];
    
    // Is the current user a superadmin (listed in the auth config or else are they an admin who is editing their own account)?
    $user_to_compare = $this->user ?  $this->user : null;
    $i_am_superadmin = auth()->user()->isSuperAdmin($user_to_compare);
    $target_is_superadmin = $this->user ? $this->user->isSuperAdmin() : false;
    
    // Is the current user editing their own account?
    $self_editing = $this->user && !empty($this->user->id) && $this->user->id === auth()->user()->id;
    
    
  /*================================================================================
      Name and Email
    ================================================================================*/
    
    $this->add('heading_name', 'static', [
      'wrapper' => ['class' => 'form-group name-email-section'],
      'tag' => 'h2',
      'value' => 'Name and Email',
      'label_show' => false
    ]);

    // Name always comes from the Person data.
    $this->add('first_name', 'text', [
      'wrapper' => ['class' => 'form-group name-email-section'],
      'rules' => 'required',
      'label' => 'First Name'
    ]);
    
    if($this->person){
      $this->modify('first_name', 'text', [
        'default_value' => $this->person->first_name
      ]);
    }
    
    $this->add('last_name', 'text', [
      'wrapper' => ['class' => 'form-group name-email-section'],
      'rules' => 'required',
      'label' => 'Last Name'
    ]);
    
    if($this->person){
      $this->modify('last_name', 'text', [
        'default_value' => $this->person->last_name
      ]);
    }
    
    $user_id_or_null = $this->user ? $this->user->id : null;
    $person_id_or_null = $this->person ? $this->person->id : null;
    
    $this->add('email','email', [
      'wrapper' => ['class' => 'form-group name-email-section'],
      'rules' => ['required', 'email', 'unique:users,email,'.$user_id_or_null, 'unique:people,email,'.$person_id_or_null.',id,deleted_at,NULL'],
    ]);
    
    // The User email takes precedence over the Person email.
    if($this->user && $this->user->email){
      
      $this->modify('email','email', [
        'default_value' => $this->user->email
      ]);
      
      // If there is a conflicting Person email...
      if($this->person && $this->person->email && $this->person->email !== $this->user->email){
        
        $maybe_comma = empty($this->person->emails_additional) ? '' : ', ';
        
        $this->person->emails_additional = $this->person->email . $maybe_comma . $this->person->emails_additional;
        
        $this->modify('email','email', [
          'help_block' => ['text' => 'Another email address (' . $this->person->email . ') was also associated with this account. It has been moved to the Additional Email Addresses field.']
        ]);
        
      }
      
    } elseif($this->person && $this->person->email){
      
      // If no User, fall back to the Person email.
      $this->modify('email','email', [
        'default_value' => $this->person->email
      ]);
      
    }
    
    $this->add('emails_additional','email', [
      'wrapper' => ['class' => 'form-group name-email-section'],
      'label' => 'Additional Email Addresses',
      'help_block' => [
        'text' => 'One or more addition emails that should also get notifications. Separate addresses with a comma.',
      ]
    ]);

    if($this->person && $this->person->emails_additional){
      $this->modify('emails_additional','email', [
        'default_value' => $this->person->emails_additional
      ]);
    }

    $this->add('tel','tel', [
      'label' => 'Phone Number',
      'wrapper' => ['class' => 'form-group name-email-section'],
      'rules' => ['regex:/^(?:(?:(\s*\(?([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\)?\s*(?:[.-]\s*)?)([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})$/'],
      'error_messages' => [
        'tel.regex' => 'Please enter a valid telephone number with the area code.'
      ]
    ]);

    if($this->person && $this->person->tel){
      $this->modify('tel','tel', [
        'default_value' => $this->person->tel
      ]);
    }

  /*================================================================================
      User Account
    ================================================================================*/
    
    $user_section_visibility_class = '';
    $user_disabled_attribute = [];
    $password_disabled_attribute = [];
    
    // If we are editing a person without a user account, offer to create one.
    if(!$this->user){
      $this->add('convert_to_user', 'static', [
        'label_show' => false,
        'tag' => 'a',
        'attr' => ['class' => 'toggle-new-user btn btn-secondary action', 'href' => '#'],
        'value' => 'Make this person a registered user',
      ]);
      
      $user_section_visibility_class = 'hidden';
      $user_disabled_attribute['disabled'] = 'disabled';
      $password_disabled_attribute = $user_disabled_attribute;
    }
    
    $this->add('heading_user', 'static', [
      'wrapper' => ['class' => 'form-group user-account-section '.$user_section_visibility_class],
      'tag' => 'h2',
      'value' => 'User Account',
      'label_show' => false
    ]);

    $this->add('username','text', [
      'wrapper' => ['class' => 'form-group user-account-section '.$user_section_visibility_class],
      'rules' => ['unique:users,username', 'regex:/^[\w\.\-]+$/i'],
      'attr' => $user_disabled_attribute
    ]);
    
    if($this->user){
      $this->modify('username','text', [
        'rules' => ['unique:users,username,'.$this->user->id]
      ]);
    }
    
    if(!empty($this->formOptions['organization'])){
      // In the context of creating an organization user, the username is required.
      $this->modify('username','text', [
        'rules' => ['required']
      ]);
    }

    if($this->user && $this->primary_type !== 'User'){
      $this->modify('username','text', [
        'default_value' => $this->user->username
      ]);
    }
    
    // Disable username editing for non-admins and non-self-editors.
    if($this->user && $this->mode === 'Edit' && !$self_editing && (!$i_am_admin || ($target_is_superadmin && !$i_am_superadmin))){
      $this->modify('username','text', [
        'attr' => ['disabled' => 'disabled']
      ]);
    }
    
    // No one can edit their own admin status.  Otherwise, superadmins can edit the "Carmen Admin" checkbox anytime.
    // Regular admins can make someone else an admin, but cannot uncheck this box for an existing admin.
    if(!$self_editing && ($i_am_superadmin || ($i_am_admin && (!$this->user || !$this->user->is_admin)))){
      $this->add('is_admin', 'choice', [
        'wrapper' => ['class' => 'form-group choice-container user-account-section '.$user_section_visibility_class],
        'label_show' => false,
        'choices' => ['1' => 'Carmen Admin'],
        'expanded' => true,
        'multiple' => true
      ]);

      if($this->user && $this->primary_type !== 'User'){
        $this->modify('is_admin', 'choice', [
          'selected' => $this->user->is_admin ? [1] : []
        ]);
      }
    } else {
      
      $is_admin_checked =  $this->user && $this->user->is_admin ? 'checked="checked"' : '';
      
      $this->add('is_admin_static', 'static', [
        'wrapper' => ['class' => 'choice-container user-account-section '.$user_section_visibility_class],
        'label_show' => false,
        'tag' => 'div',
        'value' => '<input type="checkbox" disabled="disabled" ' . $is_admin_checked . '> <label>Carmen Admin</label>'
      ]);
    }
    
    // When editing an existing user, this link will toggle the password fields.
    if($this->mode == 'Edit' && $this->user && ($i_am_superadmin || ($i_am_admin && !$target_is_superadmin) || $self_editing)){
      $this->add('update_password', 'static', [
        'wrapper' => ['class' => 'form-group user-account-section '.$user_section_visibility_class],
        'label_show' => false,
        'tag' => 'a',
        'attr' => ['class' => 'toggle-new-password btn btn-secondary action', 'href' => '#'],
        'value' => 'Update Password',
      ]);
      
      $password_disabled_attribute['disabled'] = 'disabled';
    }
    
    // Superadmins can edit the field, but regular admins can only modify this when creating another user (not when editing).
    if($i_am_superadmin || $self_editing || (($i_am_admin || $i_am_org_admin) && (!$target_is_superadmin || !$this->user || empty($this->user->id)))){
      
      $this->add('new_password','repeated', [
        'wrapper' => ['class' => 'form-group user-account-section password-fields '.$user_section_visibility_class],
        'type' => 'password',
        'second_name' => 'new_password_confirmation',
        'first_options' => [
          'default_value' => '',
          'rules' => ['required_with:username', 'confirmed', 'min:4'],
          'attr' => $password_disabled_attribute
        ],
        'second_options' => [
          'default_value' => '',
          'rules' => ['required_with:username'],
          'attr' => $password_disabled_attribute
        ]
      ]);
      
      // When creating a new user, the password fields are required
      if($this->mode == 'Create' && $this->primary_type === 'User'){
        $this->modify('new_password','repeated', [
          'first_options' => [
            'rules' => ['required', 'confirmed', 'min:4']
          ],
          'second_options' => [
            'rules' => ['required']
          ]
        ]);
      }
      
      // When editing an existing user, hide and disable the password fields (until the toggle link is clicked).
      // Also set the validation rule to "filled" instead of "required" so that it can be ommitted.
      if($this->mode == 'Edit' && $this->primary_type === 'User'){
        $this->modify('new_password','repeated', [
          'first_options' => [
            'attr' => ['disabled' => 'disabled', 'class' => 'form-control hidden'],
            'label_attr' => ['class' => 'control-label hidden'],
            'rules' => 'filled|confirmed|min:4'
          ],
          'second_options' => [
            'attr' => ['disabled' => 'disabled', 'class' => 'form-control hidden'],
            'label_attr' => ['class' => 'control-label hidden'],
            'rules' => 'filled'
          ]
        ]);
      }
    }
    
    
  /*================================================================================
      Organization
    ================================================================================*/
    
    $this->add('heading_organization', 'static', [
      'wrapper' => ['class' => 'form-group org-section '.$user_section_visibility_class],
      'tag' => 'h2',
      'value' => 'Organization',
      'label_show' => false
    ]);

    $selected_org_id = '';
    if($this->user && !empty($this->user->organization_id)){
      $selected_org_id = $this->user->organization_id;
    }

    $this->add('organization_id','entity', [
      'wrapper' => ['class' => 'form-group org-section '.$user_section_visibility_class],
      'class' => 'App\Organization',
      'empty_value' => 'Select an organization...',
      'selected' => $selected_org_id,
      'label' => 'Organization',
    ]);

    // The following modification is only for organizers who are editing members of their organization. 
    if(!empty($this->formOptions['organization'])){

      // In a "create" situation, the value is preset to the correct organization and it cannot be changed.
      if($this->mode === 'Create'){
        $this->modify('organization_id','entity', [
          'selected' => $this->formOptions['organization'],
          'attr' => ['disabled' => 'disabled']
        ]);
      }

      // In an "edit" situation, the value will already be the correct organization, but we still need to make it read-only.
      if($this->mode === 'Edit'){
        $this->modify('organization_id','entity', [
          'attr' => ['disabled' => 'disabled']
        ]);
      }
    }
    
    // If you're not an admin or an organization admin, you can't edit the organization data. (No self-editing.)
    if(!$i_am_admin && !$i_am_org_admin){
      $this->modify('heading_organization', 'static', [
        'help_block' => [
          'text' => 'Organization assignment can only be modified by administrators.'
        ]
      ]);
      $this->modify('organization_id','entity', [
        'attr' => ['disabled' => 'disabled']
      ]);
    }
    
    $selected_org_role = '';
    if($this->user && !empty($this->user->organization_role)){
      $selected_org_role = $this->user->organization_role;
    }
    
    // Only admins and organization admins can edit the organization role. (Otherwise, it is read-only.)
    if($i_am_admin || $i_am_org_admin){
      $this->add('organization_role', 'choice', [
        'wrapper' => ['class' => 'form-group choice-container org-section '.$user_section_visibility_class],
        'rules' => 'required_with:organization_id',
        'label' => 'Organization Role',
        'choices' => ['standard' => 'Standard User', 'admin' => 'Administrator'],
        'selected' => $selected_org_role,
        'expanded' => true,
        'multiple' => false
      ]);
    } else {
      $standard_checked = $this->user && !empty($this->user->organization_role) && $this->user->organization_role === 'standard' ? 'checked="checked"' : '';
      $admin_checked = $this->user && !empty($this->user->organization_role) && $this->user->organization_role === 'admin' ? 'checked="checked"' : '';
      $this->add('organization_role_static', 'static', [
        'wrapper' => ['class' => 'form-group choice-container org-section '.$user_section_visibility_class],
        'label_show' => false,
        'tag' => 'div',
        'value' => '<label class="control-label">Organization Role</label><input type="radio" disabled="disabled" ' . $standard_checked . '> <label>Standard User</label><input type="radio" disabled="disabled" ' . $admin_checked . '> <label>Administrator</label>'
      ]);
    }
    
    
  /*================================================================================
      Roles
    ================================================================================*/
    
    // Roles are not needed in the organization context.
    if(empty($this->formOptions['organization'])){
      
      $this->add('heading_roles', 'static', [
        'wrapper' => ['class' => 'form-group roles-section'],
        'tag' => 'h2',
        'value' => 'Roles',
        'label_show' => false,
        'help_block' => [
          'text' => 'Judge status for users can be enabled or disabled below. Director and choreographer status can only be changed by <a href="' . route('admin.choir.index') . '">editing a choir</a> and adding this person to it.'
        ]
      ]);

      if($i_am_admin){
        $this->add('is_judge', 'choice', [
          'wrapper' => ['class' => 'choice-container roles-section user-account-section '.$user_section_visibility_class],
          'label_show' => false,
          'choices' => ['1' => 'Judge'],
          'selected' => ($this->person && $this->person->isJudge()) ? ['1'] : [],
          'expanded' => true,
          'multiple' => true
        ]);
      } elseif(($this->user && $this->user->id) || ($this->person && $this->person->isJudge())) {
        $this->modify('heading_roles', 'static', [
          'help_block' => [
            'text' => 'Roles can only be modified by administrators.'
          ]
        ]);
        
        $judge_checked = $this->person && $this->person->isJudge() ? 'checked="checked" ' : '';

        $this->add('is_judge_static', 'static', [
          'wrapper' => ['class' => 'choice-container roles-section'],
          'label_show' => false,
          'tag' => 'div',
          'value' => '<input type="checkbox" disabled="disabled" ' . $judge_checked . '> <label>Judge</label>'
        ]);
      }

      // Director and choreographer info are only shown in edit mode. They are also read-only.
      if($this->mode == 'Edit'){

        $director = $this->person ? $this->person->director() : null;
        $choirs_directed = $director ? $director->choirs : [];
        $director_checked = $director ? 'checked="checked" ' : '';

        $this->add('is_director', 'static', [
          'wrapper' => ['class' => 'choice-container roles-section'],
          'label_show' => false,
          'tag' => 'div',
          'value' => '<input type="checkbox" disabled="disabled" ' . $director_checked . '> <label>Director</label>'
        ]);

        if(count($choirs_directed)){

          $choirs_directed_list = '';

          foreach($choirs_directed as $i => $choir){
            $choirs_directed_list .= '<a href="' . route('admin.choir.show', [$choir]) . '">' . $choir->name . '</a>';
            $choirs_directed_list .= $i < count($choirs_directed)-1 ? ', ' : '';
          }

          $this->modify('is_director', 'static', [
            'help_block' => [
              'text' => 'Choirs: '.$choirs_directed_list,
              'attr' => ['class' => 'help-block indented']
            ]
          ]);

        }

        $choreographer = $this->person ? $this->person->choreographer() : null;
        $choirs_choreographed = $choreographer ? $choreographer->choirs : [];
        $choreographer_checked = $choreographer ? 'checked="checked" ' : '';

        $this->add('is_choreographer', 'static', [
          'wrapper' => ['class' => 'choice-container roles-section'],
          'label_show' => false,
          'tag' => 'div',
          'value' => '<input type="checkbox" disabled="disabled" ' . $choreographer_checked . '> <label>Choreographer</label>'
        ]);

        if(count($choirs_choreographed)){

          $choirs_choreographed_list = '';

          foreach($choirs_choreographed as $i => $choir){
            $choirs_choreographed_list .= '<a href="' . route('admin.choir.show', [$choir]) . '">' . $choir->name . '</a>';
            $choirs_choreographed_list .= $i < count($choirs_choreographed)-1 ? ', ' : '';
          }

          $this->modify('is_choreographer', 'static', [
            'help_block' => [
              'text' => 'Choirs: '.$choirs_choreographed_list,
              'attr' => ['class' => 'help-block indented']
            ]
          ]);

        }

      }
      
    }
    
    
    $this->add('spacer', 'static', [
      'label_show' => false,
      'tag' => 'hr'
    ]);
    
    
    $this->add('submit', 'submit', ['label' => $this->mode . ' ' . $this->primary_type, 'attr' => ['class' => 'btn btn-primary']]);
  }
  
  
}
