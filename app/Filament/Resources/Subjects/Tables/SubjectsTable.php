<?php

namespace App\Filament\Resources\Subjects\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nomi')
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Rasmi'),
                TextColumn::make('topics_count')
                    ->counts('topics')
                    ->label('Mavzular soni'),
                TextColumn::make('created_at')
                    ->label("Yaratilish vaqti")
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
