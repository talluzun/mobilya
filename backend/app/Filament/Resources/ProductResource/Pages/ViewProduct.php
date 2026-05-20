<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('duplicate')
                ->label('Çoğalt')
                ->icon('heroicon-o-document-duplicate')
                ->action(function (): void {
                    $duplicate = $this->record->duplicate();

                    $this->redirect(ProductResource::getUrl('edit', ['record' => $duplicate]));
                }),
            Action::make('delete')
                ->label('Sil')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (): void {
                    $result = $this->record->deleteWithRelations();

                    if ($result === 'soft') {
                        Notification::make()
                            ->title('Bu ürüne ait teklifler olduğu için ürün silinmek yerine arşivlendi.')
                            ->warning()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Ürün silindi.')
                            ->success()
                            ->send();
                    }

                    $this->redirect(ProductResource::getUrl('index'));
                }),
        ];
    }
}
