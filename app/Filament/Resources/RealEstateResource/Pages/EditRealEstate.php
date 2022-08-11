<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\immobilienscout24\Auth\AuthService;
use Illuminate\Support\Facades\Request;
use App\Models\Openimmo\RealEstate;
use App\Models\ScouteApi;

class EditRealEstate extends EditRecord
{
    protected static string $resource = RealEstateResource::class;
    protected $scoutAPIService;
    protected $scoutAPIVerifier;


    function __construct(){
        $this->scoutAPIService = new AuthService;
        if (Request::has('oauth_verifier'))
        {
            //$this->scoutAPIVerifier = Request::get('oauth_verifier');
            $PropertRecord = RealEstate::find(request()->segments()[2]);
            
            $longtermToken = $this->scoutAPIService->getAccessToken(Request::get('oauth_token'), Request::get('oauth_verifier'),$PropertRecord->id);

              if($PropertRecord->scout_api_id =='')
            {
                 $url = $this->scoutAPIService->addProperty($PropertRecord);
                 $scout_api_id = $url['message']['id'];
                 RealEstate::where('id', $PropertRecord->id)->update(['scout_api_id'=>$scout_api_id]);
            } else{
                   $url = $this->scoutAPIService->UpdateProperty($PropertRecord);
            }  
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
        $ScoutData = ScouteApi::latest()->first();
        if($ScoutData != null && $ScoutData->verifier !='')
        {
              if($this->record->scout_api_id =='')
            {
                 $url = $this->scoutAPIService->addProperty($this->record);
                 $scout_api_id = $url['message']['id'];
                 RealEstate::where('id', $this->record->id)->update(['scout_api_id'=>$scout_api_id]);
            } else{
                    if($this->scoutAPIService->UpdateProperty($this->record)){
                        return response('Data Updated Succefully');
                    }
            }  
        }
        else{
            $url = $this->scoutAPIService->getRequestToken($this->record->id);
            return response()->redirectGuest($url);
        }
        
        
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
