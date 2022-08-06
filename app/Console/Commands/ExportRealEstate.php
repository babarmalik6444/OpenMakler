<?php

namespace App\Console\Commands;

use App\Services\XmlExportImportService;
use Illuminate\Console\Command;
use Spatie\Ray\Payloads\XmlPayload;

class ExportRealEstate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openimmo:export_realestate {ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a real estate xml file';

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
        return 0;
    }
}
