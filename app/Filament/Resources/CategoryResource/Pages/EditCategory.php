<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title(__('Categoría actualizada'))
            ->body(__('La categoría ha sido actualizada exitosamente.'))
            ->success();
        // ->send();
    }
    // También se puefde usar el método afterSave() para enviar la notificación

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make()
            //     ->successNotification(
            //         Notification::make()
            //             ->title(__('Categoría eliminada'))
            //             ->body(__('La categoría ha sido eliminada exitosamente.'))
            //             ->success()
            //     )
        ];
    }
}
