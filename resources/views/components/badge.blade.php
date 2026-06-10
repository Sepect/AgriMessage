@props(['status' => 'draft'])

@php
    $styles = [
        'draft' => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-500/20',
        'scheduled' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
        'sent' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
        'failed' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10',
        'active' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
        'inactive' => 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-500/20',
    ];
    $labels = [
        'draft' => 'Draft',
        'scheduled' => 'Dijadwalkan',
        'sent' => 'Terkirim',
        'failed' => 'Gagal',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
    ];
    
    $style = $styles[$status] ?? 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10';
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $style]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</span>
