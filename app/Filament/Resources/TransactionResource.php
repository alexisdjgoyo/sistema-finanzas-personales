<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Account;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\CategoryType;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __("Transactions");
    }

    public static function getModelLabel(): string
    {
        return __("Transaction");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Transactions");
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Llenar los campos del formulario")
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(1)
                            ->schema([
                                Forms\Components\Select::make('account_id')
                                    ->label('Cuenta')
                                    ->required()
                                    ->options(
                                        Account::all()->pluck('name', 'id')
                                    )
                                    ->native(false)
                                    ->searchable()
                                    ->live() // Para que el formulario se recargue y evalúe la visibilidad condicional
                                    ->afterStateUpdated(function (Forms\Set $set) {
                                        // Resetea el campo de cuenta destino si la categoría ya no es transferencia
                                        $set('cuenta_destino_id', null);
                                    }),
                                Forms\Components\Select::make('category_id')
                                    ->label('Categoría')
                                    ->required()
                                    ->options(
                                        Category::all()->pluck('name', 'id')
                                    )
                                    ->native(false)
                                    ->searchable()
                                    ->live() // Para que el formulario se recargue y evalúe la visibilidad condicional
                                    ->afterStateUpdated(function (Forms\Set $set) {
                                        // Resetea el campo de cuenta destino si la categoría ya no es transferencia
                                        $set('destination_account_id', null);
                                    }),
                                // --- Campo para la cuenta destino (condicional) ---
                                Forms\Components\Select::make('destination_account_id')
                                    ->label('Cuenta Destino de Transferencia')
                                    ->options(fn(Forms\Get $get) => Account::where('id', '!=', $get('account_id'))->pluck('name', 'id'))
                                    ->required()
                                    ->native(false)
                                    ->searchable()
                                    ->placeholder('Selecciona la cuenta destino')
                                    ->visible(function (Forms\Get $get) {
                                        // Obtiene la categoría seleccionada
                                        $CategoryId = $get('category_id');
                                        if (!$CategoryId) {
                                            return false; // No hay categoría seleccionada
                                        }
                                        // Busca la categoría y verifica su tipo
                                        $category = Category::find($CategoryId);
                                        return $category && $category->type === CategoryType::TRANSFER->value;
                                    })
                                    ->rules([
                                        // Validación adicional para asegurar que no sea la misma cuenta
                                        function (Forms\Get $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                if ($get('category_id') && Category::find($get('category_id'))->type === CategoryType::TRANSFER->value) {
                                                    if ($value === $get('account_id')) {
                                                        $fail("La cuenta de destino no puede ser la misma que la cuenta de origen.");
                                                    }
                                                }
                                            };
                                        },
                                    ]),
                                Forms\Components\TextInput::make('amount')
                                    ->label('Monto')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('description')
                                    ->label('Descripción')
                                    ->maxLength(500),
                                // Forms\Components\RichEditor::make('description')
                                //     ->label('Descripción')
                                //     ->maxLength(500),
                                Forms\Components\FileUpload::make('image')
                                    ->label('Imagen')
                                    ->image()
                                    ->disk('public')
                                    ->directory('transactions'),
                                Forms\Components\DatePicker::make('date')
                                    ->native(false)
                                    ->label('Fecha')
                                    ->format('Y-m-d'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Nro.')
                    ->rowIndex(),
                // Tables\Columns\TextColumn::make('user.name')
                //     ->label('Usuario')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(20)
                    // ->html()
                    // ->width(200)
                    ->label('Descripción')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->width(100)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
                    ->native(false)
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->successNotification(
                        Notification::make()
                            ->title(__('Transacción eliminada'))
                            ->body(__('La transacción ha sido eliminada exitosamente.'))
                            ->success()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
