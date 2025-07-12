<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
            ->title(__('Categoría creada'))
            ->body(__('La categoría ha sido creada exitosamente.'))
            ->success()
            ->send();

        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label(__('Guardar categoría'))
                ->color('success'),
            $this->getCancelFormAction()
                ->label(__('Volver atrás')),
        ];
    }
}
