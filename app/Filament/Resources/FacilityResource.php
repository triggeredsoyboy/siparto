<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $modelLabel = 'fasilitas';

    protected static ?string $pluralModelLabel = 'fasilitas';

    protected static ?string $navigationGroup = 'Kepariwisataan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->string()
                    ->minLength(5)
                    ->maxLength(100)
                    ->autofocus()
                    ->autocomplete(false)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->string()
                    ->minLength(5)
                    ->maxLength(100)
                    ->autocomplete(false)
                    ->required(),
                Forms\Components\Toggle::make('is_free')
                    ->label('Gratis')
                    ->inline(false)
                    ->default(true)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Forms\Set $set) => $set('price', null)),
                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->placeholder('Gratis')
                    ->numeric()
                    ->mask(Support\RawJs::make('$money($input, \',\')'))
                    ->stripCharacters('.')
                    ->autocomplete(false)
                    ->disabled(fn(Forms\Get $get): bool => $get('is_free'))
                    ->dehydrated()
                    ->required(fn(Forms\Get $get): bool => !$get('is_free')),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->lineClamp(3),
                Tables\Columns\IconColumn::make('is_free')
                    ->label('Gratis')
                    ->size(Tables\Columns\IconColumn\IconColumnSize::Medium),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->default('Gratis')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('j M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_free')
                    ->label('Gratis')
                    ->query(fn(Builder $query) => $query->where('is_free', true)),
            ])
            ->filtersTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->label('Filter')
                    ->button()
                    ->size(Support\Enums\ActionSize::Medium),
            )
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('')
                        ->modalWidth(Support\Enums\MaxWidth::TwoExtraLarge)
                        ->modalHeading(fn(Facility $record): string => 'Ubah Fasilitas - ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Fasilitas berhasil diubah.'),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->icon('')
                        ->modalHeading(fn(Facility $record): string => 'Hapus ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Fasilitas berhasil dihapus.'),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->icon(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFacilities::route('/'),
        ];
    }
}
