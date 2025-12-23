<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Models\Subject;
use App\Models\Topic;
use Faker\Provider\ar_SA\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subject_id')
                    ->label('Fan (Subject)')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')->label('Subject nomi')->required(),
                        FileUpload::make('image')
                            ->label('Rasm')
                            ->directory('subjects')   // uploads/subjects ichiga tushadi
                            ->image()
                            ->nullable(),
                        Textarea::make('description')->label('Izoh')->nullable(),
                    ])
                    ->createOptionAction(function ($action) {
                        return $action->label('Yangi Fan qo‘shish');
                    }),

                Select::make('topic_id')
                    ->label('Mavzu (Topic)')
                    ->options(function (callable $get) {
                        $subjectId = $get('subject_id');
                        if (!$subjectId) {
                            return Topic::pluck('title', 'id');
                        }
                        return Topic::where('subject_id', $subjectId)->pluck('title', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->createOptionForm(function (callable $get) {
                        return [
                            TextInput::make('title')
                                ->label('Topic nomi')
                                ->required(),

                            // Subject avtomatik to‘ldiriladi
                            Select::make('subject_id')
                                ->label('Fan')
                                ->options(Subject::pluck('name', 'id'))
                                ->default($get('subject_id'))
                                ->required(),
                        ];
                    })
                    ->createOptionAction(function ($action) {
                        return $action->label('Yangi Mavzu qo‘shish');
                    }),

                // Savol
                RichEditor::make('question')
                    ->label('Savol matni')
                    ->columnSpan('full')
                    ->required(),

                // Rasim (ixtiyoriy)
                FileUpload::make('image')
                    ->label('Rasm')
                    ->directory('quiz-images')
                    ->image()
                    ->nullable(),

                // Test turi
                Select::make('type')
                    ->label('Test turi')
                    ->options([
                        'multiple_choice' => 'Bir variantli test',
                        'multi_select' => 'Ko‘p variantli test',
                        'true_false' => 'To‘g‘ri / Noto‘g‘ri',
                        'text_input' => 'Matn kiritish',
                    ])
                    ->reactive()
                    ->required(),

                // ANSWERS
                Repeater::make('answers')
                    ->label("Javoblar")
                    ->relationship('answers')
                    ->visible(fn (callable $get) =>
                        $get('type') !== 'text_input'
                    )
                    ->schema([

                        TextInput::make('answer')
                            ->label('Variant matni')
                            ->required(),

                        Toggle::make('is_correct')
                            ->label('To‘g‘ri variantmi?'),

                    ])
                    ->columns(2)
                    ->defaultItems(2)
                    ->columnSpanFull(),

                // TEXT_INPUT uchun to‘g‘ri javob matni
                TextInput::make('correct_text_answer')
                    ->label('To‘g‘ri javob matni')
                    ->visible(fn (callable $get) =>
                        $get('type') === 'text_input'
                    ),
            ]);
    }
}
