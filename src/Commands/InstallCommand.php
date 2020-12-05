<?php


namespace Lukeraymonddowning\Honey\Commands;


use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = "honey:install";
    protected $description = "Publishes Honey files in your application.";

    public function handle()
    {
        $this->call("vendor:publish", ['--tag' => 'honey']);
    }
}