<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route' => '#',
    'placeholder' => 'Search...',
    'name' => 'q',
    'value' => request($name),
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'route' => '#',
    'placeholder' => 'Search...',
    'name' => 'q',
    'value' => request($name),
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<form method="GET" action="<?php echo e($route); ?>" class="search-bar" role="search">
    <div class="search-input-wrap">
        <span class="search-icon">🔍</span>
        <input
            type="text"
            name="<?php echo e($name); ?>"
            value="<?php echo e($value); ?>"
            placeholder="<?php echo e($placeholder); ?>"
            class="search-input"
            autocomplete="off"
        />
        <?php if($value): ?>
            <a href="<?php echo e(url()->current()); ?>" class="search-clear" aria-label="Clear search">✕</a>
        <?php endif; ?>
    </div>
</form>

<style>
.search-bar { width: 100%; max-width: 320px; }
.search-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.search-icon {
    position: absolute;
    left: 12px;
    font-size: 0.95rem;
    pointer-events: none;
    z-index: 2;
    opacity: 0.6;
}
.search-input {
    width: 100%;
    padding: 10px 36px 10px 38px;
    border: 1.5px solid var(--card-border, #e2e8f0);
    border-radius: var(--radius-pill, 9999px);
    background: var(--card-bg, #fff);
    color: var(--text-primary, #1e293b);
    font-size: 0.88rem;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.3s;
    outline: none;
}
.search-input:focus {
    border-color: var(--primary, #3b82f6);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.search-input::placeholder { color: var(--text-muted, #64748b); opacity: 0.7; }
body.dark .search-input { background: #0f172a; border-color: var(--card-border-dark, #334155); color: var(--text-on-dark, #e2e8f0); }
.search-clear {
    position: absolute;
    right: 10px;
    width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    background: rgba(100,116,139,0.1);
    color: var(--text-muted, #64748b);
    font-size: 0.7rem;
    text-decoration: none;
    transition: background 0.2s;
}
.search-clear:hover { background: rgba(239,68,68,0.15); color: var(--danger, #ef4444); }
@media (max-width: 767px) {
    .search-bar { max-width: 100%; }
}
</style>
<?php /**PATH /Users/homehub/.openclaw/workspace/clubops/resources/views/components/search-bar.blade.php ENDPATH**/ ?>