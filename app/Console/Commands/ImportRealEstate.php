<?php

namespace App\Console\Commands;

use App\OpenImmoV1\Import;
use App\Services\XmlExportImportService;
use Illuminate\Console\Command;

class ImportRealEstate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openimmo:import_file {file=storage/openimmo/examples/test.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a XML file';
    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected XmlExportImportService $xmlExportImportService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(XmlExportImportService $xmlExportImportService)
    {
        parent::__construct();
        $this->xmlExportImportService = $xmlExportImportService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $immo = new Import();

       $immo->makeFromXml($this->argument('file'));
    }
}
