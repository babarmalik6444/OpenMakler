<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealEstateResource\Pages;
use App\Filament\Resources\RealEstateResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Forms\Components\CountrySelect;
use App\Forms\Components\StaticField;
use App\Models\CompanyOffice;
use App\Models\Openimmo\Anhang;
use App\Models\Openimmo\Maincategory;
use App\Models\Openimmo\RealEstate;
use App\Models\Openimmo\RealestateInfrastruktur;
use App\Models\Openimmo\ZustandArt;
use App\Tables\Columns\BelongsToColumn;
use App\Tables\Columns\HtmlColumn;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Fieldset;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class RealEstateResource extends Resource
{
    protected static ?string $model = RealEstate::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = "Immobilie";
    protected static ?string $pluralLabel = "Immobilien";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                SidebarLayout::make(4, 2)
                ->setDefaultColumns(2)
                ->addTab(static::form_base())
                ->addTab(static::form_geo())
                ->addTab(static::form_preis())
                ->addTab(static::form_ausstattung())
                ->addTab(static::form_flachen())
                ->addTab(static::form_zustand_angaben())
                ->addTab(static::form_verwaltung_objekt())
                ->addTab(static::form_infrastruktur())
                ->addTab(static::form_objektkategorie())
                ->addTab(static::form_freitexte())
                ->addTab(static::form_anhaenge())
                ->addCard([
                    StaticField::make("creator.name")
                        ->link(fn($record) => route(UserResource::getRouteBaseName() . ".edit", $record->creator))
                        ->label("Angelegt von"),
                    StaticField::make("created_at")
                        ->label("Angelegt am"),
                    StaticField::make("updated_at")
                        ->label("Geändert am"),
                    StaticField::make("Expose öffentlich")
                        ->label("Expose öffentlich")
                        ->content(fn($record): string => $record->isActive() ? 'Öffentlich' : 'Nicht Öffentlich'),
                    StaticField::make("Expose Ansicht")
                        ->visible(fn($record): bool => $record->isActive())
                        ->link(fn($record): string => $record->getUrl(), true, "Ansicht öffnen")
                        ->label("Expose Ansicht"),
                ])
                ->toArray());
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")
                    ->sortable(),
                HtmlColumn::make('objekttitel')
                    ->subtitle(fn($record
                    ) => $record->verwaltung_techn_objektnr_intern ?? ('Objektnr.: '.$record->verwaltung_techn_objektnr_intern))
                    ->label(__("Titel"))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                HtmlColumn::make('created_at')
                    ->label(__("Erstellt am"))
                    ->date("Y-m-d")
                    ->subtitle(fn($record) => $record->updated_at->format("H:i"))
                    ->sortable(),
                HtmlColumn::make('updated_at')
                    ->label(__("Aktualisiert am"))
                    ->date("Y-m-d")
                    ->subtitle(fn($record) => $record->updated_at->format("H:i"))
                    ->sortable(),
                BelongsToColumn::make("companyOffice.name")
                    ->resource(CompanyOfficeResource::class)
                    ->label(__("Zweigstelle")),
                BelongsToColumn::make("agent.name")
                    ->resource(UserResource::class)
                    ->label(__("Betreuer")),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealEstates::route('/'),
            'create' => Pages\CreateRealEstate::route('/create'),
            'edit' => Pages\EditRealEstate::route('/{record}/edit'),
        ];
    }


    private static function hasOneForm(string $title, string $relation, array $schema, int $cols = 2)
    {
        return Forms\Components\Tabs\Tab::make($title)->schema([
            Forms\Components\Grid::make($cols)
                ->relationship($relation)
                ->schema($schema)
        ]);
    }


    private static function toggleFields(array $toggles)
    {
        $fields = [];

        foreach($toggles AS $group => $items) {
            $schema = [];
            foreach($items AS $toggle) {
                $schema[] = Forms\Components\Toggle::make($toggle)
                    ->label(Str::headline($toggle))
                    ->inline(false);
            }
            $fields[] = Forms\Components\Fieldset::make($group)->schema($schema)->columns(3);
        }

        return $fields;
    }


    private static function form_base()
    {
        return [
            // objekttitel
            Forms\Components\TextInput::make("objekttitel")
                ->columnSpan(2)
                ->required(),

            // Agent + Office
            BelongsToSelect::make('agent_id')
                ->label("Mitarbeiter")
                ->required()
                ->relationship('agent', 'name', fn (Builder $query) => $query)
                ->searchable(),
            BelongsToSelect::make("company_office_id")
                ->label("Firmenstandort")
                ->disablePlaceholderSelection()
                ->default(fn() => CompanyOffice::query()->first("id")->id)
                ->relationship("companyOffice", "name", fn(Builder $query) => $query->orderBy("name"))
                ->required(),

            // IDS
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\TextInput::make('verwaltung_techn_objektnr_intern')
                    ->label("Objektnr. intern")
                    ->maxLength(100)
                    ->required(),
                Forms\Components\TextInput::make('verwaltung_techn_objektnr_extern')
                    ->maxLength(100)
                    ->label("Objektnr. extern"),
                Forms\Components\TextInput::make('verwaltung_techn_openimmo_obid')
                    ->maxLength(100)
                    ->label("OpenImmo OBID"),
            ])->columnSpan(2),

            // verwaltung_techn_aktiv_von|verwaltung_techn_aktiv_bis
            Forms\Components\DatePicker::make("verwaltung_techn_aktiv_von")
                ->displayFormat("d.m.Y")
                ->label("Aktiv von"),
            Forms\Components\DatePicker::make("verwaltung_techn_aktiv_bis")
                ->displayFormat("d.m.Y")
                ->label("Aktiv bis"),

            BelongsToSelect::make("maincategory_id")
                ->label("Objektart")
                ->relationship("mainCategory", "name")
                ->reactive()
                ->afterStateUpdated(fn(callable $set) => $set("subcategory_id", null)),
            Forms\Components\Select::make("subcategory_id")
                ->label("Objekttyp")
                ->required()
                ->options(function(callable $get) {
                    $mainCat = Maincategory::find($get("maincategory_id"));

                    return $mainCat ? $mainCat->subcategories->pluck("name", "id") : [];
                }),
        ];
    }


    private static function form_geo()
    {
        return static::hasOneForm("Geo", "geo", [
            // PLZ + Ort
            Forms\Components\TextInput::make("plz")
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make("ort")
                ->required(),

            // Strasse + hausnummer
            Forms\Components\TextInput::make("strasse")->required(),
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make("hausnummer")->required(),
                Forms\Components\TextInput::make("wohnungsnr"),
            ])->columnSpan(1),

            // Land + Bundesland
            Forms\Components\TextInput::make("bundesland"),
            CountrySelect::make("land")->required(),
            Forms\Components\TextInput::make("regionaler_zusatz")
                ->label("Regionale Zusatz")
                ->placeholder("z.B Landschaft/Kulturlandschaft: Plattensee; Stadtgebiet: Pöseldorf")
                ->columnSpan(2),

            // etage
            Forms\Components\TextInput::make("etage")
                ->numeric(),
            Forms\Components\TextInput::make("anzahl_etagen")
                ->label("Anzahl Etagen")
                ->numeric(),

            // Geo
            Forms\Components\TextInput::make("geokoordinaten.lat")
                ->label("Breitengrad")
                ->numeric(),
            Forms\Components\TextInput::make("geokoordinaten.lng")
                ->label("Längengrad")
                ->numeric(),

            // Karte
            //OSMMap::make('geokoordinaten')
            //    ->label("Karte")
            //    ->showMarker()
            //    ->draggable()
        ]);
    }


    private static function form_preis()
    {
        return static::hasOneForm("Preise", "preis", [
            // Kaufpreise
            Forms\Components\TextInput::make("kaufpreis")
                ->numeric()
                ->postfix("€")
                ->required(),
            Forms\Components\TextInput::make("kaufpreisnetto")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("kaufpreisbrutto")
                ->numeric()
                ->postfix("€"),
            Forms\Components\Toggle::make("zzg_mehrwertsteuer")
                ->label("Zzg Mehrwertsteuer")
                ->inline(false),

            // Miete
            Forms\Components\TextInput::make("nettokaltmiete")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("kaltmiete")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("warmmiete")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("mietzuschlaege")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("hauptmietzinsnetto")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("hauptmietzinsust")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("pauschalmiete")
                ->numeric()
                ->postfix("€"),

            // Betriebskosten
            Forms\Components\TextInput::make("betriebskostennetto")
                ->numeric()
                ->postfix("€"),
            Forms\Components\TextInput::make("betriebskostenust")
                ->numeric()
                ->postfix("€"),

            // mietpreis
            Forms\Components\TextInput::make("mietpreis_pro_qm")
                ->numeric()
                ->postfix("€"),
            //Forms\Components\TextInput::make("mieteinnahmen_ist")
            //    ->numeric()
            //    ->postfix("€"),

            // Included
            Forms\Components\Toggle::make("heizkosten_enthalten")
                ->inline(false),
            Forms\Components\TextInput::make("heizkosten")
                ->numeric()
                ->postfix("€"),
        ], 3);
    }


    private static function form_ausstattung()
    {
        $toggles = [
            "Befeuerung" => [
                "befeuerung_oel","befeuerung_gas","befeuerung_elektro","befeuerung_alternativ","befeuerung_solar","befeuerung_erdwaerme","befeuerung_luftwp","befeuerung_fern","befeuerung_block","befeuerung_wasser_elektro","befeuerung_pellet"
            ],
            "Stellplatz" => [
                "stellplatzart_garage","stellplatzart_tiefgarage","stellplatzart_carport","stellplatzart_freiplatz","stellplatzart_parkhaus","stellplatzart_duplex"
            ],
            "Sonstiges" => [
                "wintergarten","sauna","barrierefrei"
            ]
        ];

        return static::hasOneForm("Ausstattung", "ausstattung", static::toggleFields($toggles), 3);
    }


    private static function form_flachen()
    {
        return static::hasOneForm("Fläche", "flaechen", [
            Forms\Components\TextInput::make("wohnflaeche")->numeric()->postfix("m")->required(),
            Forms\Components\TextInput::make("grundstuecksflaeche")->numeric()->postfix("m"),
            Forms\Components\TextInput::make("kellerflaeche")->numeric()->postfix("m"),
            Forms\Components\TextInput::make("gartenflaeche")->numeric()->postfix("m"),
            Forms\Components\TextInput::make("nutzflaeche")->numeric()->postfix("m"),
            Forms\Components\TextInput::make("balkon_terrasse_flaeche")->numeric()->postfix("m"),
            Forms\Components\TextInput::make("vermietbare_flaeche")->numeric()->postfix("m"),
            Forms\Components\TextInput::make("anzahl_wohneinheiten")->numeric(),
            Forms\Components\TextInput::make("anzahl_wohn_schlafzimmer")->numeric()->required(),
            Forms\Components\TextInput::make("anzahl_balkone")->numeric(),
            Forms\Components\TextInput::make("anzahl_terrassen"),
            Forms\Components\TextInput::make("anzahl_logia"),
            Forms\Components\Toggle::make("einliegerwohnung"),
        ], 3);
    }


    private static function form_zustand_angaben()
    {
        return static::hasOneForm("Zustand", "zustand_angaben", [
            Forms\Components\TextInput::make("baujahr")->numeric()->minValue(1800)->maxValue(date("Y")),
            Forms\Components\TextInput::make("letztemodernisierung")->label("Letzte Modernisierung"),
            Forms\Components\Select::make("zustand_art")->options(ZustandArt::all("name", "id")->pluck("name", "id")),

            Forms\Components\Fieldset::make("Energiepass")->schema([
                Forms\Components\TextInput::make("energiepass_energieverbrauchkennwert")->numeric()->label("Energieverbrauchkennwert"),
                Forms\Components\Toggle::make("energiepass_mitwarmwasser")->inline()->label("Mit Warmwasser"),
                Forms\Components\TextInput::make("energiepass_gueltig_bis")->label("Gültig bis"),
                Forms\Components\TextInput::make("energiepass_primaerenergietraeger")->label("Primärenergieträger"),
                Forms\Components\TextInput::make("energiepass_stromwert")->label("Stromwert"),
                Forms\Components\TextInput::make("energiepass_waermewert")->label("Wärmewert"),
                Forms\Components\TextInput::make("energiepass_wertklasse")->maxLength(2)->label("Wertklasse"),
            ])
        ], 2);
    }


    private static function form_verwaltung_objekt()
    {
        return static::hasOneForm("Verwaltung", "verwaltung_objekt", [
            Forms\Components\TextInput::make("verfuegbar_ab")->label("Verfügbar ab"),
            Forms\Components\DatePicker::make("abdatum")->label("Ab Datum")->displayFormat("d.m.Y")->format("Y-m-d"),
            Forms\Components\DatePicker::make("bisdatum")->label("Bis Datum")->displayFormat("d.m.Y"),
            Forms\Components\Toggle::make("haustiere"),
            Forms\Components\Toggle::make("denkmalgeschuetzt"),
            Forms\Components\Toggle::make("gewerbliche_nutzung"),
            Forms\Components\Toggle::make("hochhaus"),
            Forms\Components\Toggle::make("vermietet"),
        ], 2);
    }


    private static function form_infrastruktur()
    {
        $distanzen = [];
        foreach(["distanz_zu_flughafen", "distanz_zu_fernbahnhof", "distanz_zu_autobahn", "distanz_zu_us_bahn", "distanz_zu_bus", "distanz_zu_kindergaerten", "distanz_zu_grundschule", "distanz_zu_hauptschule", "distanz_zu_realschule", "distanz_zu_gesamtschule", "distanz_zu_gymnasium", "distanz_zu_zentrum", "distanz_zu_einkaufsmoeglichkeiten", "distanz_zu_gaststaetten"] AS $item) {
            $distanzen[] = Forms\Components\TextInput::make($item)
                ->numeric()
                ->label(\Str::headline(substr($item, 7)))
                ->postfix("km");
        }

        return static::hasOneForm("Infrastruktur", "infrastruktur", [
            // Ausblick
            Forms\Components\Select::make("ausblick")->options(RealestateInfrastruktur::getAusblickOptions()),
            Forms\Components\Fieldset::make("Distanzen")->schema($distanzen)->columns(3)
        ], 1);
    }


    private static function form_objektkategorie()
    {
        $toggles = [
            "Nutzungsart" => [
                "nutzungsart_wohnen","nutzungsart_gewerbe","nutzungsart_anlage","nutzungsart_waz"
            ],
            "Vermarktungsart" => [
                "vermarktungsart_kauf","vermarktungsart_miete_pacht","vermarktungsart_erbpacht","vermarktungsart_leasing"
            ],
        ];

        return static::hasOneForm("Objektkategorie", "objektkategorie", static::toggleFields($toggles), 3);
    }


    private static function form_freitexte()
    {
        return static::hasOneForm("Freitexte", "freitexte", [
            Forms\Components\Textarea::make("lage")->label("Lage"),
            Forms\Components\Textarea::make("ausstatt_beschr")->label("Ausstattung Beschreibung"),
            Forms\Components\Textarea::make("objektbeschreibung")->label("Objekt Beschreibung"),
            Forms\Components\Textarea::make("sonstige_angaben")->label("Sonstige Angaben"),
        ], 1);
    }


    private static function form_anhaenge()
    {
        return Forms\Components\Tabs\Tab::make("Anhänge")->schema([
            Forms\Components\HasManyRepeater::make("anhaenge")
                //->enableOrdering()
                ->relationship('anhaenge')
                ->label("Bilder")
                ->schema([
                    Forms\Components\Placeholder::make("img")
                        ->label("Bild")
                        ->hidden(fn($record): bool => (bool) !optional($record)->exists)
                        ->content(fn($record) => new HtmlString('<a href="' . $record->getUrl() .'" target="_blank"><img src="' . $record->getUrl() .'" /></a>')),
                    Forms\Components\FileUpload::make("filename")
                        ->placeholder("Datei auswählen oder einfach reinziehen")
                        ->hidden(fn($record): bool => (bool) optional($record)->exists)
                        ->label('Bild')
                        ->saveUploadedFileUsing(function(\Livewire\TemporaryUploadedFile $file, $record) {
                            $model = $this->model;
                            $filename = \Str::random(15) . "." . $file->getClientOriginalExtension();
                            $file->storePubliclyAs("/public/immo-images/" . $model->id . "/",  $filename);

                            return $filename;
                        })
                        ->image()
                        ->required(),
                    Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\TextInput::make("anhangtitel")
                            ->required(),
                        Forms\Components\Select::make("gruppe")
                            ->default(Anhang::GRUPPE_BILD)
                            ->required()
                            ->options(Anhang::getImageGruppenSelect()),
                        StaticField::make("created_at")
                            ->label("Upload Datum")
                            ->hidden(fn($record): bool => (bool) !optional($record)->exists)
                    ])
                ])
                ->columns(2)
                ->createItemButtonLabel("Bild hinzufügen")
        ]);
    }
}
