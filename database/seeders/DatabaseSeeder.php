<?php

namespace Database\Seeders;

use App\Enums\Quota\Basis;
use App\Enums\User\Type;
use App\Models\Quota;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public const REGULAR_AMOUTN = 2500;
    public const SPECIAL_AMOUNT = 5000;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->createUsers();
        $this->createQuotaRecord();
        $this->assignConsumptionsforUsers($users);
    }

    public function createUsers()
    {
        $users = User::factory()->count(100)->create();

        User::factory()->makeOwner()->approveAccount()->create(['nic' => 1111]);
        User::factory()->makeSuperAdmin()->approveAccount()->create(['nic' => 2222]);
        User::factory()->makeAdmin()->approveAccount()->create(['nic' => 3333]);

        return $users;
    }

    public function createQuotaRecord()
    {
        quota::create([
            'basis' => Basis::WEEKLY->value,
            'regular_amount' => self::REGULAR_AMOUTN,
            'special_amount' => self::SPECIAL_AMOUNT,
            'is_current_plan' => true,
        ]);
    }

    public function assignConsumptionsforUsers(Collection $users)
    {
        $users->each(function (User $user) {
            $amount = $user->type->value === Type::REGULAR->value
                ? rand(0, self::REGULAR_AMOUTN)
                : rand(0, self::SPECIAL_AMOUNT);

            $user->consumptions()->create([
                'amount' => $amount,
                'consumed_at' => now()->subDays(rand(0, 7))->toDateTime(),
            ]);
        });
    }
}
