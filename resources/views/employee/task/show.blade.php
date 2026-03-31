@extends('employee.layouts.master')

@section('title', 'Detail Task')

@php
    $pageTitle   = 'Detail Task';
    $activePage  = 'tasks';
    $breadcrumbs = [
        ['label' => 'Tasks', 'url' => route('tasks.index')],
        ['label' => $task->title],
    ];
    $headerCta = null;
@endphp

@section('content')

<div class="tasks-show-grid">

    {{-- ===== DETAIL CARD ===== --}}
    <div class="card">
        <div class="panel-head">
            <span class="panel-title">Detail Task</span>
            <a href="{{ route('tasks.index') }}" class="btn btn-sm" style="margin-left:auto">
                ← Kembali
            </a>
        </div>

        {{-- Judul --}}
        <div class="detail-row">
            <div class="detail-label">Judul</div>
            <div class="detail-val">{{ $task->title }}</div>
        </div>

        {{-- Assigned To --}}
        <div class="detail-row">
            <div class="detail-label">Ditugaskan ke</div>
            <div class="detail-val">
                <div style="display:flex;align-items:center;gap:8px">
                    <div class="av av-sm" style="background:var(--accent);color:#ede9e3">
                        {{ strtoupper(substr($task->employee->fullname, 0, 2)) }}
                    </div>
                    {{ $task->employee->fullname }}
                </div>
            </div>
        </div>

        {{-- Due Date --}}
        <div class="detail-row">
            <div class="detail-label">Due Date</div>
            <div class="detail-val">
                {{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}
            </div>
        </div>

        {{-- Status --}}
        <div class="detail-row">
            <div class="detail-label">Status</div>
            <div class="detail-val">
                @if($task->status == 'done')
                    <span class="badge badge-green">Done</span>
                @elseif($task->status == 'pending')
                    <span class="badge badge-amber">Pending</span>
                @else
                    <span class="badge badge-gray">{{ ucfirst($task->status) }}</span>
                @endif
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="detail-row" style="border:none">
            <div class="detail-label">Deskripsi</div>
            <div class="detail-val" style="white-space:pre-line;line-height:1.7">
                {{ $task->description ?? '—' }}
            </div>
        </div>

    </div>

    {{-- ===== ACTION CARD ===== --}}
    <div class="card" style="align-self:start">
        <div class="panel-head">
            <span class="panel-title">Aksi</span>
        </div>

        <div style="display:flex;flex-direction:column;gap:8px">

            @if($task->status == 'pending')
                <a href="{{ route('tasks.done', $task->id) }}"
                   class="btn" style="background:var(--green-soft);border-color:var(--green-text);color:var(--green-text);justify-content:center">
                    ✓ Mark as Done
                </a>
            @else
                <a href="{{ route('tasks.pending', $task->id) }}"
                   class="btn" style="background:var(--amber-soft);border-color:var(--amber-text);color:var(--amber-text);justify-content:center">
                    ↺ Mark as Pending
                </a>
            @endif

            @if(session('role') == 'HR')
                <a href="{{ route('tasks.edit', $task->id) }}"
                   class="btn" style="justify-content:center">
                    Edit Task
                </a>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            style="width:100%;justify-content:center"
                            onclick="return confirm('Apakah Anda Yakin?')">
                        Hapus Task
                    </button>
                </form>
            @endif

            <a href="{{ route('tasks.index') }}" class="btn" style="justify-content:center">
                ← Kembali ke List
            </a>

        </div>
    </div>

</div>

@endsection