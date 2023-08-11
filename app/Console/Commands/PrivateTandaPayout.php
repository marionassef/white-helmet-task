<?php

namespace App\Console\Commands;


use App\Services\PrivateTandaService;
use Illuminate\Console\Command;

class PrivateTandaPayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tandas-private:payout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there any payout slots';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $privateTandaService = app(PrivateTandaService::class);
        $privateTandaService->checkNextPayouts();
    }
}
