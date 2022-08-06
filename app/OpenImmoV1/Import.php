<?php

namespace App\OpenImmoV1;

use App\Models\Openimmo\Import as ImportModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Ujamii\OpenImmo\API\Anbieter;
use Ujamii\OpenImmo\API\Immobilie;
use Ujamii\OpenImmo\API\Uebertragung;
use ZanySoft\Zip\Zip;

class Import
{
    /**
     * @var \Ujamii\OpenImmo\API\Openimmo
     */
    private \Ujamii\OpenImmo\API\Openimmo $openImmo;
    private string $filename;
    private string $path;
    private ImportModel $import;

    /**
     * @return Anbieter[]
     */
    private function getAnbieter(): array
    {
        return $this->openImmo->getAnbieter();
    }


    /**
     * @return Immobilie[]
     */
    public function getImmobilien(): array
    {
        $immos = [];

        foreach($this->getAnbieter() AS $anbieter){
            $immos = array_merge($immos, $anbieter->getImmobilie());
        }

        return $immos;
    }


    /**
     * @return Uebertragung|null
     */
    private function getUebertragung(): Uebertragung
    {
        $item = $this->openImmo->getUebertragung();

        return $item ;
    }


    public function getImportedCount(): int
    {
        return method_exists($this->import, "realEstates") ? $this->import->realEstates()->count() : 0;
    }


    public function saveToDb(?User $user = null, bool $debug = false): self
    {
        $user = $user ?: auth()->user();

        // Create Import Model
        $importModel = ImportModel::create([
            "user_id" => $user->id,
            "company_id" => $user->company_id,
            "filename" => $this->filename
        ]);

        // Get immos from files
        $path = $this->path;
        $immos = $this->getImmobilien();

        foreach($immos AS $immo) {
            // Full transactions
            DB::transaction(function () use ($immo, $importModel, $path, $debug) {
                RealEstateImport::make($immo, $importModel, $path, $debug)->import();
            }, 2);
        }

        $this->import = $importModel;

        return $this;
    }


    /**
     * @param string $path
     * @param string|null $filename
     * @param \App\Models\User|null $user
     * @return static[]
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    public static function importFromZip(string $path, string $filename = null, User $user = null): array
    {
        // Create temp and counter
        $temporaryDirectory = (new TemporaryDirectory())->create();
        $imports = [];

        // Extract zip file
        $zip = Zip::open($path);
        $tmpPath = $temporaryDirectory->path("/");
        $zip->extract($tmpPath);

        foreach(scandir($tmpPath) AS $file) {
            if(\Str::endsWith($file, ".xml")) {
                $import = static::makeFromXml($tmpPath . $file);
                $import->saveToDb($user);
                $imports[] = $import;
            }
        }

        // Delete the temporary directory and all the files inside it
        $temporaryDirectory->delete();

        // return the imports
        return $imports;
    }


    public static function makeFromXml(string $path, string $filename = null): self
    {
        $obj = new static();
        $obj->filename = $filename ?: basename($path);
        $obj->path = dirname($path);

        // Data
        $xmlString = file_get_contents($path);

        // Serializer
        $serializer = \JMS\Serializer\SerializerBuilder::create();
        $serializer->configureHandlers(function(\JMS\Serializer\Handler\HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new OpenImmoDatetypeHandler());
        });

        $serializer = $serializer->build();

        // Deserialize
        $obj->openImmo = $serializer->deserialize(
            $xmlString,
            \Ujamii\OpenImmo\API\Openimmo::class,
            'xml'
        );

        return $obj;
    }
}
