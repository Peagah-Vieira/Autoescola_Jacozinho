<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\Widgets\PaymentStatsOverview;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;
use Illuminate\Database\Eloquent\Model;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $navigationLabel = 'Pagamentos';

    protected static ?string $navigationGroup = 'Recursos';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->fullname;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['fullname', 'amount'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('fullname')
                            ->label('Nome Completo')
                            ->placeholder('John Doe Maia')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Valor')
                            ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                ->money(prefix: 'R$', isSigned: false)
                            )
                            ->required(),
                        TimePickerField::make('payment_time')
                            ->label('Hora de Pagamento')
                            ->okLabel('Confirm')
                            ->cancelLabel('Cancel')
                            ->required(),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Data de Pagamento')
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
                Tables\Columns\TextColumn::make('fullname')
                    ->label('Nome Completo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('amount')
                    ->label('Valor')
                    ->prefix('$')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Hora de Pagamento')
                    ->searchable()
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('payment_time')
                    ->label('Data de Pagamento')
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

    public static function getWidgets(): array
    {
        return [
            PaymentStatsOverview::class,
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
