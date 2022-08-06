<?php

namespace App\Http\Livewire;

use App\Models\CustomerRequest;
use App\Models\Openimmo\RealEstate;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImmoContactForm extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public RealEstate $realEstate;

    public bool $submitted = false;
    public string $name = "";
    public string $email = "";
    public string $phone = "";
    public string $message = "";


    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->placeholder("Ihr Vor- und Nachname")
                ->required()
                ->label("Name"),
            Forms\Components\TextInput::make('email')
                ->placeholder("Ihre E-Mail Adresse")
                ->required()
                ->email()
                ->label("E-Mail"),
            Forms\Components\TextInput::make('phone')
                ->placeholder("Ihre Telefonnummer")
                ->required()
                ->label("Telefonnummer"),
            Forms\Components\Textarea::make("message")
                ->placeholder("Was möchten Sie uns mitteilen?")
                ->required()
                ->label("Ihre Nachricht")
        ];
    }


    public function submit(): void
    {
        $data = $this->form->getState();
        $data["realestate_id"] = $this->realEstate->id;
        $data["company_id"] = $this->realEstate->company_id;
        CustomerRequest::create($data);
        $this->submitted = true;
    }


    public function render(): View
    {
        return view('livewire.immo-contact-form');
    }
}
