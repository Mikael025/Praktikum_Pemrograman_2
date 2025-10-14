@props(['title', 'value' => 0, 'icon' => null, 'color' => 'indigo'])

<div class="rounded-lg bg-white dark:bg-gray-800 p-4 shadow ring-1 ring-gray-200 dark:ring-gray-700">
    <div class="flex items-center justify-between">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ $title }}</dt>
        @if($icon)
            <span class="text-{{ $color }}-600">{!! $icon !!}</span>
        @endif
    </div>
    <dd class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</dd>
    {{ $slot ?? '' }}
  </div>


