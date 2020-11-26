<?php

namespace Lukeraymonddowning\Honey\Tests;

use Illuminate\Http\Request;
use Lukeraymonddowning\Honey\Facades\Honey;
use Lukeraymonddowning\Honey\Http\Middleware\PreventSpam;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MiddlewareTest extends TestCase
{
    protected PreventSpam $middleware;

    /** @test */
    public function it_has_a_present_but_unfilled_check()
    {
        $this->middleware->handle(
            static::request(),
            function ($request) {
                $this->assertEmpty($request->present_but_empty);
            }
        );

        try {
            $this->middleware->handle(
                static::request(['honey_present' => 'foobar']),
                function ($request) {
                    $this->fail("This request should have been aborted");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }

        try {
            $this->middleware->handle(
                static::request([], ['honey_present']),
                function ($request) {
                    $this->fail("This request should have been aborted");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }
    }

    protected static function request($attributes = [], $skip = [])
    {
        $attributes = array_merge(['honey_present' => '', 'honey_time' => microtime(true) - 5], $attributes);

        foreach ($skip as $key) {
            unset($attributes[$key]);
        }

        return (new Request)->merge($attributes);
    }

    /** @test */
    public function it_has_a_time_check()
    {
        $this->middleware->handle(
            static::request(['honey_time' => microtime(true) - 10]),
            function ($request) {
                $this->assertNotEmpty($request->honey_time);
            }
        );

        try {
            $this->middleware->handle(
                static::request(['honey_time' => microtime(true) - 2]),
                function ($request) {
                    $this->fail("This request should have been aborted");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(422, $exception->getStatusCode());
        }

    }

    /** @test */
    public function the_handler_can_be_configured()
    {
        Honey::failUsing(fn() => abort(404, "Nothing to see here!"));

        try {
            $this->middleware->handle(
                static::request(['honey_time' => microtime(true) - 2]),
                function ($request) {
                    $this->fail("This request should have been aborted");
                }
            );
        } catch (HttpException $exception) {
            $this->assertEquals(404, $exception->getStatusCode());
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('honey.input_name_selectors.default', 'static');
        $this->middleware = app(PreventSpam::class);
    }

}