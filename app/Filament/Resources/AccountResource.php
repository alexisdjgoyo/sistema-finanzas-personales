<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Account;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AccountResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AccountResource\RelationManagers;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return __("Accounts");
    }

    public static function getModelLabel(): string
    {
        return __("Account");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Accounts");
    }
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
                                    ->label('Nombre de la cuenta')
                                    ->required(),
                                Forms\Components\TextInput::make('description')
                                    ->label('Descripción de la cuenta')
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->label('Tipo de cuenta')
                                    ->options([
                                        'debit' => 'Débito',
                                        'credit' => 'Crédito',
                                        'cash' => 'Efectivo',
                                        'investment' => 'Inversión',
                                        'other' => 'Otro',
                                        'savings' => 'Cuenta de ahorro'
                                    ])
                                    ->native(false)
                                    ->required(),
                                Forms\Components\TextInput::make('balance')
                                    ->label('Saldo inicial')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->required()
                                    ->numeric()
                                    ->disabled(fn(?string $operation): bool => $operation !== 'create')
                                    ->default(0),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'debit' => 'Débito',
                        'credit' => 'Crédito',
                        'cash' => 'Efectivo',
                        'investment' => 'Inversión',
                        'other' => 'Otro',
                        'savings' => 'Cuenta de ahorro'
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('balance')
                    ->numeric()
                    ->money()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
