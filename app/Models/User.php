<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompanyTrait;
use App\Models\Traits\HasNameTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


/**
 * @property int $user_role_id
 * @property \App\Models\UserRole $userRole
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, BelongsToCompanyTrait, HasNameTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * @return BelongsTo
     */
    public function companyOffice(): BelongsTo
    {
        return $this->belongsTo(CompanyOffice::class);
    }


    public static function boot(): void
    {
        parent::boot();
        self::creating(function ($model) {
            if (! $model->password) {
                $model->password = Hash::make(config("app.name").rand(-99999, 99999)."#$%".time());
            }
        });
    }


    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }


    public function isSystemAdminOrSystemUser(): bool
    {
        return $this->isSystemAdmin() || $this->isSystemUser();
    }


    public function isSystemAdmin(): bool
    {
        return $this->user_role_id == UserRole::ROLE_SYSTEM_ADMIN;
    }


    public function isSystemUser(): bool
    {
        return $this->user_role_id == UserRole::ROLE_SYSTEM_USER;
    }


    public function isOwner(): bool
    {
        return $this->user_role_id == UserRole::ROLE_OWNER;
    }


    public function isUser(): bool
    {
        return $this->user_role_id == UserRole::ROLE_USER;
    }


    public function isFreelancer(): bool
    {
        return $this->user_role_id == UserRole::ROLE_FREELANCER;
    }


    public function canImpersonate(): bool
    {
        return $this->isSystemAdmin();
    }


    protected static function belongsToCompanyScopeFunction(Builder &$builder)
    {
        $user = auth()->hasUser() ? auth()->user() : null;

        if (request()->routeIs("filament-impersonate.leave")) {
            return;
        }

        if (optional($user)->company_id) {
            $builder->where("company_id", $user->company_id);
        }
    }
}
