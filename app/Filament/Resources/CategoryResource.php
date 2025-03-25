<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
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

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $modelLabel = 'kategori';

    protected static ?string $pluralModelLabel = 'kategori';

    protected static ?string $navigationGroup = 'Blog & Artikel';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->string()
                    ->minLength(3)
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
                    ->minLength(3)
                    ->maxLength(100)
                    ->autocomplete(false)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->string()
                    ->minLength(10)
                    ->maxLength(1000)
                    ->autocomplete(false)
                    ->rows(5)
                    ->autosize()
                    ->helperText('Kosongan jika tidak perlu.'),
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
                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Jumlah artikel')
                    ->counts('posts')
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
                        ->modalHeading(fn(Category $record): string => 'Ubah Kategori - ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Kategori berhasil diubah.'),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->icon('')
                        ->modalHeading(fn(Category $record): string => 'Hapus ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Kategori berhasil dihapus.'),
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
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
