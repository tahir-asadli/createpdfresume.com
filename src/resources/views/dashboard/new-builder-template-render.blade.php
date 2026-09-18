<div class="new-builder template html w-full {{ $resume->template->folder }} {{ $resume->style }} py-10 !pt-11 !preview">
    <div class="template body p-2 w-full overflow-x-scroll mx-auto {{ $resume->style }}"> {!! $resume->template->builderHTML($page) !!} </div>
    {{--  flex justify-center
 !preview --}}
</div>