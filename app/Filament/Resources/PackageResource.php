<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $modelLabel = 'paket wisata';

    protected static ?string $pluralModelLabel = 'paket wisata';

    protected static ?string $navigationGroup = 'Kepariwisataan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
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
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi')
                                    ->string()
                                    ->minLength(10)
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                    ])
                                    ->columnSpanFull()
                                    ->required(),
                            ])
                            ->compact()
                            ->columns(['sm' => 2]),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                    ->helperText(str('File yang didukung adalah **gambar/foto** dengan jumlah **min 2 dan maks 5** item.')->inlineMarkdown()->toHtmlString())
                                    ->image()
                                    ->collection('package-media')
                                    ->multiple()
                                    ->reorderable()
                                    ->appendFiles()
                                    ->moveFiles()
                                    ->minFiles(2)
                                    ->maxFiles(5)
                                    ->panelLayout('grid')
                                    ->required(),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->mask(Support\RawJs::make('$money($input, \',\')'))
                                    ->stripCharacters('.')
                                    ->autocomplete(false)
                                    ->required(),
                                Forms\Components\TextInput::make('duration')
                                    ->label('Durasi')
                                    ->numeric()
                                    ->step(1)
                                    ->minValue(1)
                                    ->maxValue(7)
                                    ->autocomplete(false)
                                    ->required()
                                    ->suffix('hari'),
                            ])
                            ->compact(),
                        Forms\Components\Section::make('Pengunjung')
                            ->schema([
                                Forms\Components\TextInput::make('min_person')
                                    ->label('Min.')
                                    ->numeric()
                                    ->step(1)
                                    ->minValue(1)
                                    ->maxValue(7)
                                    ->autocomplete(false)
                                    ->required(),
                                Forms\Components\TextInput::make('max_person')
                                    ->label('Maks.')
                                    ->numeric()
                                    ->step(1)
                                    ->minValue(1)
                                    ->maxValue(7)
                                    ->autocomplete(false)
                                    ->required(),
                            ])
                            ->columns(['lg' => 2]),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat')
                                    ->content(fn(Package $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diubah')
                                    ->content(fn(Package $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->compact()
                            ->hidden(fn(?Package $record) => $record === null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn(Model $record): string => route('filament.admin.resources.packages.view', ['record' => $record]),
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->lineClamp(3),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi')
                    ->formatStateUsing(fn(string $state): string => $state . ' hari')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('j M Y')
                    ->sortable(),
            ])
            ->filters([
                //
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
                        ->icon(''),
                    Tables\Actions\DeleteAction::make()
                        ->icon('')
                        ->modalHeading(fn(Package $record): string => 'Hapus ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Paket berhasil dihapus.'),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->icon(''),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama'),
                                Infolists\Components\TextEntry::make('slug')
                                    ->label('Slug'),
                                Infolists\Components\TextEntry::make('description')
                                    ->label('Deskripsi')
                                    ->markdown()
                                    ->columnSpanFull(),
                            ])
                            ->compact()
                            ->columns(['sm' => 2]),
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\SpatieMediaLibraryImageEntry::make('media')
                                    ->collection('package-media'),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('price')
                                    ->label('Harga')
                                    ->money('IDR', locale: 'id'),
                                Infolists\Components\TextEntry::make('duration')
                                    ->label('Durasi')
                                    ->formatStateUsing(fn(string $state): string => $state . ' hari'),
                            ])
                            ->compact(),
                        Infolists\Components\Section::make('Pengunjung')
                            ->schema([
                                Infolists\Components\TextEntry::make('min_person')
                                    ->label('Min.')
                                    ->formatStateUsing(fn(string $state): string => $state . ' orang'),
                                Infolists\Components\TextEntry::make('max_person')
                                    ->label('Maks.')
                                    ->formatStateUsing(fn(string $state): string => $state . ' orang'),
                            ])
                            ->compact()
                            ->columns(['sm' => 2]),
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Dibuat')
                                    ->dateTime('j M Y'),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Terakhir diubah')
                                    ->dateTime('j M Y'),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DestinationsRelationManager::class,
            RelationManagers\ItinerariesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
