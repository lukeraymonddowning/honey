<?php


namespace Lukeraymonddowning\Honey\Tests\Feature;


use Lukeraymonddowning\Honey\Models\Spammer;

class SpammerBlockTest extends TestCase
{

    /** @test */
    public function if_an_ip_address_hits_the_maximum_spam_attempts_they_are_marked_as_blocked()
    {
        $spammer = Spammer::factory()->attempted(4)->create();
        Spammer::markAttempt($spammer->ip_address);
        $this->assertTrue(Spammer::isBlocked($spammer->ip_address));
    }

}