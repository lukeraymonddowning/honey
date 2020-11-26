<?php


namespace Lukeraymonddowning\Honey\Tests;


use Lukeraymonddowning\Honey\Facades\Honey;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FailHooksTest extends TestCase
{

    /** @test */
    public function hooks_can_be_fired_before_the_registered_fail_takes_place()
    {
        Honey::failUsing(fn() => abort(500, 'This shouldn\'t have happened'));
        Honey::beforeFailing(fn() => abort(404, 'Not found you say?'));

        try {
            Honey::fail();
        } catch (HttpException $exception) {
            $this->assertEquals(404, $exception->getStatusCode());
        }
    }

}