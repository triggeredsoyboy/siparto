<?php

namespace App\Filament\Resources\DestinationTypeResource\RelationManagers;

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

    public function form(Form $form): Form
    {
        return DestinationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return DestinationResource::table($table);
    }
}
