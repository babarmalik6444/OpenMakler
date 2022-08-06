<?php

namespace App\Models\Traits;

use App\Models\CompanyOffice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $company_office_id
 * @property CompanyOffice $companyOffice
 */
trait BelongsToCompanyOfficeTrait
{
    public function companyOffice(): BelongsTo
    {
        return $this->belongsTo(CompanyOffice::class);
    }
}
