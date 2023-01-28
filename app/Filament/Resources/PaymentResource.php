<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers\UserRelationManager;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use Illuminate\Database\Eloquent\Model;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $navigationGroup = 'Manager Resources';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->username;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['username', 'amount'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->placeholder('John Doe')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                ->money(prefix: 'R$', isSigned: false)
                            )
                            ->required(),
                        TimePickerField::make('payment_time')
                            ->okLabel('Confirm')
                            ->cancelLabel('Cancel')
                            ->required(),
                        Forms\Components\DatePicker::make('payment_date')
                            ->placeholder('Jan 5, 2023')
                            ->maxDate(now())
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('amount')
                    ->prefix('$')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->searchable()
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('payment_time')
                    ->time()
                    ->searchable()
                    ->sortable()
            ])->defaultSort('id')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
