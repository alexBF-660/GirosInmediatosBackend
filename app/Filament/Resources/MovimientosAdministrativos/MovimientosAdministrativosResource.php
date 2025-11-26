<?php

namespace App\Filament\Resources\MovimientosAdministrativos;

use App\Filament\Resources\MovimientosAdministrativos\Pages\CreateMovimientosAdministrativos;
use App\Filament\Resources\MovimientosAdministrativos\Pages\EditMovimientosAdministrativos;
use App\Filament\Resources\MovimientosAdministrativos\Pages\ListMovimientosAdministrativos;
use App\Filament\Resources\MovimientosAdministrativos\Schemas\MovimientosAdministrativosForm;
use App\Filament\Resources\MovimientosAdministrativos\Tables\MovimientosAdministrativosTable;
use App\Models\MovimientosAdministrativos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MovimientosAdministrativosResource extends Resource
{
    protected static ?string $model = MovimientosAdministrativos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'Movimientos Administrativos';

    public static function getNavigationGroup(): ?string
    {
        return 'Distribución de capital';
    }

    public static function form(Schema $schema): Schema
    {
        return MovimientosAdministrativosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovimientosAdministrativosTable::configure($table);
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
            'index' => ListMovimientosAdministrativos::route('/'),
            'create' => CreateMovimientosAdministrativos::route('/create'),
            'edit' => EditMovimientosAdministrativos::route('/{record}/edit'),
        ];
    }
}
