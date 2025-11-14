@extends('layouts.admin')
@section('title', 'Riwayat Pencaker')
@section('content')
    <div class="max-w-6xl mx-auto p-6">
        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Riwayat Aktivitas</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $profile->nama_lengkap ?? $user->name }} · {{ $user->email }}
                </p>
            </div>
            <a href="{{ route('admin.pencaker.index') }}" 
               class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white text-sm transition">
                Kembali
            </a>
        </div>

        {{-- Timeline --}}
        <div class="bg-slate-900/70 rounded-xl border border-slate-800 p-6">
            @if($allLogs->isEmpty())
                <div class="text-center py-12 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Belum ada riwayat aktivitas</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($allLogs as $logEntry)
                        @php
                            $log = $logEntry['data'];
                            $isActivity = $logEntry['type'] === 'activity';
                            $isAk1 = $logEntry['type'] === 'ak1';
                        @endphp

                        <div class="flex gap-4 relative">
                            {{-- Timeline line --}}
                            @if(!$loop->last)
                                <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-slate-700"></div>
                            @endif

                            {{-- Icon --}}
                            <div class="flex-shrink-0">
                                @if($isActivity)
                                    @php
                                        $action = $log->action;
                                        $iconColor = match($action) {
                                            'created' => 'bg-emerald-500',
                                            'updated' => 'bg-blue-500',
                                            'deleted' => 'bg-rose-500',
                                            default => 'bg-slate-500'
                                        };
                                    @endphp
                                    <div class="w-10 h-10 rounded-full {{ $iconColor }} flex items-center justify-center text-white text-sm font-semibold">
                                        @if($action === 'created')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        @elseif($action === 'updated')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        @endif
                                    </div>
                                @else
                                    @php
                                        $action = $log->action;
                                        $iconColor = match($action) {
                                            'approve' => 'bg-emerald-500',
                                            'reject' => 'bg-rose-500',
                                            'revision' => 'bg-amber-500',
                                            'submitted' => 'bg-blue-500',
                                            default => 'bg-indigo-500'
                                        };
                                    @endphp
                                    <div class="w-10 h-10 rounded-full {{ $iconColor }} flex items-center justify-center text-white text-sm font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pb-6">
                                <div class="bg-slate-800/50 rounded-lg p-4 border border-slate-700">
                                    @if($isActivity)
                                        {{-- Activity Log --}}
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                                                    @if($log->action === 'created') bg-emerald-500/20 text-emerald-300
                                                    @elseif($log->action === 'updated') bg-blue-500/20 text-blue-300
                                                    @else bg-rose-500/20 text-rose-300
                                                    @endif">
                                                    {{ strtoupper($log->action) }}
                                                </span>
                                                <span class="ml-2 text-xs text-slate-400">
                                                    {{ class_basename($log->model_type) }}
                                                </span>
                                            </div>
                                            <time class="text-xs text-slate-400">
                                                {{ $log->created_at->format('d M Y, H:i') }}
                                            </time>
                                        </div>
                                        <p class="text-sm text-slate-200">
                                            {{ $log->description }}
                                        </p>
                                    @else
                                        {{-- AK1 Log --}}
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                                                    @if($log->action === 'approve') bg-emerald-500/20 text-emerald-300
                                                    @elseif($log->action === 'reject') bg-rose-500/20 text-rose-300
                                                    @elseif($log->action === 'revision') bg-amber-500/20 text-amber-300
                                                    @else bg-blue-500/20 text-blue-300
                                                    @endif">
                                                    {{ strtoupper($log->action) }}
                                                </span>
                                                <span class="ml-2 text-xs text-slate-400">
                                                    Pengajuan AK1
                                                </span>
                                                @if($log->application->nomor_ak1)
                                                    <span class="ml-2 text-xs text-slate-300 font-medium">
                                                        No. AK1: {{ $log->application->nomor_ak1 }}
                                                    </span>
                                                @endif
                                            </div>
                                            <time class="text-xs text-slate-400">
                                                {{ $log->created_at->format('d M Y, H:i') }}
                                            </time>
                                        </div>
                                        
                                        <div class="space-y-1 text-sm text-slate-200">
                                            @if($log->from_status && $log->to_status)
                                                <p>
                                                    Status: 
                                                    <span class="text-slate-400">{{ $log->from_status }}</span>
                                                    <span class="mx-2">→</span>
                                                    <span class="font-medium">{{ $log->to_status }}</span>
                                                </p>
                                            @endif
                                            
                                            @if($log->actor)
                                                <p class="text-xs text-slate-400">
                                                    Oleh: {{ $log->actor->name }}
                                                </p>
                                            @endif
                                            
                                            @if($log->notes)
                                                <p class="mt-2 p-2 bg-slate-900/50 rounded text-xs text-slate-300">
                                                    {{ $log->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info tambahan --}}
        <div class="mt-6 text-center text-sm text-slate-400">
            <p>Total: {{ $allLogs->count() }} aktivitas</p>
        </div>
    </div>
@endsection

