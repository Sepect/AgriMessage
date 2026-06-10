@props(['disabled' => false, 'label' => null, 'id' => null, 'type' => 'text', 'error' => null])

@php
    $id = $id ?? Str::random(8);
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }} 
            {!! $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-2.5 transition-colors ' . ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300')]) !!}
        >{{ $slot }}</textarea>
    @elseif($type === 'select')
        <select 
            id="{{ $id }}"
            {{ $disabled ? 'disabled' : '' }} 
            {!! $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-2.5 bg-white transition-colors ' . ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300')]) !!}
        >
            {{ $slot }}
        </select>
    @else
        <input 
            id="{{ $id }}"
            type="{{ $type }}" 
            {{ $disabled ? 'disabled' : '' }} 
            {!! $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-2.5 transition-colors ' . ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300')]) !!}
        >
    @endif
    
    @if($error)
        <p class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
