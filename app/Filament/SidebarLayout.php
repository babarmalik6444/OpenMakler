<?php

namespace App\Filament;

use WebCaravel\Admin\Forms\Components\RelatedTableField;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class SidebarLayout
{
    protected array $tabs = [];
    protected array $cards = [];
    protected int $tabSpan;
    protected int $cardSpan;
    protected int $defaultColumns = 2;
    protected bool $sidebarOnlyOnEdit;


    public static function make(int $tabSpan = 3, int $cardSpan = 1, $sidebarOnlyOnEdit = true): self
    {
        $obj = new static();
        $obj->tabSpan = $tabSpan;
        $obj->cardSpan = $cardSpan;
        $obj->sidebarOnlyOnEdit = $sidebarOnlyOnEdit;

        return $obj;
    }


    public function addTab(Tab|array $tab, ?string $label = null, ?int $columns = null): self
    {
        if(is_array($tab)) {
            $this->tabs[] = Tab::make($label ?: __("General"))
                ->columns($columns ?: $this->defaultColumns)
                ->schema($tab);
        }
        else {
            $this->tabs[] = $tab;
        }

        return $this;
    }


    public function addCard(array $schema): self
    {
        $this->cards[] = Card::make($schema)
            ->visible($this->sidebarOnlyOnEdit ? fn(Model $record): bool => $record->exists : true);

        return $this;
    }


    public function toArray(): array
    {
        $tabs = Tabs::make("Main Tabs")
            ->tabs($this->tabs)
            ->columnSpan(2);

        if ($this->cards) {
            $cards = Grid::make(1)
                ->schema($this->cards)
                ->columnSpan($this->cardSpan);

            return [
                Grid::make()
                    ->visible()
                    ->schema([
                        $tabs->columnSpan($this->tabSpan),
                        $cards,
                    ])
                    ->columns($this->tabSpan + $this->cardSpan),
            ];
        }
        else {
            return [$tabs];
        }
    }


    //public function addRelation(string $relatedTableClass, string $label): self
    //{
    //    return $this->addTab([
    //        RelatedTableField::make($relatedTableClass)
    //    ], $label, 1);
    //}


    public function setDefaultColumns(int $defaultColumns): self
    {
        $this->defaultColumns = $defaultColumns;

        return $this;
    }
}
