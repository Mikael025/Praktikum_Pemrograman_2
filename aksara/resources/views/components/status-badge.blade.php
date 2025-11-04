@props(['status'])

@php
    $statusConfig = [
        'diusulkan' => [
            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'label' => 'Diusulkan'
        ],
        'tidak_lolos' => [
            'class' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'label' => 'Tidak Lolos'
        ],
        'lolos_perlu_revisi' => [
            'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'label' => 'Lolos, Perlu Revisi'
        ],
        'lolos' => [
            'class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'label' => 'Lolos'
        ],
        'revisi_pra_final' => [
            'class' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'label' => 'Revisi Pra-final'
        ],
        'selesai' => [
            'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
            'label' => 'Selesai'
        ]
    ];
    
    $config = $statusConfig[$status] ?? [
        'class' => 'bg-gray-100 text-gray-800 dark:text-gray-200',
        'label' => ucfirst($status)
    ];
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['class'] }}">
    {{ $config['label'] }}
</span>
