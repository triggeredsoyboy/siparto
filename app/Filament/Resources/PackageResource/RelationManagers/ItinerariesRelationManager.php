<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Models\Itinerary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class ItinerariesRelationManager extends RelationManager
{
    protected static string $relationship = 'itineraries';

    protected static ?string $title = 'Rencana Perjalanan';

    protected static ?string $modelLabel = 'rencana perjalanan';

    protected static ?string $pluralModelLabel = 'rencana perjalanan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kegiatan')
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->autofocus()
                    ->autocomplete(false)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('activities')
                    ->label('Aktivitas')
                    ->schema([
                        Forms\Components\TimePicker::make('time')
                            ->label('Waktu')
                            ->seconds(false)
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('activity')
                            ->label('Kegiatan')
                            ->string()
                            ->minLength(5)
                            ->maxLength(100)
                            ->autocomplete(false)
                            ->required(),
                    ])
                    ->columns(['sm' => 2])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->lineClamp(3),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Buat Rencana Perjalanan')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Berhasil')
                            ->body('Rencana perjalanan berhasil ditambahkan.'),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('')
                        ->modalHeading(fn(Itinerary $record): string => 'Ubah Rencana - ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Rencana perjalanan berhasil diubah.'),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->icon('')
                        ->modalHeading(fn(Itinerary $record): string => 'Hapus ' . $record->name)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil')
                                ->body('Rencana perjalanan berhasil dihapus.'),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->icon(''),
            ]);
    }
}
