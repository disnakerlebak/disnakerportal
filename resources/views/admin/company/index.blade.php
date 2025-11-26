@extends('layouts.admin')

@section('title', 'Kelola Perusahaan')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
        </div>

        <livewire:admin.manage-companies-table />
    </div>
@endsection

