<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class EditChoirForm extends Form
{
    public function buildForm()
    {
        $choir = $this->getData('choir');
        $division = $choir->divisions->first();

        $this->add('receives_rankings', 'checkbox', [
            'value' => $division->pivot->receives_rankings,
            'checked' => $division->pivot->receives_rankings,
            'label' => 'Can receive rankings'
        ]);

        $this->add('receives_ratings', 'checkbox', [
            'value' => $division->pivot->receives_ratings,
            'checked' => $division->pivot->receives_ratings,
            'label' => 'Can receive ratings'
        ]);

		$this->add('submit', 'submit', ['label' => 'Edit ensemble', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
