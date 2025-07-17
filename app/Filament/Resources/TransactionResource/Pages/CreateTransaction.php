<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransactionResource;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title(__('Transacción actualizada'))
            ->body(__('La transacción ha sido actualizada exitosamente.'))
            ->success();
        // ->send();
    }
    // También se puefde usar el método afterSave() para enviar la notificación

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title(__('Transacción eliminada'))
                        ->body(__('La transacción ha sido eliminada exitosamente.'))
                        ->success()
                )
        ];
    }
}
