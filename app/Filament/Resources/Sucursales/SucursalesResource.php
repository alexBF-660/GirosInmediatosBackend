<?php

namespace App\Filament\Resources\Sucursales;

use App\Filament\Resources\Sucursales\Pages\CreateSucursales;
use App\Filament\Resources\Sucursales\Pages\EditSucursales;
use App\Filament\Resources\Sucursales\Pages\ListSucursales;
use App\Filament\Resources\Sucursales\Schemas\SucursalesForm;
use App\Filament\Resources\Sucursales\Tables\SucursalesTable;
use App\Models\Sucursales;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SucursalesResource extends Resource
{
    protected static ?string $model = Sucursales::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function getNavigationGroup(): ?string
    {
        return 'Menu Principal';
    }

    public static function getNavigationSort(): ?int
    {
        return 1; 
    }

    public static function form(Schema $schema): Schema
    {
        return SucursalesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SucursalesTable::configure($table);
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
            'index' => ListSucursales::route('/'),
            'create' => CreateSucursales::route('/create'),
            'edit' => EditSucursales::route('/{record}/edit'),
        ];
    }
}
