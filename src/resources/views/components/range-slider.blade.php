@props(['width' => '20px', 'height' => '20px'])
<div {{ $attributes->merge(['class' => 'my-2 mx-1']) }}
    data-hs-range-slider='{
  "start": 0,
  "range": {
    "min": 0,
    "max": 100
  },
  "step": 10,
  "connect": "lower",
  "pips": {
    "mode": "values",
    "values": [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
    "density": 10
  },
  "formatter": {
    "type": "integer",
    "prefix": "%"
  },
  "cssClasses": {
    "target": "relative h-2 mb-10 rounded-full bg-gray-100 dark:bg-neutral-700",
    "base": "w-full h-full relative z-1",
    "origin": "absolute top-0 end-0 w-full h-full origin-[0_0] rounded-full",
    "handle": "absolute top-1/2 end-0 w-[1.125rem] h-[1.125rem] bg-white border-4 border-violet-600 rounded-full cursor-pointer translate-x-2/4 -translate-y-2/4 dark:border-violet-500",
    "connects": "relative z-0 w-full h-full rounded-full overflow-hidden",
    "connect": "absolute top-0 end-0 z-1 w-full h-full bg-violet-600 origin-[0_0] dark:bg-violet-500",
    "touchArea": "absolute -top-1 -bottom-1 -start-1 -end-1",
    "tooltip": "bg-white border border-gray-200 text-sm text-gray-800 py-1 px-2 rounded-lg mb-3 absolute bottom-full start-2/4 -translate-x-2/4 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white",
    "pips": "relative w-full h-10 mt-1",
    "value": "absolute top-4 -translate-x-2/4 text-sm text-gray-400",
    "marker": "absolute border-s border-gray-400",
    "markerNormal": "h-2",
    "markerLarge": "h-4"
  }
}'>
</div>
@script
    <script>
        // HSStaticMethods.autoInit();
        // if (HSStaticMethods) {}
    </script>
@endscript
