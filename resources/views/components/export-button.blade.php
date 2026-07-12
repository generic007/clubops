@props([
    'route' => '#',
    'label' => 'Export CSV',
    'class' => '',
])

<a href="{{ $route }}" class="btn btn-outline-secondary btn-sm {{ $class }}" role="button">
    📥 {{ $label }}
</a>
