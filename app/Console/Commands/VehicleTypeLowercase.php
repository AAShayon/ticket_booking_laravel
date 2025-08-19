<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;

class VehicleTypeLowercase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicle:type-lowercase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all vehicle types to lowercase';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Converting vehicle types to lowercase...');

        $vehicles = Vehicle::all();
        $updatedCount = 0;

        foreach ($vehicles as $vehicle) {
            $originalType = $vehicle->type;
            $lowerType = strtolower($originalType);

            if ($originalType !== $lowerType) {
                $vehicle->type = $lowerType;
                $vehicle->save();
                $updatedCount++;
            }
        }

        $this->info("Done. Updated {$updatedCount} vehicle records.");

        return 0;
    }
}