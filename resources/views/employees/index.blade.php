@extends('layouts.sec')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/patrons/directory.css') }}">
@endsection

@section('content')
<div class="patron-dir">
    <header class="patron-dir__hero">
        <div>
            <p class="patron-dir__eyebrow">Patron data</p>
            <h1 class="patron-dir__title">Faculty &amp; staff</h1>
            <p class="patron-dir__subtitle">Search, register, and manage employee patron records.</p>
        </div>
        <div class="patron-dir__hero-actions">
            <a href="{{ route('employees.create') }}" class="patron-dir__btn patron-dir__btn--primary">+ Register patron</a>
            <a href="{{ route('book.index') }}" class="patron-dir__btn patron-dir__btn--outline">← Catalog</a>
        </div>
    </header>

    @include('patrons.partials.type_tabs', ['active' => 'employees'])

    @include('patrons.partials.quick_actions_employee')

    @if(session('success'))
        <div class="alert alert-success patron-dir__alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger patron-dir__alert">{{ session('error') }}</div>
    @endif

    <div class="patron-dir__toolbar">
        <form action="{{ route('employees.index') }}" method="GET" class="patron-dir__filters">
            <div class="patron-dir__field">
                <label for="employee_search">Search</label>
                <input type="text" name="search" id="employee_search" class="form-control"
                       placeholder="Name, ID, designation…" value="{{ request('search') }}">
            </div>
            <div class="patron-dir__field">
                <label for="employee_program">Program</label>
                <select name="program" id="employee_program" class="form-select">
                    <option value="">All programs</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->program_code }}" @selected(request('program') === $program->program_code)>
                            {{ $program->program_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="patron-dir__field">
                <label for="employee_start_year">Start year</label>
                <select name="year_start_work" id="employee_start_year" class="form-select">
                    <option value="">All years</option>
                    @foreach ($workStartYears as $yr)
                        <option value="{{ $yr }}" @selected(request('year_start_work') == (string) $yr)>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            <div class="patron-dir__filter-btn">
                <button type="submit" class="patron-dir__btn patron-dir__btn--outline">Apply</button>
            </div>
        </form>
    </div>

    <div class="patron-dir__meta">
        <span class="patron-dir__meta-item"><strong>{{ number_format($faculty->total()) }}</strong> registered</span>
        @if(request()->hasAny(['search', 'program', 'year_start_work', 'per_page']))
            <span class="patron-dir__meta-item">
                <a href="{{ route('employees.index') }}" class="text-decoration-none">Clear filters</a>
            </span>
        @endif
    </div>

    <div class="patron-dir__card">
        @if($faculty->total() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Patron</th>
                            <th>Designation</th>
                            <th>Program</th>
                            <th>Start</th>
                            <th class="text-end" style="width: 3rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($faculty as $employee)
                            @php
                                $programLabel = $programs->firstWhere('program_code', $employee->program)?->program_name
                                    ?? $employee->program
                                    ?? $employee->department;
                            @endphp
                            <tr>
                                <td>
                                    <div class="patron-dir__person">
                                        @if ($employee->formal_picture)
                                            <img src="{{ asset($employee->formal_picture) }}" alt="" class="patron-dir__avatar">
                                        @else
                                            <span class="patron-dir__avatar patron-dir__avatar--empty">N/A</span>
                                        @endif
                                        <div>
                                            <div class="patron-dir__person-name">
                                                {{ $employee->lastname }}, {{ $employee->firstname }}
                                                @if($employee->middle_initial)
                                                    {{ $employee->middle_initial }}.
                                                @endif
                                            </div>
                                            <div class="patron-dir__person-meta">
                                                {{ $employee->employee_id }}
                                                @if($employee->qrcode)
                                                    · <span class="patron-dir__code">{{ $employee->qrcode }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->designation ?? $employee->position ?? '—' }}</td>
                                <td><span class="patron-dir__chip">{{ $programLabel ?: '—' }}</span></td>
                                <td><span class="patron-dir__chip patron-dir__chip--muted">{{ $employee->year_start_work ?? '—' }}</span></td>
                                <td class="text-end">
                                    @include('patrons.partials.row_menu_employee', ['employee' => $employee])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('layouts.partials.pagination_bar', ['paginator' => $faculty])
        @else
            <div class="patron-dir__empty">
                <div class="patron-dir__empty-icon">👥</div>
                <p class="mb-2">No faculty or staff match your filters.</p>
                <a href="{{ route('employees.create') }}" class="patron-dir__btn patron-dir__btn--primary">Register first patron</a>
            </div>
        @endif
    </div>
</div>
@endsection
