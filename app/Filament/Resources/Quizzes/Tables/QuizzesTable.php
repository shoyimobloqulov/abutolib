<?php

namespace App\Filament\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('subject.name')
                    ->label('Fan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('topic.title')
                    ->label('Mavzu')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('question')
                    ->label('Savol matni')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Savol turi')
                    ->badge(),

                ImageColumn::make('image')
                    ->label('Rasm'),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('Y-m-d H:i'),
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
