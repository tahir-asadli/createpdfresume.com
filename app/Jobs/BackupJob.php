<?php

namespace App\Jobs;

use App\Mail\AdminNotification;
use App\Mail\BackupNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PharData;
use Phar;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Support\Str;
class BackupJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $date)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $rootBackupDir = 'backups';
        $datetime = date("Y-m-d_H-i-s");
        $uniqid = uniqid();
        $newFolderName = "{$datetime}.{$uniqid}";
        $folderToArchive = base_path();
        $rootBackupPath = Storage::disk('local')->path($rootBackupDir);
        if (!is_dir($rootBackupPath) && !is_file($rootBackupPath)) {
            mkdir($rootBackupPath, 0777);
            file_put_contents($rootBackupPath . '/.gitignore', "*\n!.gitignore\n");
        }
        // if (!Storage::disk('local')->exists($rootBackupDir)) {
        //     Storage::disk('local')->makeDirectory($rootBackupDir);
        // }
        $newBackupFolder = "$rootBackupDir/$newFolderName";
        $newBackupPath = Storage::disk('local')->path($newBackupFolder);
        if (!is_dir($newBackupPath) && !is_file($newBackupPath)) {
            mkdir($newBackupPath, 0777);
        }
        // if (!Storage::disk('local')->makeDirectory($newBackupFolder)) {
        //     return;
        // }
        $MysqlUsername = config('database.connections.mysql.username');
        $MysqlPassword = config('database.connections.mysql.password');
        $MysqlDatabase = config('database.connections.mysql.database');
        if (!$MysqlUsername || !$MysqlPassword || !$MysqlDatabase) {
            return;
        }

        $MysqlDumpFile = "$newBackupPath/database.sql";
        $command = "mysqldump -u \"$MysqlUsername\" -p\"$MysqlPassword\" \"$MysqlDatabase\" > \"$MysqlDumpFile\"";
        $commandResult = Process::run("$command");
        // if ($commandResult->successful()) {
        // } else {
        // }
        $tarFile = "{$rootBackupPath}/{$newFolderName}.tar";
        $archive = new PharData($tarFile);
        $directory = new RecursiveDirectoryIterator($folderToArchive, RecursiveDirectoryIterator::SKIP_DOTS);
        $filter = new RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator) use ($rootBackupPath) {
            // List of directory names or files to ignore
            $exclude = ['node_modules'];
            if (Str::startsWith($current->getPath(), $rootBackupPath)) {
                return false;
            }
            // Check if the current file/folder name is in the exclude list
            if (in_array($current->getFilename(), $exclude)) {
                return false;
            }
            return true;
        });
        $iterator = new RecursiveIteratorIterator($filter);
        $archive->buildFromIterator($iterator, $folderToArchive);
        $archive->addFile($MysqlDumpFile, 'database.sql');
        $archive->compress(Phar::GZ);
        unset($archive);
        unlink($tarFile);
        unlink($MysqlDumpFile);
        File::deleteDirectory($newBackupPath);
        Mail::to(config('site.adminMail'))->send(new BackupNotification($this->date, $newFolderName . '.tar.gz'));
    }
}
