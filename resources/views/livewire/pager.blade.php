<div
    class="flex justify-end items-center absolute top-0 right-0 left-0 mx-auto w-full max-w-[21cm] box-content _invisible _opacity-0 group-hover:visible group-hover:opacity-100">
    <div class="flex gap-2 p-1 backdrop-blur-lg rounded-lg border border-slate-200">
        <button wire:click="prev()" class="small rounded-md button text-slate-600 flex-shrink-0 w-8"><svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>

        </button><!-- button -->
        <input wire:model="page" type="text" class="!w-10 flex-shrink-0 !p-1 text-center rounded-md" /><!-- input -->
        <button wire:click="next()" class="small rounded-md button text-slate-600 flex-shrink-0 w-8"><svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </button><!-- button -->
    </div><!-- div -->
</div><!-- div2 -->
