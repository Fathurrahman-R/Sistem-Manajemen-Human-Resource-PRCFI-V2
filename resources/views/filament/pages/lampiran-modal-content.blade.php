<x-filament::section.description>
    Jumlah Lampiran: {{count($record->lampiran)}}
</x-filament::section.description>
@foreach($lampiran = $record->lampiran as $index => $file)
    @php
        $filename = basename($file);
     @endphp
        <x-filament::button
            href="{{route('cuti.lampiran.download',['cuti'=>$record->id,'filename'=>$filename])}}"
            tag="a"
            color="primary"
            size="md"
            icon="heroicon-m-arrow-down-tray">
            {{$filename}}
        </x-filament::button>
@endforeach
