<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmenityResource\Pages;
use App\Filament\Resources\AmenityResource\RelationManagers;
use App\Models\Amenity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static ?string $modelLabel = 'amenitas';

    protected static ?string $pluralModelLabel = 'amenitas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Fasilitas, Amenitas & Layanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->autofocus()
                    ->autocomplete(false)
                    ->string()
                    ->minLength(5)
                    ->maxLength(100)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->hint('Otomatis terisi')
                    ->autocomplete(false)
                    ->string()
                    ->minLength(5)
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->readOnly(),
                Forms\Components\Checkbox::make('is_free')
                    ->label('Gratis')
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(fn(Forms\Set $set, ?int $state) => $set('price', 0)),
                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->autocomplete(false)
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10000000)
                    ->default(0)
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                    ->stripCharacters([',', '.'])
                    ->disabled(fn(Forms\Get $get): bool => $get('is_free'))
                    ->required(fn(Forms\Get $get): bool => !$get('is_free')),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_free')
                    ->label('Gratis')
                    ->boolean(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->searchPlaceholder('Cari amenitas')
            ->defaultSort('id', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Status')
                    ->placeholder('Semua fasilitas')
                    ->trueLabel('Gratis')
                    ->falseLabel('Berbayar')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth(Enums\MaxWidth::TwoExtraLarge),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAmenities::route('/'),
        ];
    }
}
