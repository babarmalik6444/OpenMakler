<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompanyTrait;
use App\Models\Traits\HasNameTrait;


/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $strasse
 * @property string $hausnummer
 * @property string $plz
 * @property string $ort
 * @property string $postfach
 */
class CompanyOffice extends AbstractBaseModel
{
    use BelongsToCompanyTrait, HasNameTrait;


    public function employees()
    {
        return $this->hasMany(User::class);
    }
}
