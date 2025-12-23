<?php

namespace App\Filament\Resources\Tests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('title')->label('Nom')->sortable()->searchable(),
                TextColumn::make('type')
                    ->label('Turi')
                    ->colors([
                        'success' => 'yopiq',
                        'warning' => 'ochiq',
                    ]),
                TextColumn::make('questions_count')->label('Savollar soni'),
                TextColumn::make('start_at')->label('Boshlanish')->dateTime(),
                TextColumn::make('end_at')->label('Tugash')->dateTime(),
                TextColumn::make('passing_score')->label('O‘tish bali'),
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
