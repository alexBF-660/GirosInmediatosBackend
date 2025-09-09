<?php

namespace App\Filament\Resources\Giros;

use App\Filament\Resources\Giros\Pages\CreateGiros;
use App\Filament\Resources\Giros\Pages\EditGiros;
use App\Filament\Resources\Giros\Pages\ListGiros;
use App\Filament\Resources\Giros\Schemas\GirosForm;
use App\Filament\Resources\Giros\Tables\GirosTable;
use App\Models\Giros;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GirosResource extends Resource
{
    protected static ?string $model = Giros::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'id';

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
        return GirosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GirosTable::configure($table);
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
            'index' => ListGiros::route('/'),
            'create' => CreateGiros::route('/create'),
            'edit' => EditGiros::route('/{record}/edit'),
        ];
    }
}
