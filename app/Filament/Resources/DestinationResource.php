<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DestinationResource\Pages;
use App\Filament\Resources\DestinationResource\RelationManagers;
use App\Models\Destination;
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
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $modelLabel = 'destinasi';

    protected static ?string $pluralModelLabel = 'destinasi';

    protected static ?string $navigationGroup = 'Destinasi Wisata';

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
                                    ->columnSpanFull(),
                            ])
                            ->compact()
                            ->columns(['sm' => 2]),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                    ->helperText(str('File yang didukung adalah **gambar/foto** dengan jumlah **min 2 dan maks 5** item.')->inlineMarkdown()->toHtmlString())
                                    ->image()
                                    ->collection('destination-media')
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
                                Forms\Components\TextInput::make('ticket_price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->mask(Support\RawJs::make('$money($input, \',\')'))
                                    ->stripCharacters('.')
                                    ->required(),
                                Forms\Components\Select::make('destination_type_id')
                                    ->label('Tipe Destinasi')
                                    ->native(false)
                                    ->relationship(name: 'destinationType', titleAttribute: 'name')
                                    ->preload()
                                    ->required(),
                                Forms\Components\CheckboxList::make('facilities')
                                    ->label('Fasilitas')
                                    ->relationship(titleAttribute: 'name')
                                    ->bulkToggleable()
                                    ->required()
                                    ->columns(['sm' => 2, 'lg' => 1, 'xl' => 2]),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat')
                                    ->content(fn(Destination $record): ?string => $record->created_at?->diffForHumans())
                                    ->hidden(fn(?Destination $record) => $record === null),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diubah')
                                    ->content(fn(Destination $record): ?string => $record->updated_at?->diffForHumans())
                                    ->hidden(fn(?Destination $record) => $record === null),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn(Model $record): string => route('filament.admin.resources.destinations.view', ['record' => $record]),
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->lineClamp(3),
                Tables\Columns\TextColumn::make('destinationType.name')
                    ->label('Tipe Destinasi')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket_price')
                    ->label('HTM')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('j M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('destinationTypes')
                    ->label('Tipe Destinasi')
                    ->native(false)
                    ->relationship(name: 'destinationType', titleAttribute: 'name')
                    ->preload(),
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
                        ->modalHeading(fn(Destination $record): string => 'Hapus ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Destinasi berhasil dihapus.'),
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
                                    ->collection('destination-media'),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('ticket_price')
                                    ->label('Harga Tiket')
                                    ->money('IDR', locale: 'id'),
                                Infolists\Components\TextEntry::make('destinationType.name')
                                    ->label('Tipe Destinasi')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('facilities.name')
                                    ->label('Fasilitas')
                                    ->listWithLineBreaks()
                                    ->bulleted(),
                            ])
                            ->compact(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'view' => Pages\ViewDestination::route('/{record}'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
