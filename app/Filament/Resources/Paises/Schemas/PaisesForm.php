<?php

namespace App\Filament\Resources\Paises\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaisesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->placeholder('Nombre del país')
                    ->required(),
            ]);
    }
}
