@props([
    'headers' => [],
    'title' => null,
    'actions' => null,
    'searchPlaceholder' => 'Search...',
    'tableId' => null,
])

<div class="card p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        @if($title)
            <h6 class="mb-0 heading">{{ $title }}</h6>
        @endif
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="input-group" style="max-width: 220px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input
                    type="text"
                    class="form-control border-start-0"
                    placeholder="{{ $searchPlaceholder }}"
                    @if($tableId) data-table-search="{{ $tableId }}" @endif
                >
            </div>
            {{ $actions }}
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" @if($tableId) id="{{ $tableId }}" @endif>
            <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            {{ $slot }}
            </tbody>
        </table>
    </div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
        <div class="small text-muted" @if($tableId) data-table-summary="{{ $tableId }}" @endif>Showing 0–0 of 0 results</div>
        <nav class="d-flex align-items-center gap-2 flex-wrap">
            <button class="btn btn-outline-secondary btn-sm" type="button" @if($tableId) data-table-prev="{{ $tableId }}" @endif>Previous</button>
            <div class="d-flex align-items-center gap-2" @if($tableId) data-table-pages="{{ $tableId }}" @endif></div>
            <button class="btn btn-outline-secondary btn-sm" type="button" @if($tableId) data-table-next="{{ $tableId }}" @endif>Next</button>
        </nav>
    </div>
</div>
