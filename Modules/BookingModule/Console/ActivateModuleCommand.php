<?php

namespace Modules\BookingModule\Console;

use App\Models\Company;
use Illuminate\Console\Command;
use Modules\BookingModule\Entities\BookingModuleSetting;

class ActivateModuleCommand extends Command
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'bookingmodule:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all the module settings of bookingmodule module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            BookingModuleSetting::addModuleSetting($company);
        }

        $this->info('BookingModule settings inserted/updated for all companies.');

        return 0;
    }
}
