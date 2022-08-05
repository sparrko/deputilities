@if ($item->created_at || $item->updated_at || $item->archived_at)
    <div class="edit_miniInfo">
        @if (isset($item->created_at) && $item->created_at)
            <p>Дата создания: <span>{{ Carbon\Carbon::parse($item->created_at)->toDateString() }}</span></p>
        @endif
        @if (isset($item->updated_at) && $item->updated_at)
            <p>Дата последнего изменения: <span>{{ Carbon\Carbon::parse($item->updated_at)->toDateString() }}</span></p>
        @endif
        @if (isset($item->archived_at) && $item->archived_at)
            <p>Дата архивации: <span>{{ Carbon\Carbon::parse($item->archived_at)->toDateString() }}</span></p>
        @endif
    </div>
@endif
