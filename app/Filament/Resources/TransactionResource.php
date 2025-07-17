<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Llenar los campos del formulario")
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(1)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Usuario')
                                    ->required()
                                    ->options(
                                        User::all()->pluck('name', 'id')
                                    ),
                                Forms\Components\Select::make('category_id')
                                    ->label('Categoría')
                                    ->required()
                                    ->options(
                                        Category::all()->pluck('name', 'id')
                                    )
                                    ->native(false)
                                    ->searchable(),
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
