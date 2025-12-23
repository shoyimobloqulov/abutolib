<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nomi')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('image')
                    ->label('Rasm')
                    ->image()
                    ->directory('subjects')
                    ->nullable(),

                // ✅ Description rich editor
                RichEditor::make('description')
                    ->label('Qo\'shimcha malumot')
                    ->required(false)
                    ->columnSpan('full'),

                // ✅ Topics qo‘shish uchun Repeater
                Repeater::make('topics')
                    ->label('Mavzular')
                    ->relationship('topics')
                    ->schema([
                        TextInput::make('title')
                            ->label('Nomi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'),

                        RichEditor::make('content')
                            ->label('Mavzu haqida')
                            ->required(false)
                            ->columnSpan('full'),
                    ])
                    ->collapsible()
                    ->createItemButtonLabel('Mavzu qo\'shish')
                    ->columnSpan('full'),
            ]);
    }
}
