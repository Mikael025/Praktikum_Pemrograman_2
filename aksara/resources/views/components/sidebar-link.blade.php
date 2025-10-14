@props(['href' => '#', 'active' => false])

<a href="{{ $href }}" {{ $attributes->merge(['class' => ($active ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white') . ' flex items-center px-3 py-2 rounded-md text-sm font-medium']) }}>
    {{ $slot }}
</a>


