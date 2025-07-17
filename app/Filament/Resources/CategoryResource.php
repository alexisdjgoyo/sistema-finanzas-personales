<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\CategoryResource\Pages;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("Llenar los campos del formulario")
                    ->schema([
                        Forms\Components\Grid::make()
                            // ->columns(2)
                            // ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre de la categoría')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('description')
                                    ->label('Descripción de la categoría')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'income' => 'Ingreso',
                                        'spending' => 'Gasto',
                                    ])
                                    ->label('Tipo de movimiento')
                                    ->native(false)
                                    ->searchable(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Nro.')
                    // ->sortable()
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de movimiento')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'income' => 'Ingreso',
                        'spending' => 'Gasto',
                        // default => 'Desconocido',
                    })
                    ->searchable(),
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
                SelectFilter::make('type')
                    ->label('Tipo de movimiento')
                    ->options([
                        'income' => 'Ingreso',
                        'spending' => 'Gasto',
                    ])
                    ->placeholder('Todos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button(),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->successNotification(
                        Notification::make()
                            ->title(__('Categoría eliminada'))
                            ->body(__('La categoría ha sido eliminada exitosamente.'))
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
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
