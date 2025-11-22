<?php

namespace App\Filament\Resources\DistribucionCapitals;

use App\Filament\Resources\DistribucionCapitals\Pages\CreateDistribucionCapital;
use App\Filament\Resources\DistribucionCapitals\Pages\EditDistribucionCapital;
use App\Filament\Resources\DistribucionCapitals\Pages\ListDistribucionCapitals;
use App\Filament\Resources\DistribucionCapitals\Schemas\DistribucionCapitalForm;
use App\Filament\Resources\DistribucionCapitals\Tables\DistribucionCapitalsTable;
use App\Models\DistribucionCapital;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DistribucionCapitalResource extends Resource
{
    protected static ?string $model = DistribucionCapital::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'no';

    public static function getNavigationGroup(): ?string
    {
        return 'Distribución de capital';
    }

    public static function form(Schema $schema): Schema
    {
        return DistribucionCapitalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DistribucionCapitalsTable::configure($table);
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
            'index' => ListDistribucionCapitals::route('/'),
            'create' => CreateDistribucionCapital::route('/create'),
            'edit' => EditDistribucionCapital::route('/{record}/edit'),
        ];
    }
}
