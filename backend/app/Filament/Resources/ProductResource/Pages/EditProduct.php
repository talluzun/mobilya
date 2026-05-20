<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterSave(): void
    {
        $this->normalizeOptions();
        $this->logImageStorage();
    }

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

    private function normalizeOptions(): void
    {
        $product = $this->record;
        $colorOption = $product->colorOption;

        if ($colorOption) {
            $colorOption->update([
                'label' => 'Color',
                'key' => 'color',
                'type' => 'swatch',
                'is_required' => true,
            ]);

            $values = $colorOption->values;
            $defaultValue = $values->firstWhere('is_default', true) ?? $values->first();

            if ($defaultValue) {
                $colorOption->values()
                    ->where('id', '!=', $defaultValue->id)
                    ->update(['is_default' => false]);

                $defaultValue->is_default = true;
                $defaultValue->save();
            }

            foreach ($values as $value) {
                if ($value->color_hex && ! Str::startsWith($value->color_hex, '#')) {
                    $value->color_hex = '#'.$value->color_hex;
                    $value->save();
                }
            }
        }

        $product->extraOptions->each(function ($option): void {
            if (! in_array($option->type, ['button_group', 'select', 'radio'], true)) {
                $option->values()->delete();
            }
        });
    }

    private function logImageStorage(): void
    {
        $product = $this->record->loadMissing('media');
        $thumbnailPath = $product->thumbnail_path;
        $galleryPaths = $product->media->pluck('path')->all();

        Log::info('Product image storage check (edit).', [
            'product_id' => $product->id,
            'thumbnail_path' => $thumbnailPath,
            'thumbnail_exists' => $thumbnailPath ? Storage::disk('public')->exists($thumbnailPath) : false,
            'gallery_paths' => $galleryPaths,
            'gallery_exists' => array_map(
                fn (string $path): bool => Storage::disk('public')->exists($path),
                $galleryPaths
            ),
        ]);
    }
}
