@extends('layouts.admin')

@section('title', 'Leaders')
@section('page-title', 'Leaders')

@section('content')
@php
    $levels = config('leader.levels', ['association', 'church']);
    $statuses = config('leader.statuses', ['active', 'inactive']);
@endphp
<div class="section-gap">
    <x-table
        title="Leaders"
        :headers="['Name', 'Email', 'Church', 'Actions']"
        search-placeholder="Search leaders"
        table-id="leaders-table"
    >
        <x-slot:actions>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#leaderModal" id="leaderCreateBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Leader
            </button>
        </x-slot:actions>
        <tr><td colspan="4" class="text-muted">Loading...</td></tr>
    </x-table>
</div>

<div class="modal fade" id="leaderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="leaderForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaderModalTitle">Create Leader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="leaderId">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="leaderName" required>
                        <div class="invalid-feedback" id="leaderNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <input type="text" class="form-control" id="leaderPosition" required>
                        <div class="invalid-feedback" id="leaderPositionError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="leaderEmail" required>
                        <div class="invalid-feedback" id="leaderEmailError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="leaderPhone">
                        <div class="invalid-feedback" id="leaderPhoneError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Church</label>
                        <select class="form-select" id="leaderChurch" required></select>
                        <div class="invalid-feedback" id="leaderChurchError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Level</label>
                        <select class="form-select" id="leaderLevel" required>
                            @foreach($levels as $level)
                                <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="leaderLevelError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="leaderStatus" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="leaderStatusError"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Term Start</label>
                            <input type="date" class="form-control" id="leaderTermStart">
                            <div class="invalid-feedback" id="leaderTermStartError"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Term End</label>
                            <input type="date" class="form-control" id="leaderTermEnd">
                            <div class="invalid-feedback" id="leaderTermEndError"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="leaderSubmitBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="leaderSubmitSpinner"></span>
                        Save Leader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/leaders.js"></script>
@endpush
