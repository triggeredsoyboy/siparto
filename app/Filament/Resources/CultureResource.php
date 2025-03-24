<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CultureResource\Pages;
use App\Filament\Resources\CultureResource\RelationManagers;
use App\Models\Culture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CultureResource extends Resource
{
    protected static ?string $model = Culture::class;

    protected static ?string $modelLabel = 'budaya';

    protected static ?string $pluralModelLabel = 'budaya';

    protected static ?string $navigationGroup = 'Kepariwisataan';

    protected static ?int $navigationSort = 3;

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
                                    ->collection('culture-media')
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
                        Forms\Components\Repeater::make('schedules')
                            ->label('Jadwal')
                            ->schema([
                                Forms\Components\Select::make('day')
                                    ->label('Hari')
                                    ->options([
                                        'senin' => 'Senin',
                                        'selasa' => 'Selasa',
                                        'rabu' => 'Rabu',
                                        'kamis' => 'Kamis',
                                        'jumat' => 'Jumat',
                                        'sabtu' => 'Sabtu',
                                        'minggu' => 'Minggu',
                                    ])
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('week')
                                    ->label('Pasaran')
                                    ->options([
                                        'legi' => 'Legi',
                                        'pahing' => 'Pahing',
                                        'pon' => 'Pon',
                                        'wage' => 'Wage',
                                        'kliwon' => 'Kliwon',
                                    ])
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('location')
                                    ->label('Lokasi')
                                    ->options([
                                        'padem' => 'Padem',
                                        'bolang' => 'Bolang',
                                        'dawung' => 'Dawung',
                                        'wiloso' => 'Wiloso',
                                        'karang' => 'Karang',
                                        'bedug' => 'Bedug',
                                        'doplang' => 'Doplang',
                                        'pundung' => 'Pundung',
                                    ])
                                    ->multiple()
                                    ->minItems(1)
                                    ->maxItems(8)
                                    ->native(false)
                                    ->required(),
                            ])
                            ->addActionLabel('Tambah Jadwal'),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat')
                                    ->content(fn(Culture $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diubah')
                                    ->content(fn(Culture $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->compact()
                            ->hidden(fn(?Culture $record) => $record === null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn(Model $record): string => route('filament.admin.resources.cultures.view', ['record' => $record]),
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->lineClamp(3),
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
                        ->modalHeading(fn(Culture $record): string => 'Hapus ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Budaya berhasil dihapus.'),
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
                                    ->collection('culture-media'),
                            ])
                            ->compact(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('schedules')
                            ->schema([
                                Infolists\Components\TextEntry::make('day')
                                    ->label('Hari')
                                    ->formatStateUsing(fn(string $state): string => str($state)->apa()),
                                Infolists\Components\TextEntry::make('week')
                                    ->label('Pasaran')
                                    ->formatStateUsing(fn(string $state): string => str($state)->apa()),
                                Infolists\Components\TextEntry::make('location')
                                    ->label('Lokasi')
                                    ->listWithLineBreaks()
                                    ->bulleted()
                                    ->formatStateUsing(fn(string $state): string => str($state)->apa()),
                            ]),
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
            'index' => Pages\ListCultures::route('/'),
            'create' => Pages\CreateCulture::route('/create'),
            'view' => Pages\ViewCulture::route('/{record}'),
            'edit' => Pages\EditCulture::route('/{record}/edit'),
        ];
    }
}
