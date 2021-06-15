<?php

namespace App\Console\Commands;

use App\Phone;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportPhonesDataSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phones:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the phones into the database.';
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = $this->client->get("https://storage.googleapis.com/play_public/supported_devices.csv");

        $csv = Reader::createFromString(iconv('UTF-16', 'UTF-8', $response->getBody()->getContents()));
        $csv->setHeaderOffset(0);

        foreach ($csv->getRecords() as $record) {
            Phone::updateOrCreate(
                ['model' =>  $record['Model']],
                [
                    'retail_branding' => $record['Retail Branding'],
                    'marketing_name' => $record['Marketing Name'],
                    'device' => $record['Device'],
                ]
            );
        }
    }
}
