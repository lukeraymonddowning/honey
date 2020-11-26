<?php


namespace Lukeraymonddowning\Honey\Tests\Feature;


use Lukeraymonddowning\Honey\Checks\UserIsBlockedSpammerCheck;
use Lukeraymonddowning\Honey\Models\Spammer;

class UserIsKnownSpammerCheckTest extends TestCase
{

    /** @test */
    public function it_blocks_known_spammers()
    {
        $check = app(UserIsBlockedSpammerCheck::class);

        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);
        Spammer::factory()->blocked()->create(['ip_address' => '111.111.1.0']);

        $this->assertFalse($check->passes([]));
    }

    /** @test */
    public function it_allows_unblocked_spammers_through()
    {
        $check = app(UserIsBlockedSpammerCheck::class);

        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);
        Spammer::factory()->create(['ip_address' => '111.111.1.0']);

        $this->assertTrue($check->passes([]));
    }

    /** @test */
    public function it_allows_new_people_through()
    {
        $check = app(UserIsBlockedSpammerCheck::class);

        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);

        $this->assertTrue($check->passes([]));
    }

    /** @test */
    public function it_skips_if_the_feature_is_disabled()
    {
        app()['config']->set('honey.features', []);

        $check = app(UserIsBlockedSpammerCheck::class);

        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);
        Spammer::factory()->blocked()->create(['ip_address' => '111.111.1.0']);

        $this->assertTrue($check->passes([]));
    }

}