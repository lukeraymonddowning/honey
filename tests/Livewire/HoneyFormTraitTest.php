<?php


namespace Lukeraymonddowning\Honey\Tests\Livewire;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Livewire;
use Lukeraymonddowning\Honey\InputValues\Values;
use Lukeraymonddowning\Honey\Tests\TestCase;
use Lukeraymonddowning\Honey\Traits\WithHoney;
use Lukeraymonddowning\Honey\Traits\WithRecaptcha;

class HoneyFormTraitTest extends TestCase
{
    /** @test */
    public function the_honeyInputs_attribute_is_filled_with_the_input_names()
    {
        $test = Livewire::test(Example::class);
        $test->assertSet('honeyInputs.honey_present', null);
        $this->assertEquals((int) microtime(true), (int) Crypt::decrypt($test->viewData('honeyInputs')['honey_time']));
        $test->assertSet('honeyInputs.honey_alpine', null);
    }

    /** @test */
    public function checks_can_be_run_against_the_inputs()
    {
        $test = Livewire::test(Example::class);
        $test->assertSet('passesCheck', false);
        $test->set('honeyInputs.honey_time', Crypt::encrypt(microtime(true) - 5));
        $test->set('honeyInputs.honey_alpine', Values::alpine()->getValue());
        $test->call('check');
        $test->assertSet('passesCheck', true);
    }

    /** @test */
    public function if_the_values_are_incorrect_the_check_fails()
    {
        $test = Livewire::test(Example::class);
        $test->assertSet('passesCheck', false);
        $test->call('check');
        $test->assertSet('passesCheck', false);
    }

    /** @test */
    public function it_can_run_a_recaptcha_test()
    {
        Http::fake(['*' => [
            'success' => true,
            'score' => 0.8,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => []
        ]]);

        $test = Livewire::test(AnotherExample::class);
        $test->assertSet('passesRecaptcha', false);
        $test->set('honeyInputs.honey_recaptcha_input', 'foobar');
        $test->call('checkRecaptcha');
        $test->assertSet('passesRecaptcha', true);
    }

    /** @test */
    public function if_honeyPasses_is_called_and_WithRecaptcha_is_a_trait_it_checks_the_recaptcha_too()
    {
        Http::fake(['*' => [
            'success' => true,
            'score' => 0.8,
            'action' => 'submit',
            'challenge_ts' => now()->toIso8601String(),
            'hostname' => config('app.url'),
            'error-codes' => []
        ]]);

        $test = Livewire::test(AnotherExample::class);
        $test->set('honeyInputs.honey_time', Crypt::encrypt(microtime(true) - 5));
        $test->set('honeyInputs.honey_alpine', Values::alpine()->getValue());
        $test->set('honeyInputs.honey_recaptcha_input', 'foobar');
        $test->assertSet('passesEverything', false);
        $test->call('check');
        $test->assertSet('passesEverything', true);

        Http::assertSentCount(1);
    }
}

class Example extends Component
{
    use WithHoney;

    public $passesCheck = false;

    public function render()
    {
        return <<<'blade'
            <div>
                <x-honey recaptcha/>
            </div>
        blade;
    }

    public function check()
    {
        $this->passesCheck = $this->honeyPasses();
    }
}

class AnotherExample extends Component
{
    use WithHoney, WithRecaptcha;

    public $passesEverything = false;
    public $passesRecaptcha = false;

    public function render()
    {
        return <<<'blade'
            <div>
                <x-honey recaptcha/>
            </div>
        blade;
    }

    public function check()
    {
        $this->passesEverything = $this->honeyPassed;
    }

    public function checkRecaptcha()
    {
        $this->passesRecaptcha = $this->recaptchaPassed;
    }
}