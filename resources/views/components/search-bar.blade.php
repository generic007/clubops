@props([
    'route' => '#',
    'placeholder' => 'Search...',
    'name' => 'q',
    'value' => request($name),
])

<form method="GET" action="{{ $route }}" class="search-bar" role="search">
    <div class="search-input-wrap">
        <span class="search-icon">🔍</span>
        <input
            type="text"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            class="search-input"
            autocomplete="off"
        />
        @if($value)
            <a href="{{ url()->current() }}" class="search-clear" aria-label="Clear search">✕</a>
        @endif
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
