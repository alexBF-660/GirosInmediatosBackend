<?php

namespace App\Filament\Resources\EstadoGiros\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;


class EstadoGirosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->placeholder('Nombre')
                    ->required(),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
            ]);
    }
}
