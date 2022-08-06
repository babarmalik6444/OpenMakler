<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompanyTrait;

class Contact extends AbstractBaseModel
{
    use BelongsToCompanyTrait;


    protected function onCreating()
    {
        parent::onCreating();
        $user = auth()->user();

        if($user->company_id) {
            $this->company_id = $user->company_id;
        }
    }


    protected function onSaving()
    {
        $this->fullname = $this->vorname . " " . $this->name;
    }


    public function getName(): ?string
    {
        return $this->fullname;
    }
}
