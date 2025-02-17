<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Department;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a CSV file into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {$file = $this->argument('file');

        // it needs to contain .csv extension
        if (!preg_match('/\.csv$/', $file)) {
            $this->error('Invalid file type. Please provide a CSV file.');
            return 1;
        }

        // read from public folder
        $file = public_path($file);
        if (!file_exists($file)) {
            $this->error('File not found.');
            return 1;
        }

        try {
            // read the CSV file
            $csv = array_map('str_getcsv', file($file));
            $header = array_shift($csv);

            $this->info('Importing from ' . $file . '...');

            DB::transaction(function() use ($csv, $header) {
                foreach ($csv as $row) {
                    if (count($header) !== count($row)) {
                        $this->error('CSV row does not match header count. Skipping row: ' . implode(',', $row));
                        continue;
                    }
                    $data = array_combine($header, $row);

                    // insert into departments table
                    $newDepartment = Department::firstOrCreate(['name' => $data['deparment_name']]);
                    $newDepartment->save();

                    // insert into categories table
                    $newCategory = Category::firstOrCreate(['name' => $data['category_name']]);
                    $newCategory->save();

                    // insert into manufacturers table
                    $newManufacturer = Manufacturer::firstOrCreate(['name' => $data['manufacturer_name']]);
                    $newManufacturer->save();

                    // insert into products table
                    $newProduct = Product::firstOrCreate([
                        'product_number' => $data['product_number'],
                        'upc' => $data['upc'],
                        'sku' => $data['sku'],
                        'description' => $data['description'],
                        'regular_price' => $data['regular_price'],
                        'sale_price' => $data['sale_price'],
                        'category_id' => $newCategory->id,
                        'department_id' => $newDepartment->id,
                        'manufacturer_id' => $newManufacturer->id
                    ]);
                    $newProduct->save();
                }
            });

            $this->info('CSV imported successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}
