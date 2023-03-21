<?php

namespace App\Forms\Choir;

use Kris\LaravelFormBuilder\Form;

use App\Choir;

class ChooseChoirForm extends Form
{
    public function buildForm()
    {
        $choirs = Choir::get();

				$this->add('choir_id','select', [
					'choices' => $choirs->pluck('name','id')->toArray(),
					'empty_value' => 'Choose ensemble...',
					'label' => 'Ensemble'
				]);
				$this->add('submit', 'submit', ['label' => 'Add ensemble', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
