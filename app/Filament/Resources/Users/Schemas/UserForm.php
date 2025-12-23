<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foydalanuvchi ma\'lumotlari')
                    ->schema([
                        TextInput::make('name')
                            ->label('Ism')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email manzil')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email tasdiqlangan vaqti')
                            ->nullable(),

                        TextInput::make('password')
                            ->label('Parol')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255)
                            ->revealable()
                            ->helperText('Yangilashda bo\'sh qoldiring, agar parolni o\'zgartirmoqchi bo\'lmasangiz'),
                    ])
                    ->columns(2),

                Section::make('Rollar va Ruxsatlar')
                    ->schema([
                        Select::make('roles')
                            ->label('Rollar')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Foydalanuvchiga biriktirilgan rollar'),

                        Select::make('permissions')
                            ->label('To\'g\'ridan-to\'g\'ri ruxsatlar')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Rollardan tashqari qo\'shimcha ruxsatlar'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
