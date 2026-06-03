<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Knowledge Base';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Pertanyaan';

    protected static ?string $pluralModelLabel = 'Pertanyaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pertanyaan')
                    ->schema([
                        Forms\Components\Textarea::make('text')
                            ->label('Teks Pertanyaan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('help_text')
                            ->label('Teks Bantuan')
                            ->helperText('Penjelasan tambahan yang muncul saat pengguna butuh klarifikasi.')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('answer_type')
                            ->label('Tipe Jawaban')
                            ->options([
                                'YA_TIDAK' => 'Ya / Tidak',
                                'PILIHAN' => 'Pilihan Ganda',
                            ])
                            ->default('YA_TIDAK')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('P#')
                    ->sortable()
                    ->width('60px')
                    ->formatStateUsing(fn ($state) => 'P' . $state),
                Tables\Columns\TextColumn::make('text')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->wrap()
                    ->limit(100),
                Tables\Columns\TextColumn::make('answer_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'YA_TIDAK' => 'success',
                        'PILIHAN' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('rule_conditions_count')
                    ->label('Digunakan di Rules')
                    ->counts('ruleConditions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
