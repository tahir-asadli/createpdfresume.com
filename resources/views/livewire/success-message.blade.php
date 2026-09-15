<div x-data="{ message: '', show: false }" :class="{ 'h-14': show }" x-cloak
    @added.window="show = true; setTimeout(() => show = false, 2000)"
    class="h-0 w-full text-green-900 bg-green-100 rounded-lg font-medium transition-all overflow-hidden">
    <div class="px-4 py-4 ">
        Skill added!
    </div>
</div>
