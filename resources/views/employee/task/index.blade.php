@extends('employee.layouts.master')

@section('title', 'Tasks')

@php
    $pageTitle   = 'Tasks';
    $activePage  = 'tasks';
    $breadcrumbs = [['label' => 'Tasks']];

    $pending = $tasks->where('status', 'pending')->count();
    $done    = $tasks->where('status', 'done')->count();
    $total   = $tasks->count();
    $donePct = $total > 0 ? round(($done / $total) * 100) : 0;

    $headerCta = session('role') == 'HR'
        ? ['label' => 'New Task', 'url' => route('tasks.create')]
        : null;
@endphp

@section('content')

{{-- ===== METRICS ===== --}}
<div class="tasks-metrics">

    <div class="m-card">
        <div class="m-label">Total Tasks</div>
        <div class="m-val">{{ $total }}</div>
        <div class="m-note">semua tugas</div>
        <div class="m-bar">
            <div class="m-fill" style="width:100%;background:var(--accent)"></div>
        </div>
    </div>

    <div class="m-card">
        <div class="m-label">Selesai</div>
        <div class="m-val">{{ $done }}</div>
        <div class="m-note" style="color:var(--green-text)">{{ $donePct }}%</div>
        <div class="m-bar">
            <div class="m-fill" style="width:{{ $donePct }}%;background:var(--green)"></div>
        </div>
    </div>

    <div class="m-card">
        <div class="m-label">Pending</div>
        <div class="m-val">{{ $pending }}</div>
        <div class="m-note" style="color:var(--amber-text)">perlu diselesaikan</div>
        <div class="m-bar">
            <div class="m-fill" style="width:{{ $total > 0 ? round(($pending/$total)*100) : 0 }}%;background:var(--amber)"></div>
        </div>
    </div>

</div>

{{-- ===== SESSION SUCCESS ===== --}}
@if(session('success'))
    <div class="alert-success-bar">
        {{ session('success') }}
    </div>
@endif

{{-- ===== TABLE (desktop) ===== --}}
<div class="card task-table-wrap">
    <div class="panel-head">
        <span class="panel-title">Daftar Tasks</span>
        @if(session('role') == 'HR')
            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm" style="margin-left:auto">
                <svg viewBox="0 0 14 14"><path d="M7 1v12M1 7h12"/></svg>
                <span>New Task</span>
            </a>
        @endif
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Karyawan</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>
                            <span style="font-weight:500;color:var(--text-primary)">
                                {{ $task->title }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div class="av av-xs" style="background:var(--accent);color:#fff">
                                    {{ strtoupper(substr($task->employee->fullname, 0, 2)) }}
                                </div>
                                {{ $task->employee->fullname }}
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                        <td>
                            @if($task->status == 'pending')
                                <span class="badge badge-amber">Pending</span>
                            @elseif($task->status == 'done')
                                <span class="badge badge-green">Done</span>
                            @else
                                <span class="badge badge-gray">{{ $task->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm">Detail</a>
                                @if($task->status == 'pending')
                                    <a href="{{ route('tasks.done', $task->id) }}" class="btn btn-sm" style="background:var(--green-soft);border-color:var(--green-text);color:var(--green-text)">Mark Done</a>
                                @else
                                    <a href="{{ route('tasks.pending', $task->id) }}" class="btn btn-sm" style="background:var(--amber-soft);border-color:var(--amber-text);color:var(--amber-text)">Mark Pending</a>
                                @endif
                                @if(session('role') == 'HR')
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin?')">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--text-muted);padding:32px">
                            Belum ada task
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== CARD LIST (mobile) ===== --}}
<div class="task-card-list">

    @if(session('role') == 'HR')
        <a href="{{ route('tasks.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:12px">
            <svg viewBox="0 0 14 14"><path d="M7 1v12M1 7h12"/></svg>
            New Task
        </a>
    @endif

    @forelse($tasks as $task)
        <div class="task-card-item">

            {{-- Header: avatar + nama + badge --}}
            <div class="task-card-header">
                <div class="av av-sm" style="background:var(--accent);color:#ede9e3;flex-shrink:0">
                    {{ strtoupper(substr($task->employee->fullname, 0, 2)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div class="task-card-title">{{ $task->title }}</div>
                    <div class="task-card-sub">{{ $task->employee->fullname }}</div>
                </div>
                @if($task->status == 'pending')
                    <span class="badge badge-amber">Pending</span>
                @elseif($task->status == 'done')
                    <span class="badge badge-green">Done</span>
                @else
                    <span class="badge badge-gray">{{ $task->status }}</span>
                @endif
            </div>

            {{-- Due date --}}
            <div class="task-card-due">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                    <rect x="1.5" y="2" width="13" height="12" rx="2"/>
                    <path d="M5 2V5M11 2V5M1.5 7h13"/>
                </svg>
                Due: {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
            </div>

            {{-- Aksi --}}
            <div class="task-card-actions">
                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm" style="flex:1;justify-content:center">Detail</a>

                @if($task->status == 'pending')
                    <a href="{{ route('tasks.done', $task->id) }}" class="btn btn-sm" style="flex:1;justify-content:center;background:var(--green-soft);border-color:var(--green-text);color:var(--green-text)">
                        Mark Done
                    </a>
                @else
                    <a href="{{ route('tasks.pending', $task->id) }}" class="btn btn-sm" style="flex:1;justify-content:center;background:var(--amber-soft);border-color:var(--amber-text);color:var(--amber-text)">
                        Mark Pending
                    </a>
                @endif

                @if(session('role') == 'HR')
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm" style="justify-content:center">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin?')">Hapus</button>
                    </form>
                @endif
            </div>

        </div>
    @empty
        <div class="card" style="text-align:center;color:var(--text-muted);padding:32px">
            Belum ada task
        </div>
    @endforelse

</div>

@endsection