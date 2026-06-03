<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RuleResource\Pages;
use App\Models\Rule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RuleResource extends Resource
{
    protected static ?string $model = Rule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Knowledge Base';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Rule';

    protected static ?string $pluralModelLabel = 'Rules';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konfigurasi Rule')
                    ->schema([
                        Forms\Components\Select::make('hypothesis_id')
                            ->label('Hipotesis (Kesimpulan)')
                            ->relationship('hypothesis', 'label')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('certainty_factor')
                            ->label('Certainty Factor (CF)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(1)
                            ->step(0.01)
                            ->default(1.00)
                            ->required()
                            ->helperText('Nilai kepastian aturan (0.00 - 1.00). Default: 1.00'),
                    ])->columns(2),

                Forms\Components\Section::make('Kondisi (Premis)')
                    ->schema([
                        Forms\Components\Repeater::make('conditions')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('question_id')
                                    ->label('Pertanyaan')
                                    ->relationship('question', 'text')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('expected_answer')
                                    ->label('Jawaban yang Diharapkan')
                                    ->default('YA')
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Kondisi')
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['question_id'])
                                    ? 'Kondisi — P' . $state['question_id']
                                    : 'Kondisi Baru'
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Rule #')
                    ->sortable()
                    ->width('80px')
                    ->formatStateUsing(fn ($state) => 'R' . $state),
                Tables\Columns\TextColumn::make('hypothesis.label')
                    ->label('Hipotesis')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('hypothesis.pasal_uutpks')
                    ->label('Pasal')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('certainty_factor')
                    ->label('CF')
                    ->numeric(2)
                    ->sortable()
                    ->width('80px'),
                Tables\Columns\TextColumn::make('conditions_count')
                    ->label('Jumlah Kondisi')
                    ->counts('conditions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hypothesis_id')
                    ->label('Filter Hipotesis')
                    ->relationship('hypothesis', 'label'),
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
            'index' => Pages\ListRules::route('/'),
            'create' => Pages\CreateRule::route('/create'),
            'edit' => Pages\EditRule::route('/{record}/edit'),
        ];
    }
}
