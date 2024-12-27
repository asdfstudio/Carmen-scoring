<?php
namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class CompleteAllScoringForm extends Form
{
    protected $formOptions = [
        'method' => 'POST',
    ];

    public function buildForm()
    {
        $this->add('complete', 'hidden', ['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Complete All Scoring', 'attr' => ['class' => 'action']]);
    }
}
