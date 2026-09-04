@if(!empty($program))
    @php
        $progMeta = \App\Models\Tenant::getProgramMeta($program);
    @endphp
    <div class="elog-program-badge">
        <span class="badge {{ $progMeta['badge_class'] }}"><i class="{{ $progMeta['icon'] }}"></i> {!! $progMeta['label'] !!}</span>
    </div>
@endif
