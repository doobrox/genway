<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SimpleLayout extends Component
{
    public $title;
    public $keywords;
    public $description;

    public function __construct($title = null, $keywords = null, $description = null)
    {
        $this->title = $title != null && $title != '' ? $title : null;
        $this->keywords = $keywords != null && $keywords != '' ? $keywords : null;
        $this->description = $description != null && $description != '' ? $description : null;
    }
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $setari = setari([
            'TITLU_NUME_SITE',
            'DEFAULT_META_KEYWORDS',
            'DEFAULT_META_DESCRIPTION',
        ]);
        return view('layouts.simple', [
            'page_title' => $this->title ? $this->title.' | '.$setari['TITLU_NUME_SITE'] : $setari['TITLU_NUME_SITE'] ?? '',
            'meta_keywords' => $this->keywords ?? $setari['DEFAULT_META_KEYWORDS'] ?? '',
            'meta_description' => $this->description ?? $setari['DEFAULT_META_DESCRIPTION'] ?? '',
        ]);
    }
}
