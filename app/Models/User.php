<?php

namespace App\Models;

use App\Enums\User\Role;
use App\Enums\User\Status;
use App\Enums\User\Type;
use App\Services\FuelQuotaService;
use App\Services\UserFuelConsumptionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nic',
        'name',
        'email',
        'password',
        'approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => Type::class,
        'role' => Role::class,
        'status' => Status::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // Relationships
    public function consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class);
    }


    public function scopeCurrentPlanFuelConsumption(Builder $builder)
    {
        $planDates = app(FuelQuotaService::class)->getCurrentPlanDates();

        $builder->withSum([
                'consumptions as currentPlanFuelConsumptionAmount' => function (Builder $builder) use (
                    $planDates
                ) {
                    $builder->whereBetween('consumed_at', [
                        $planDates['startDate'],
                        $planDates['endDate'],
                    ]);
                },
            ], 'amount');
    }

    public function getRemainingFuelQuota(): float
    {
        return (new UserFuelConsumptionService($this))->getRemainingFuelQuota();
    }
}
