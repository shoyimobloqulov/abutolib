<?php

namespace App\Filament\Resources\Tests\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;

class TestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('QuizTabs')
                    ->tabs([
                        Tab::make('Test sinovi haqida')->schema([
                            Select::make('type')
                                ->label('Test sinovi turi')
                                ->options([
                                    'yopiq' => 'Yopiq test sinovi (private)',
                                    'ochiq' => 'Ochiq test sinovi (public)',
                                ])
                                ->required()
                                ->columnSpan('full'),

                            TextInput::make('title')
                                ->label('Test sinovi nomi')
                                ->required()
                                ->columnSpan('full'),

                            RichEditor::make('description')
                                ->label('Test sinovi haqida batafsil')
                                ->columnSpan('full'),

                            Fieldset::make('Test sinovi malumotlari')
                                ->columns([
                                    'default' => 4,
                                    'md' => 4,
                                    'xl' => 4,
                                ])
                                ->schema([
                                    TextInput::make('questions_count')
                                        ->label('Savollar soni')
                                        ->numeric(),

                                    DateTimePicker::make('start_at')
                                        ->label('Boshlanish vaqti'),

                                    TimePicker::make('duration_minutes')
                                        ->label('Davomiyligi')
                                        ->seconds(false),
                                    DateTimePicker::make('end_at')
                                        ->label('Tugash vaqti'),
                                ])
                        ]),

                        Tab::make('Kengaytirilgan sozlamalar')->schema([
                            Toggle::make('show_answers')
                                ->label('Javoblarni ko‘rsatish')
                                ->columnSpan('full'),

                            Toggle::make('shuffle_questions')
                                ->label('Savollarni aralashtirish')
                                ->columnSpan('full'),

                            Toggle::make('shuffle_answers')
                                ->label('Variantlarni aralashtirish')
                                ->columnSpan('full'),

                            MultiSelect::make('quizzes')
                                ->relationship('quizzes', 'title')
                                ->label('Savollar')
                                ->preload()
                                ->columnSpan('full'),
                        ]),
                    ])
                    ->columnSpan('full')
        ]);
    }
}
