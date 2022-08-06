<?php

namespace App\Models\Traits;

trait HasNameTrait
{
    public function getName(): ?string
    {
        return $this->name;
    }
}
