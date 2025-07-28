@props(['label', 'name', 'type' => 'text'])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'mt-1 block w-full rounded-md p-2 border ' . ($errors->has($name) ? 'border-red-600' : 'border-gray-300')
            ]) }}
    >

    @error($name)
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
