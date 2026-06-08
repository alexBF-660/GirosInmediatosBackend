<?php

namespace App\Filament\Support;

use Livewire\Component;

class OpensReportInNewTab
{
    public static function dispatch(Component $livewire, string $url): void
    {
        $livewire->js('window.open(' . json_encode($url) . ", '_blank')");
    }
}
