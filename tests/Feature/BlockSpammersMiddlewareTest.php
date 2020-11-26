<?php


namespace Lukeraymonddowning\Honey\Tests\Feature;


use Lukeraymonddowning\Honey\Http\Middleware\BlockSpammers;
use Lukeraymonddowning\Honey\Models\Spammer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BlockSpammersMiddlewareTest extends TestCase
{

    /** @test */
    public function it_blocks_known_spammers()
    {
        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);
        Spammer::factory()->blocked()->ip('111.111.1.0')->create();

        try {
            app(BlockSpammers::class)->handle(
                request(),
                function () {
                    $this->fail("You shouldn't have been able to reach here.");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    /** @test */
    public function it_allows_unblocked_spammers_through()
    {
        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);
        Spammer::factory()->ip('111.111.1.0')->create();

        try {
            app(BlockSpammers::class)->handle(
                request(),
                function () {
                    $this->expectNotToPerformAssertions();
                }
            );
        } catch (HttpException $exception) {
            $this->fail("This should have allowed access");
        }
    }

    /** @test */
    public function it_allows_anybody_not_a_spammer_through()
    {
        request()->server->add(['REMOTE_ADDR' => '111.111.1.0']);

        try {
            app(BlockSpammers::class)->handle(
                request(),
                function () {
                    $this->expectNotToPerformAssertions();
                }
            );
        } catch (HttpException $exception) {
            $this->fail("This should have allowed access");
        }
    }

}