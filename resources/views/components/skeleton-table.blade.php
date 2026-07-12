@props([
    'rows' => 5,
    'cols' => 4,
    'class' => '',
])

<div class="skeleton-wrapper {{ $class }}" aria-hidden="true">
    <div class="skeleton-header">
        @for($c = 0; $c < $cols; $c++)
            <span class="skeleton-cell skeleton-th" style="width: {{ 100 / $cols }}%"></span>
        @endfor
    </div>
    @for($r = 0; $r < $rows; $r++)
        <div class="skeleton-row">
            @for($c = 0; $c < $cols; $c++)
                <span class="skeleton-cell" style="width: {{ 100 / $cols }}%"></span>
            @endfor
        </div>
    @endfor
</div>

<style>
.skeleton-wrapper { display: flex; flex-direction: column; gap: 0; border-radius: var(--radius-md, 12px); overflow: hidden; background: var(--card-bg, #fff); border: 1px solid var(--card-border, #e2e8f0); }
body.dark .skeleton-wrapper { background: var(--card-bg-dark, #1e293b); border-color: var(--card-border-dark, #334155); }
.skeleton-header { display: flex; padding: 14px 16px; gap: 8px; border-bottom: 2px solid var(--card-border, #e2e8f0); }
body.dark .skeleton-header { border-color: var(--card-border-dark, #334155); }
.skeleton-row { display: flex; padding: 12px 16px; gap: 8px; border-bottom: 1px solid var(--card-border, #e2e8f0); }
body.dark .skeleton-row { border-color: var(--card-border-dark, #334155); }
.skeleton-row:last-child { border-bottom: none; }
.skeleton-cell {
    display: block;
    height: 14px;
    border-radius: 6px;
    background: linear-gradient(90deg, 
        rgba(148,163,184,0.08) 0%, 
        rgba(148,163,184,0.18) 50%, 
        rgba(148,163,184,0.08) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s ease-in-out infinite;
}
body.dark .skeleton-cell {
    background: linear-gradient(90deg, 
        rgba(255,255,255,0.03) 0%, 
        rgba(255,255,255,0.1) 50%, 
        rgba(255,255,255,0.03) 100%
    );
    background-size: 200% 100%;
}
.skeleton-th { height: 12px; }
@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
