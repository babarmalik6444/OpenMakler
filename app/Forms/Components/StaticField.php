<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class StaticField extends Placeholder
{
    protected $link = null;
    protected bool $linkTargetBlank = false;
    protected $linkText = null;


    protected function setUp(): void
    {
        parent::setUp();
        $name = $this->name;

        $this->content = function($record) use ($name) {
            $tmp = explode(".", $name);

            if(count($tmp) == 1) {
                return $record->$name;
            }
            else if(count($tmp) == 2) {
                $rel = $tmp[0];
                $key = $tmp[1];

                return optional($record->$rel)->$key;
            }
            else {
                throw new \Exception("Unknown StaticField name. Please only use one dot max.");
            }
        };
    }


    public function link(callable|string $link, bool $linkTargetBlank = false, null|callable|string $linkText = null): static
    {
        $this->link = $link;
        $this->linkTargetBlank = $linkTargetBlank;
        $this->linkText = $linkText;

        return $this;
    }


    public function getContent()
    {
        $content = $this->evaluate($this->content);

        if($link = $this->evaluate($this->link)) {
            $linkText = $this->evaluate($this->linkText) ?:  $content;
            $content = '<a href="' . $link .'" class="link"' . ($this->linkTargetBlank ? ' target="_blank"' : '') .'>' . $linkText . '</a>';
        }

        return new HtmlString($content);
    }
}
