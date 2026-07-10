@extends('layouts.sec')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/patrons/directory.css') }}">
@endsection

@section('content')
@php
    $studentCount = $pendingStudents->count();
    $employeeCount = $pendingEmployees->count();
    $activeTab = $defaultTab ?? 'students';
@endphp
<div class="patron-dir">
    <header class="patron-dir__hero">
        <div>
            <p class="patron-dir__eyebrow">Patron data · review queue</p>
            <h1 class="patron-dir__title">Pending registrations</h1>
            <p class="patron-dir__subtitle">Approve or reject self-service sign-ups before they appear in the directory.</p>
        </div>
        <div class="patron-dir__hero-actions">
            <a href="{{ $backRoute ?? route('students.index') }}" class="patron-dir__btn patron-dir__btn--outline">← Back to directory</a>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success patron-dir__alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger patron-dir__alert">{{ session('error') }}</div>
    @endif

    <div class="patron-dir__stats">
        <div class="patron-dir__stat-card {{ $studentCount > 0 ? 'patron-dir__stat-card--alert' : '' }}">
            <div class="patron-dir__stat-card__value">{{ $studentCount }}</div>
            <div class="patron-dir__stat-card__label">Students waiting</div>
        </div>
        <div class="patron-dir__stat-card {{ $employeeCount > 0 ? 'patron-dir__stat-card--alert' : '' }}">
            <div class="patron-dir__stat-card__value">{{ $employeeCount }}</div>
            <div class="patron-dir__stat-card__label">Faculty &amp; staff waiting</div>
        </div>
    </div>

    <nav class="patron-dir__tabs" aria-label="Pending registration type" role="tablist">
        <button type="button"
                id="pending-tab-students"
                class="patron-dir__tab {{ $activeTab === 'students' ? 'active' : '' }}"
                role="tab"
                aria-selected="{{ $activeTab === 'students' ? 'true' : 'false' }}"
                aria-controls="pending-panel-students"
                data-pending-tab="students">
            Students
            @if($studentCount > 0)
                <span class="patron-dir__quick-action-count">{{ $studentCount }}</span>
            @endif
        </button>
        <button type="button"
                id="pending-tab-employees"
                class="patron-dir__tab {{ $activeTab === 'employees' ? 'active' : '' }}"
                role="tab"
                aria-selected="{{ $activeTab === 'employees' ? 'true' : 'false' }}"
                aria-controls="pending-panel-employees"
                data-pending-tab="employees">
            Faculty &amp; staff
            @if($employeeCount > 0)
                <span class="patron-dir__quick-action-count">{{ $employeeCount }}</span>
            @endif
        </button>
    </nav>

    <div id="pending-panel-students"
         class="patron-dir__pending-panel {{ $activeTab === 'students' ? 'is-active' : '' }}"
         role="tabpanel"
         aria-labelledby="pending-tab-students">
        <div class="patron-dir__card">
            @if($studentCount > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Program</th>
                                <th>Year</th>
                                <th>Submitted</th>
                                <th class="text-end">Decision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingStudents as $p)
                                @php
                                    $programLabel = $programs->firstWhere('program_code', $p->course)?->program_name ?? $p->course;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="patron-dir__person">
                                            @if($p->profile_picture)
                                                <img src="{{ asset($p->profile_picture) }}" alt="" class="patron-dir__avatar">
                                            @else
                                                <span class="patron-dir__avatar patron-dir__avatar--empty">N/A</span>
                                            @endif
                                            <div>
                                                <div class="patron-dir__person-name">{{ $p->lastname }}, {{ $p->firstname }}</div>
                                                <div class="patron-dir__person-meta">
                                                    @if($p->id_number) ID {{ $p->id_number }} @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="patron-dir__chip">{{ $programLabel ?: '—' }}</span></td>
                                    <td><span class="patron-dir__chip patron-dir__chip--muted">{{ $p->year ?: '—' }}</span></td>
                                    <td class="text-muted small">{{ $p->created_at?->timezone('Asia/Manila')->diffForHumans() }}</td>
                                    <td class="text-end">
                                        @include('patrons.partials.pending_decision_buttons', [
                                            'approveRoute' => route('students.approve', $p->id),
                                            'rejectRoute' => route('students.reject', $p->id),
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="patron-dir__empty">
                    <div class="patron-dir__empty-icon">✓</div>
                    <p class="mb-0">No pending student registrations.</p>
                </div>
            @endif
        </div>
    </div>

    <div id="pending-panel-employees"
         class="patron-dir__pending-panel {{ $activeTab === 'employees' ? 'is-active' : '' }}"
         role="tabpanel"
         aria-labelledby="pending-tab-employees">
        <div class="patron-dir__card">
            @if($employeeCount > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Designation</th>
                                <th>Program</th>
                                <th>Start year</th>
                                <th>Submitted</th>
                                <th class="text-end">Decision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingEmployees as $e)
                                @php
                                    $programLabel = $programs->firstWhere('program_code', $e->program)?->program_name
                                        ?? $e->program
                                        ?? $e->department;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="patron-dir__person">
                                            @if($e->formal_picture)
                                                <img src="{{ asset($e->formal_picture) }}" alt="" class="patron-dir__avatar">
                                            @else
                                                <span class="patron-dir__avatar patron-dir__avatar--empty">N/A</span>
                                            @endif
                                            <div>
                                                <div class="patron-dir__person-name">
                                                    {{ $e->lastname }}, {{ $e->firstname }}
                                                    @if($e->middle_initial) {{ $e->middle_initial }}. @endif
                                                </div>
                                                <div class="patron-dir__person-meta">{{ $e->employee_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $e->designation ?? $e->position ?? '—' }}</td>
                                    <td><span class="patron-dir__chip">{{ $programLabel ?: '—' }}</span></td>
                                    <td><span class="patron-dir__chip patron-dir__chip--muted">{{ $e->year_start_work ?? '—' }}</span></td>
                                    <td class="text-muted small">{{ $e->created_at?->timezone('Asia/Manila')->diffForHumans() }}</td>
                                    <td class="text-end">
                                        @include('patrons.partials.pending_decision_buttons', [
                                            'approveRoute' => route('employees.approve', $e->id),
                                            'rejectRoute' => route('employees.reject', $e->id),
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="patron-dir__empty">
                    <div class="patron-dir__empty-icon">✓</div>
                    <p class="mb-0">No pending faculty &amp; staff registrations.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        const tabs = document.querySelectorAll('[data-pending-tab]');
        const panels = {
            students: document.getElementById('pending-panel-students'),
            employees: document.getElementById('pending-panel-employees'),
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const key = tab.getAttribute('data-pending-tab');

                tabs.forEach((t) => {
                    const active = t === tab;
                    t.classList.toggle('active', active);
                    t.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                Object.entries(panels).forEach(([name, panel]) => {
                    panel?.classList.toggle('is-active', name === key);
                });
            });
        });
    })();
</script>
@endsection
