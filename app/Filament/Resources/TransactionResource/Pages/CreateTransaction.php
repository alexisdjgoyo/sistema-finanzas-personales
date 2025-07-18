<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Models\Category;
use App\Enums\CategoryType;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
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

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    protected function afterCreate(): ?string
    {
        Notification::make()
            ->title(__('Transacción creada'))
            ->body(__('La transacción ha sido creada exitosamente.'))
            ->success()
            ->send();

        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label(__('Guardar Transacción')),
            // ->color('success'),
            $this->getCancelFormAction()
                ->label(__('Volver atrás')),
        ];
    }

    protected function handleRecordCreation(array $data): Transaction
    {
        info('Handling record creation with data: ', $data);
        DB::beginTransaction();
        try {
            $category = Category::find($data['category_id']);

            if (!$category) {
                throw new \Exception("Categoría no encontrada.");
            }

            if ($category->type === CategoryType::TRANSFER->value) {
                if (!isset($data['destination_account_id']) || $data['destination_account_id'] === $data['account_id']) {
                    throw new \Exception("Para una transferencia, debes seleccionar una cuenta destino diferente a la de origen.");
                }

                $transactionOrigin = new Transaction();
                $transactionOrigin->fill([
                    'account_id' => $data['account_id'],
                    'amount' => $data['amount'],
                    'date' => $data['date'],
                    'description' => $data['description'] . ' (Salida de Transferencia a Cuenta ID: ' . $data['destination_account_id'] . ')',
                    'category_id' => $data['category_id'],
                ]);
                $transactionOrigin->save();

                $transactionDestiny = new Transaction();
                $transactionDestiny->fill([
                    'account_id' => $data['destination_account_id'],
                    'amount' => $data['amount'],
                    'date' => $data['date'],
                    'description' => $data['description'] . ' (Entrada de Transferencia desde Cuenta ID: ' . $data['account_id'] . ')',
                    'category_id' => $data['category_id'],
                ]);
                $transactionDestiny->save();

                $transactionOrigin->transaction_related_id = $transactionDestiny->id;
                $transactionOrigin->save();

                $transactionDestiny->transaction_related_id = $transactionOrigin->id;
                $transactionDestiny->save();

                DB::commit();

                Notification::make()
                    ->title('Transferencia registrada con éxito')
                    ->success()
                    ->send();

                return $transactionOrigin;
            } else {
                $record = parent::handleRecordCreation($data);

                // $montoFinal = ($category->type === TipoCategoria::Egreso) ? -$data['monto'] : $data['monto'];
                // $record = new Transaction();
                // $record->fill([
                //     'account_id' => $data['account_id'],
                //     'monto' => $montoFinal, // El monto ya con el signo si es egreso
                //     'fecha' => $data['fecha'],
                //     'descripcion' => $data['descripcion'],
                //     'categoria_id' => $data['categoria_id'],
                //     // Otros campos
                // ]);
                // $record->save();

                DB::commit();

                // Notification::make()
                //     ->title('Transacción registrada con éxito')
                //     ->success()
                //     ->send();

                return $record;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error al registrar la transacción')
                // ->body($e->getMessage())
                ->danger()
                ->send();
            throw $e;
        }
    }
}
