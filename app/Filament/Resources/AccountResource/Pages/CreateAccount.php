<?php

namespace App\Filament\Resources\AccountResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AccountResource;

class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate(): ?string
    {
        Notification::make()
            ->title(__('Cuenta creada'))
            ->body(__('La cuenta ha sido creada exitosamente.'))
            ->success()
            ->send();

        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label(__('Guardar cuenta'))
                ->color('success'),
            $this->getCancelFormAction()
                ->label(__('Volver atrás')),
        ];
    }
}
