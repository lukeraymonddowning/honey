<?php

namespace Lukeraymonddowning\Honey\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lukeraymonddowning\Honey\Models\Spammer;

class SpammerFactory extends Factory
{
    protected $model = Spammer::class;

    public function definition()
    {
        return [
            'ip_address' => $this->faker->ipv4,
            'attempts' => $this->faker->numberBetween(0, 5),
            'blocked_at' => null
        ];
    }

    public function blocked()
    {
        return $this->state(['blocked_at' => now()]);
    }

    public function attempted($times)
    {
        return $this->state(['attempts' => $times]);
    }
}