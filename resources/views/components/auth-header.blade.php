@props([
    'title',
    'description',
    'titleClass' => '', // clase opcional para el título
    'descClass' => '',  // clase opcional para la descripción
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl" class="{{ $titleClass }}">{{ $title }}</flux:heading>
    <flux:subheading class="{{ $descClass }}">{{ $description }}</flux:subheading>
</div>
