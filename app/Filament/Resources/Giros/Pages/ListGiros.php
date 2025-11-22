<?php

namespace App\Filament\Resources\Giros\Pages;

use App\Filament\Resources\Giros\GirosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Models\Sucursales;


class ListGiros extends ListRecords
{
    protected static string $resource = GirosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        // Verifica roles
        if ($user->hasRole(['Gerente de sucursal', 'Operador de sucursal'])) {
            $this->notifyCapitalLevel();
            return [
                \App\Filament\Resources\Sucursales\Widgets\CapitalSucursalStats::class,
            ];
        }

        // Si el usuario no tiene rol permitido, no mostrar nada
        return [];
    }

    protected function notifyCapitalLevel(): void
    {
        $user = auth()->user();

        $sucursal = Sucursales::find($user->sucursal_id);
        if (! $sucursal) {
            return;
        }

        $capital = $sucursal->capital_actual;

        // Determinar mensaje y tipo según capital
        if ($capital > 8000) {
            $message = 'Nivel de capital: Estable';
            $color = 'success';
        } elseif ($capital > 5000) {
            $message = 'Nivel de capital: Regular';
            $color = 'info';
        } elseif ($capital > 1000) {
            $message = 'Nivel de capital: Bajo';
            $color = 'warning';
        } else {
            $message = 'Nivel de capital: Muy bajo';
            $color = 'danger';
        }

        Notification::make()
            ->title('Atención al capital')
            ->body($message)
            ->color($color)
            ->send();
    }
}
