@props([
    'headers' => [],
    'title' => null,
    'actions' => null,
])

<div class="card p-4">
    @if($title)
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h6 class="mb-0 heading">{{ $title }}</h6>
            <div class="d-flex gap-2">
                {{ $actions }}
            </div>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover align-middle">
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
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
        <div class="small text-muted">Showing 1 to 10 of 100 entries</div>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Prev</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>
