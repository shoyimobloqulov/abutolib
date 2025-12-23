<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Ruxsat nomi')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('roles')
                    ->label('Rollar')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                        TextInput::make('guard_name')->default('web'),
                    ])
        ]);
    }
}
