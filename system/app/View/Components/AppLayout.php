<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public $title;
    public $keywords;
    public $description;
    public $canonical;

    public function __construct($title = null, $keywords = null, $description = null, $canonical = null)
    {
        $this->title = $title != null && $title != '' ? $title : null;
        $this->keywords = $keywords != null && $keywords != '' ? $keywords : null;
        $this->description = $description != null && $description != '' ? $description : null;
        $this->canonical = !empty($canonical) ? $canonical : null;
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
        return view('layouts.app', [
            'page_title' => $this->title ? $this->title.' - '.$setari['TITLU_NUME_SITE'] : $setari['TITLU_NUME_SITE'] ?? '',
            'meta_keywords' => $this->keywords ?? $setari['DEFAULT_META_KEYWORDS'] ?? '',
            'meta_description' => $this->description ?? $setari['DEFAULT_META_DESCRIPTION'] ?? '',
            'canonical' => $this->canonical ?: null,
        ]);
    }
}
