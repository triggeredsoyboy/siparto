<?php

namespace App\Filament\Resources;

use App\Enums\PostStatus;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
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
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'post';

    protected static ?string $pluralModelLabel = 'postingan';

    protected static ?string $navigationGroup = 'Blog & Artikel';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul')
                                    ->string()
                                    ->minLength(5)
                                    ->maxLength(100)
                                    ->autofocus()
                                    ->autocomplete(false)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->string()
                                    ->minLength(5)
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true)
                                    ->autocomplete(false)
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                    ->label('Gambar Sampul')
                                    ->helperText(str('File yang didukung adalah **gambar/foto** dengan jumlah **maksimal 1** item.')->inlineMarkdown()->toHtmlString())
                                    ->image()
                                    ->collection('post-cover')
                                    ->moveFiles()
                                    ->minFiles(1)
                                    ->maxFiles(1)
                                    ->required()
                                    ->imagePreviewHeight('250'),
                            ])
                            ->compact(),
                        Forms\Components\Builder::make('body')
                            ->label('Isi Artikel')
                            ->blocks([
                                Forms\Components\Builder\Block::make('paragraph')
                                    ->label('Paragraf')
                                    ->icon('heroicon-m-bars-3-bottom-left')
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->label('Paragraf')
                                            ->string()
                                            ->minLength(10)
                                            ->disableToolbarButtons([
                                                'attachFiles',
                                            ])
                                            ->required(),
                                    ]),
                                Forms\Components\Builder\Block::make('media')
                                    ->label('Media')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                            ->helperText(str('File yang didukung adalah **gambar/foto** dengan jumlah **maksimal 2** item.')->inlineMarkdown()->toHtmlString())
                                            ->image()
                                            ->collection('post-media')
                                            ->multiple()
                                            ->reorderable()
                                            ->appendFiles()
                                            ->moveFiles()
                                            ->minFiles(1)
                                            ->maxFiles(2)
                                            ->panelLayout('grid')
                                            ->required(),
                                    ]),
                            ])
                            ->blockNumbers(false)
                            ->required(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship(name: 'category', titleAttribute: 'name')
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(PostStatus::class)
                                    ->native(false)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        if (($get('status')) === 'draft') {
                                            $set('published_at', null);
                                        }
                                    }),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Diterbitkan pada')
                                    ->seconds(false)
                                    ->maxDate(now())
                                    ->required()
                                    ->disabled(fn(Forms\Get $get): bool => $get('status') === 'draft')
                                    ->dehydrated(),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Dibuat')
                                    ->content(fn(Post $record): ?string => $record->created_at?->diffForHumans())
                                    ->hidden(fn(?Post $record) => $record === null),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir diubah')
                                    ->content(fn(Post $record): ?string => $record->updated_at?->diffForHumans())
                                    ->hidden(fn(?Post $record) => $record === null),
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
                fn(Model $record): string => route('filament.admin.resources.posts.view', ['record' => $record]),
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->lineClamp(3),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('j M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->filtersFormSchema(fn(array $filters): array => [
                //
            ])
            ->filtersTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->label('Filter')
                    ->button()
                    ->size(Support\Enums\ActionSize::Medium),
            )
            ->filtersFormWidth(Support\Enums\MaxWidth::Small)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon(''),
                    Tables\Actions\DeleteAction::make()
                        ->icon('')
                        ->modalHeading(fn(Post $record): string => 'Hapus ' . $record->title)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Postingan berhasil dihapus.'),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->icon(''),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
