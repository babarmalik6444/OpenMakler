<?php

namespace App\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;

class HtmlColumn extends TextColumn
{
    protected string $view = 'tables.columns.html-column';
    protected Closure|string|null $subtitleUsing = null;


    public function getFormattedState()
    {
        $html = parent::getFormattedState();
        $state = $this->getState();

        if ($this->subtitleUsing) {
            $html.= '<div class="text-xs text-gray-600">'. $this->evaluate($this->subtitleUsing, [
                    'state' => $state,
                ]) . '</div>';
        }

        return $html;
    }


    public function subtitle(Closure|string $callback): static
    {
        $this->subtitleUsing = $callback;

        return $this;
    }
}
