@extends('layouts.sec')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/patrons/directory.css') }}">
    <link rel="stylesheet" href="{{ asset('css/attendance_logs/page.css') }}">
@endsection

@section('content')
@php
    $queryParams = request()->query();
    $hasFilters = request()->hasAny(['search', 'from', 'to', 'course_code', 'year_level', 'per_page']);
@endphp
<div class="attn-logs">
    <header class="attn-logs__hero">
        <div>
            <p class="attn-logs__eyebrow">Reports · gate terminal</p>
            <h1 class="attn-logs__title">Attendance logs</h1>
            <p class="attn-logs__subtitle">School gate IN/OUT scans. Filter by date, program, or student, then export or open analytics.</p>
        </div>
        <div class="attn-logs__hero-actions">
            <a href="{{ route('attendance.scan') }}" class="attn-logs__btn attn-logs__btn--outline">Gate terminal</a>
            <a href="{{ route('book.index') }}" class="attn-logs__btn attn-logs__btn--outline">← Catalog</a>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success attn-logs__alert">{{ session('success') }}</div>
    @endif

    <nav class="attn-logs__quick-actions" aria-label="Attendance log actions">
        <a href="{{ route('attendance_logs.reports.hub') }}" class="attn-logs__quick-action attn-logs__quick-action--primary">
            Reports &amp; analytics
        </a>
        <a href="{{ route('attendance_logs.export.pdf', $queryParams) }}" class="attn-logs__quick-action">
            Export PDF
        </a>
        <a href="{{ route('attendance_logs.export.excel', $queryParams) }}" class="attn-logs__quick-action">
            Export Excel
        </a>
    </nav>

    <div class="attn-logs__filters-card">
        <form method="GET" action="{{ route('attendance_logs.index') }}" class="attn-logs__filters">
            <div class="attn-logs__field" style="flex: 2 1 200px;">
                <label for="attn_search">Search</label>
                <input type="text" name="search" id="attn_search" class="form-control"
                       placeholder="Name, program, status…" value="{{ request('search') }}">
            </div>
            <div class="attn-logs__field">
                <label for="attn_from">From</label>
                <input type="date" name="from" id="attn_from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="attn-logs__field">
                <label for="attn_to">To</label>
                <input type="date" name="to" id="attn_to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="attn-logs__field">
                <label for="attn_program">Program</label>
                <select name="course_code" id="attn_program" class="form-select">
                    <option value="">All programs</option>
                    @foreach($courses as $course)
                        <option value="{{ $course }}" @selected(request('course_code') == $course)>
                            {{ $programs->firstWhere('program_code', $course)?->program_name ?? $course }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="attn-logs__field">
                <label for="attn_year">Year level</label>
                <select name="year_level" id="attn_year" class="form-select">
                    <option value="">All years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected(request('year_level') == $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="attn-logs__filter-actions">
                <button type="submit" class="attn-logs__btn attn-logs__btn--primary">Apply filters</button>
                @if($hasFilters)
                    <a href="{{ route('attendance_logs.index') }}" class="attn-logs__btn attn-logs__btn--outline">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="attn-logs__meta">
        <span><strong>{{ number_format($logs->total()) }}</strong> scan{{ $logs->total() === 1 ? '' : 's' }} found</span>
        @if($hasFilters)
            <span>Filters active</span>
        @endif
    </div>

    <div class="attn-logs__card">
        @if($logs->total() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Program</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Scanned at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            @php
                                $student = $log->student;
                                $programLabel = $student
                                    ? ($programs->firstWhere('program_code', $student->course)?->program_name ?? $student->course)
                                    : null;
                                $status = strtolower((string) $log->status);
                                $scanned = $log->scanned_at?->timezone('Asia/Manila');
                            @endphp
                            <tr>
                                <td>
                                    @if($student)
                                        <div class="attn-logs__person-name">
                                            {{ $student->lastname }}, {{ $student->firstname }}
                                        </div>
                                        @if($student->id_number)
                                            <div class="attn-logs__person-meta">ID {{ $student->id_number }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted">Unknown student</span>
                                    @endif
                                </td>
                                <td>
                                    @if($programLabel)
                                        <span class="attn-logs__chip">{{ $programLabel }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student?->year)
                                        <span class="attn-logs__chip attn-logs__chip--muted">{{ $student->year }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($status === 'in')
                                        <span class="attn-logs__status attn-logs__status--in">In</span>
                                    @elseif($status === 'out')
                                        <span class="attn-logs__status attn-logs__status--out">Out</span>
                                    @else
                                        <span class="attn-logs__status attn-logs__status--unknown">{{ $log->status ?? '—' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($scanned)
                                        <span class="attn-logs__time">
                                            {{ $scanned->format('M j, Y') }}
                                            <small>{{ $scanned->format('g:i A') }}</small>
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('layouts.partials.pagination_bar', ['paginator' => $logs])
        @else
            <div class="attn-logs__empty">
                <p class="mb-2">No attendance records match your filters.</p>
                <a href="{{ route('attendance_logs.index') }}" class="attn-logs__btn attn-logs__btn--outline">Clear filters</a>
            </div>
        @endif
    </div>
</div>
@endsection
