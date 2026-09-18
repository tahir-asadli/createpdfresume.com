<?php

namespace App\Filament\Resources\BlacklistResource\Pages;

use App\Filament\Resources\BlacklistResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlacklist extends CreateRecord
{
    protected static string $resource = BlacklistResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $list = static::getModel()::create($data);
        updateBlackListedIps();
        return $list;
    }
}
