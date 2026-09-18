<?php

namespace App\Filament\Pages;
use App\Jobs\BackupJob;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
class Backups extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.backups';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runBackup')
                ->label('Create New Backup')
                ->color('primary')
                ->requiresConfirmation()
                ->action(fn() => $this->runBackup()),
        ];
    }
    public function runBackup()
    {


        // $rootBackupDir = 'backups';
        // $datetime = date("Y-m-d_H-i-s");
        // $uniqid = uniqid();
        // $newFolderName = "{$datetime}.{$uniqid}";
        // $folderToArchive = base_path();
        // $rootBackupPath = Storage::disk('local')->path($rootBackupDir);
        // if (!is_dir($rootBackupPath) && !is_file($rootBackupPath)) {
        //     mkdir($rootBackupPath, 0777);
        //     file_put_contents($rootBackupPath . '/.gitignore', "*\n!.gitignore\n");
        // }
        // info($rootBackupPath);
        BackupJob::dispatch(date("Y-m-d H:i:s"));
        Notification::make()
            ->title('Backup started!. You\'ll get an emails once it\'s done')
            ->success()
            ->send();
    }
}
