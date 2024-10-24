<?php

namespace App\View\Components;

use App\Models\Form;
use Illuminate\View\Component;

class ViserGigForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $identifier;
    public $identifierValue;
    public $form;
    public $formData;
    public $packages;

    public function __construct($identifier, $identifierValue, $packages)
    {
        $this->identifier = $identifier;
        $this->identifierValue = $identifierValue;
        $this->form = Form::where($this->identifier, $this->identifierValue)->first();
        $this->formData = @$this->form->form_data ?? [];
        $this->packages = json_decode(htmlspecialchars_decode($packages));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.viser-gig-form');
    }
}
