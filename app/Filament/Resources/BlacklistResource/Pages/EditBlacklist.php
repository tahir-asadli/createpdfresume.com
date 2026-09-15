<?php

namespace App\Filament\Resources\BlacklistResource\Pages;

use App\Filament\Resources\BlacklistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Storage;

class EditBlacklist extends EditRecord
{
    protected static string $resource = BlacklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(function (): void {
                updateBlackListedIps();
            }),
        ];
    }
    protected function handleRecordUpdate(Model $bundle, array $data): Model
    {

        $bundle->update($data);
        updateBlackListedIps();
        return $bundle;
    }
}
