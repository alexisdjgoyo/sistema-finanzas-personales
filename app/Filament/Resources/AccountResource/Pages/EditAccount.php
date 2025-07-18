<?php

namespace App\Filament\Resources\AccountResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AccountResource;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title(__('Cuenta actualizada'))
            ->body(__('La cuenta ha sido actualizada exitosamente.'))
            ->success();
    }
    // También se puefde usar el método afterSave() para enviar la notificación

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title(__('Cuenta eliminada'))
                        ->body(__('La cuenta ha sido eliminada exitosamente.'))
                        ->success()
                )
        ];
    }
}
