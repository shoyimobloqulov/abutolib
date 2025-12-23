<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ism')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email manzil')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('roles.name')
                    ->label('Rollar')
                    ->badge()
                    ->separator(',')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'moderator',
                        'success' => 'user',
                    ]),

                TextColumn::make('permissions_count')
                    ->label('Ruxsatlar')
                    ->counts('permissions')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state . ' ta')
                    ->tooltip(fn ($record) => $record->permissions->pluck('name')->join(', ') ?: 'Ruxsat yo\'q'),

                TextColumn::make('email_verified_at')
                    ->label('Email tasdiqlangan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Tasdiqlangan' : 'Tasdiqlanmagan')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Yangilangan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Rol bo\'yicha')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('permissions')
                    ->label('Ruxsat bo\'yicha')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('email_verified_at')
                    ->label('Email holati')
                    ->options([
                        'verified' => 'Tasdiqlangan',
                        'unverified' => 'Tasdiqlanmagan',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'verified') {
                            return $query->whereNotNull('email_verified_at');
                        }
                        if ($data['value'] === 'unverified') {
                            return $query->whereNull('email_verified_at');
                        }
                    }),
            ])
            ->actions([
                Action::make('manage_permissions')
                    ->label('Ruxsatlar')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->modalHeading('Ruxsatlarni boshqarish')
                    ->modalDescription(fn ($record) => $record->name . ' uchun ruxsatlar')
                    ->modalWidth('lg')
                    ->form([
                        Select::make('permissions')
                            ->label('Ruxsatlar')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Foydalanuvchiga to\'g\'ridan-to\'g\'ri beriladigan ruxsatlar')
                            ->default(fn ($record) => $record->permissions->pluck('id')->toArray()),
                    ])
                    ->action(function ($record, array $data) {
                        $record->syncPermissions($data['permissions']);

                        Notification::make()
                            ->success()
                            ->title('Ruxsatlar yangilandi')
                            ->body($record->name . ' uchun ruxsatlar muvaffaqiyatli yangilandi')
                            ->send();
                    }),

                Action::make('manage_roles')
                    ->label('Rollar')
                    ->icon('heroicon-o-shield-check')
                    ->color('info')
                    ->modalHeading('Rollarni boshqarish')
                    ->modalDescription(fn ($record) => $record->name . ' uchun rollar')
                    ->modalWidth('lg')
                    ->form([
                        Select::make('roles')
                            ->label('Rollar')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Foydalanuvchiga biriktirilgan rollar')
                            ->default(fn ($record) => $record->roles->pluck('id')->toArray()),
                    ])
                    ->action(function ($record, array $data) {
                        $record->syncRoles($data['roles']);

                        Notification::make()
                            ->success()
                            ->title('Rollar yangilandi')
                            ->body($record->name . ' uchun rollar muvaffaqiyatli yangilandi')
                            ->send();
                    }),

                EditAction::make(),

                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    BulkAction::make('assign_role')
                        ->label('Rol biriktirish')
                        ->icon('heroicon-o-shield-check')
                        ->color('info')
                        ->form([
                            Select::make('role')
                                ->label('Rol')
                                ->relationship('roles', 'name')
                                ->required()
                                ->preload()
                                ->searchable(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->assignRole($data['role']);
                            }

                            Notification::make()
                                ->success()
                                ->title('Rollar biriktirildi')
                                ->body(count($records) . ' ta foydalanuvchiga rol biriktirildi')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('assign_permission')
                        ->label('Ruxsat berish')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->form([
                            Select::make('permission')
                                ->label('Ruxsat')
                                ->relationship('permissions', 'name')
                                ->required()
                                ->preload()
                                ->searchable(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->givePermissionTo($data['permission']);
                            }

                            Notification::make()
                                ->success()
                                ->title('Ruxsatlar berildi')
                                ->body(count($records) . ' ta foydalanuvchiga ruxsat berildi')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
