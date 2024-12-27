<?php
namespace App\Forms\Scoring;

use Kris\LaravelFormBuilder\Form;

class SendAllScoresAndFeedbackForm extends Form
{
    protected $formOptions = [
        'method' => 'POST',
    ];

    public function buildForm()
    {
        $this->add('send', 'hidden', ['value' => '1']);
        $this->add('submit', 'submit', ['label' => 'Send All Scores and Feedback', 'attr' => ['class' => 'action']]);
    }
}
