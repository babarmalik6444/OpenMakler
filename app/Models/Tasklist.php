<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompanyTrait;
use App\Models\Traits\HasNameTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $visibility
 * @property int $user_id
 * @property int $company_office_id
 */
class Tasklist extends AbstractBaseModel
{
    use BelongsToCompanyTrait, HasNameTrait;

    const VISIBILITY_ALL = "a";
    const VISIBILITY_OFFICE = "o";
    const VISIBILITY_PRIVATE = "p";

    protected $casts = [
        "tasks" => "array"
    ];


    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('visibility', function (Builder $builder) {
            $user = auth()->user();

            if($user) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_office_id', $user->company_office_id)
                        ->orWhereNull('company_office_id');
                });
                $builder->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                });
            }
        });
    }


    protected function onCreating()
    {
        parent::onCreating();
        $user = auth()->user();

        if($this->visibility == static::VISIBILITY_PRIVATE && $user) {
            $this->user_id = $user->id;
        }
        else if($this->visibility == static::VISIBILITY_OFFICE) {
            $this->company_office_id = $user->company_office_id;
        }
    }


    public static function getVisibilityOptions()
    {
        return [
            static::VISIBILITY_ALL => "Komplettes Unternehmen",
            static::VISIBILITY_OFFICE => "Nur meine Zweigstelle",
            static::VISIBILITY_PRIVATE => "Nur ich",
        ];
    }
}
