<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\immobilienscout24\Auth\AuthService;
use Illuminate\Support\Facades\Request;
use App\Models\Openimmo\RealEstate;

class EditRealEstate extends EditRecord
{
    protected static string $resource = RealEstateResource::class;
    protected $scoutAPIService;
    protected $scoutAPIVerifier;


    function __construct(){
        $this->scoutAPIService = new AuthService;
        if (Request::has('oauth_verifier'))
        {
            $this->scoutAPIVerifier = Request::get('oauth_verifier');
            $this->scoutAPIService->getAccessToken($this->scoutAPIVerifier); 
            dd($this->scoutAPIVerifier);   
        }
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make("openimmo")
                ->action("openImmoDownload"),
                Actions\Action::make("ExportAPI")
                ->action("ExportAPI"),
                Actions\Action::make("DeleteAPI")
                ->action("DeleteAPI"),
            Actions\Action::make("archive")
                ->label(fn(): string => $this->record->published ? "Archivieren" : "Reaktivieren")
                ->action("archiveRealestate")
        ];
    }


    public function openImmoDownload()
    {
        $xml = $this->record->getOpenImmoXml();

        return response()->streamDownload(function () use ($xml) {
            echo $xml;
        }, $this->record->id . "_openimmo.xml");
    }

    public function ExportAPI()
    {
        //dd($this->record->scout_api_id);
        // dd($this->scoutAPIService->getRequestToken($this->record));
        // if($this->record->scout_api_id =='')
        // { 
        //      $url = $this->scoutAPIService->addProperty($this->record);
        //      $scout_api_id = $url['message']['id'];
        //      RealEstate::where('id', $this->record->id)->update(['scout_api_id'=>$scout_api_id]);
        // } else{
        //        $url = $this->scoutAPIService->UpdateProperty($this->record); 
        // }
        $url = $this->scoutAPIService->getRequestToken($this->record);
        stream_get_contents(fopen($url, "r"));
        return response()->redirectGuest($url);
    }

    public function DeleteAPI()
    {
        //dd($this->record);
        $url = $this->scoutAPIService->DeleteProperty($this->record);
        return response($url);
    }


    public function archiveRealestate()
    {
        if($this->record->published) {
            $this->record->archiveIt();
        }
        else {
            $this->record->reactivateIt();
        }

        $this->notify('success', "Wurde erfolgreich " . ($this->record->published ? "archiviert" : "reaktiviert") . ".");
    }
}
