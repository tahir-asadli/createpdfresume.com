<x-filament-panels::page>
    @if (Storage::disk('local')->exists('backups'))
        <table>
            <thead>
                <tr>
                    <td>File name</td>
                </tr>
            </thead>
            <tbody>
                @foreach (scandir(Storage::disk('local')->path('backups')) as $dir)
                    @if (Str::endsWith($dir, '.tar.gz'))
                        <tr>
                            <td>
                                <a href="{{ route('backup-download') . '/?dbckfn=' . $dir }}">{{ $dir }}</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div>No backup</div>
    @endif
</x-filament-panels::page>
