<?php

namespace App\Tables\Columns;

use Illuminate\Database\Eloquent\Model;

class BelongsToColumn extends HtmlColumn
{
    public function resource(string $resource): static
    {
        $name = explode(".", $this->name)[0];

        return $this->url(function(Model $record) use ($resource, $name): ?string {
            return $record && $record->$name ? route($resource::getRouteBaseName().".edit", [
                "record" => $record->$name
            ]) : null;
        });
    }
}
