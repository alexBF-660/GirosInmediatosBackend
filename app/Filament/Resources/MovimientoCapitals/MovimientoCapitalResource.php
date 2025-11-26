<?php

namespace App\Filament\Resources\MovimientoCapitals;

use App\Filament\Resources\MovimientoCapitals\Pages\CreateMovimientoCapital;
use App\Filament\Resources\MovimientoCapitals\Pages\EditMovimientoCapital;
use App\Filament\Resources\MovimientoCapitals\Pages\ListMovimientoCapitals;
use App\Filament\Resources\MovimientoCapitals\Schemas\MovimientoCapitalForm;
use App\Filament\Resources\MovimientoCapitals\Tables\MovimientoCapitalsTable;
use App\Models\MovimientoCapital;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MovimientoCapitalResource extends Resource
{
    protected static ?string $model = MovimientoCapital::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function getNavigationGroup(): ?string
    {
        return 'Distribución de capital';
    }

    public static function form(Schema $schema): Schema
    {
        return MovimientoCapitalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovimientoCapitalsTable::configure($table);
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
            'index' => ListMovimientoCapitals::route('/'),
            'create' => CreateMovimientoCapital::route('/create'),
            'edit' => EditMovimientoCapital::route('/{record}/edit'),
        ];
    }
}
