<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Models\DivisionFile; 

class EditChoirForm extends Form
{
    public function buildForm()
    {
        $choir = $this->getData('choir');
        $division = $choir->divisions->first();

        $currentDivision = $choir->divisions->first();

        if ($currentDivision) {
            $round = $currentDivision->round;
            $availableDivisions = $round->divisions;
        } else {
            $availableDivisions = collect();
        }

        $this->add('division_id', 'select', [
            'choices' => $availableDivisions->pluck('name', 'id')->toArray(), 
            'selected' => $currentDivision ? $currentDivision->id : null,
            'label' => 'Move to Another Class',
            'attr' => ['class' => 'form-control']
        ]);

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

        $this->add('choral_sweepstakes', 'checkbox', [
            'value' => $division->pivot->choral_sweepstakes,
            'checked' => $division->pivot->choral_sweepstakes,
            'label' => 'Choral Sweepstakes Qualification',
        ]);

        $this->add('instrumental_sweepstakes', 'checkbox', [
            'value' => $division->pivot->instrumental_sweepstakes,
            'checked' => $division->pivot->instrumental_sweepstakes,
            'label' => 'Instrumental Sweepstakes Qualification',
        ]);

        $this->add('festival_sweepstakes', 'checkbox', [
            'value' => $division->pivot->festival_sweepstakes,
            'checked' => $division->pivot->festival_sweepstakes,
            'label' => 'Festival Sweepstakes Qualification',
        ]);

        $this->add('submit', 'submit', ['label' => 'Edit ensemble', 'attr' => ['class' => 'btn btn-primary']]);
    }
}
