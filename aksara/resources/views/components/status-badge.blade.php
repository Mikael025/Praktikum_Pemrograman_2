@props(['status'])

@php
    $statusConfig = [
        'diusulkan' => [
            'class' => 'bg-blue-100 text-blue-800',
            'label' => 'Diusulkan'
        ],
        'tidak_lolos' => [
            'class' => 'bg-red-100 text-red-800',
            'label' => 'Tidak Lolos'
        ],
        'lolos_perlu_revisi' => [
            'class' => 'bg-yellow-100 text-yellow-800',
            'label' => 'Lolos, Perlu Revisi'
        ],
        'lolos' => [
            'class' => 'bg-green-100 text-green-800',
            'label' => 'Lolos'
        ],
        'revisi_pra_final' => [
            'class' => 'bg-orange-100 text-orange-800',
            'label' => 'Revisi Pra-final'
        ],
        'selesai' => [
            'class' => 'bg-emerald-100 text-emerald-800',
            'label' => 'Selesai'
        ]
    ];
    
    $config = $statusConfig[$status] ?? [
        'class' => 'bg-gray-100 text-gray-800',
        'label' => ucfirst($status)
    ];
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['class'] }}">
    {{ $config['label'] }}
</span>
