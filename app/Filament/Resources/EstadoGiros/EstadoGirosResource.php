<?php

namespace App\Filament\Resources\EstadoGiros;

use App\Filament\Resources\EstadoGiros\Pages\CreateEstadoGiros;
use App\Filament\Resources\EstadoGiros\Pages\EditEstadoGiros;
use App\Filament\Resources\EstadoGiros\Pages\ListEstadoGiros;
use App\Filament\Resources\EstadoGiros\Schemas\EstadoGirosForm;
use App\Filament\Resources\EstadoGiros\Tables\EstadoGirosTable;
use App\Models\Estado_Giros;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstadoGirosResource extends Resource
{
    protected static ?string $model = Estado_Giros::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'nombre';

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
        return EstadoGirosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstadoGirosTable::configure($table);
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
            'index' => ListEstadoGiros::route('/'),
            'create' => CreateEstadoGiros::route('/create'),
            'edit' => EditEstadoGiros::route('/{record}/edit'),
        ];
    }
}
