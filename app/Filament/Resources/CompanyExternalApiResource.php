<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyExternalApiResource\Pages;
use App\Filament\Resources\CompanyExternalApiResource\RelationManagers;
use App\Filament\SidebarLayout;
use App\Models\CompanyExternalApi;
use App\Models\ExternalApi;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyExternalApiResource extends Resource
{
    protected static ?string $model = CompanyExternalApi::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe';
    protected static ?string $label = "Schnittstelle";
    protected static ?string $pluralLabel = "Schnittstellen";


    public static function form(Form $form): Form
    {
        return $form
            ->schema(SidebarLayout::make()
                ->addTab([
                    Forms\Components\Select::make("external_api_id")
                        ->label("Anbieter")
                        ->options(
                            ExternalApi::query()
                                ->whereNotIn("id", auth()->user()->company->companyExternalApis->pluck("id")->toArray())
                                ->pluck("name", "id")
                        )
                        ->visible(fn($record): bool => !$record || !$record->exists)
                        ->columnSpan(2)
                        ->required(),
                    Forms\Components\Placeholder::make("externalApi.name")
                        ->content(fn($record) => $record->externalApi->getName())
                        ->visible(fn($record): bool => $record && $record->exists)
                        ->label("Anbieter")
                        ->columnSpan(2),
                    Forms\Components\Grid::make()
                        ->schema(fn(?CompanyExternalApi $record) => $record && $record->exists && $record->externalApi ? $record->externalApi->driver()->schema() : [])
                ])
                ->toArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("externalApi.name")
                    ->label("Anbieter")
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListCompanyExternalApis::route('/'),
            'create' => Pages\CreateCompanyExternalApi::route('/create'),
            'edit' => Pages\EditCompanyExternalApi::route('/{record}/edit'),
        ];
    }
}
