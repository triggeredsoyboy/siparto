<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Filament\Resources\DestinationResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DestinationsRelationManager extends RelationManager
{
    protected static string $relationship = 'destinations';

    protected static ?string $title = 'Destinasi';

    protected static ?string $modelLabel = 'destinasi';

    protected static ?string $pluralModelLabel = 'destinasi';

    public function form(Form $form): Form
    {
        return DestinationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return DestinationResource::table($table)
            ->recordTitleAttribute('name')
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->icon('heroicon-o-link-slash')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->icon(''),
            ]);
    }
}
