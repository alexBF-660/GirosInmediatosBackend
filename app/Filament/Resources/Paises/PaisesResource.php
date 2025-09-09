<?php

namespace App\Filament\Resources\Paises;

use App\Filament\Resources\Paises\Pages\CreatePaises;
use App\Filament\Resources\Paises\Pages\EditPaises;
use App\Filament\Resources\Paises\Pages\ListPaises;
use App\Filament\Resources\Paises\Schemas\PaisesForm;
use App\Filament\Resources\Paises\Tables\PaisesTable;
use App\Models\Paises;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaisesResource extends Resource
{
    protected static ?string $model = Paises::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $recordTitleAttribute = 'nombres';

    public static function getNavigationGroup(): ?string
    {
        return 'Parametricas';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return PaisesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaisesTable::configure($table);
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
            'index' => ListPaises::route('/'),
            'create' => CreatePaises::route('/create'),
            'edit' => EditPaises::route('/{record}/edit'),
        ];
    }
}
