<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HypothesisResource\Pages;
use App\Models\Hypothesis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HypothesisResource extends Resource
{
    protected static ?string $model = Hypothesis::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Knowledge Base';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Hipotesis';

    protected static ?string $pluralModelLabel = 'Hipotesis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Hipotesis')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Klasifikasi Hukum')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('pasal_uutpks')
                            ->label('Dasar Hukum (Pasal UU TPKS)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Template BAP')
                    ->schema([
                        Forms\Components\Textarea::make('bap_template')
                            ->label('Template Dokumen BAP')
                            ->helperText('Template teks untuk generate PDF BAP. Gunakan placeholder seperti {tanggal}, {kronologi}, dll.')
                            ->rows(8)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('label')
                    ->label('Klasifikasi Hukum')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('pasal_uutpks')
                    ->label('Pasal')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('rules_count')
                    ->label('Jumlah Rules')
                    ->counts('rules')
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
            'index' => Pages\ListHypotheses::route('/'),
            'create' => Pages\CreateHypothesis::route('/create'),
            'edit' => Pages\EditHypothesis::route('/{record}/edit'),
        ];
    }
}
