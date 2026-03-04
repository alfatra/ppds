@extends('layouts.master')

@section('title','Edit SOAP Log')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Edit SOAP Entry #{{ $log->id }}</h4>
        </div>
        <div class="mb-3">
    <label for="diagnosis-select" class="form-label">Diagnosa</label>
    <select id="diagnosis-select" name="diagnosis" placeholder="Ketik untuk mencari diagnosa...">
        {{-- Jika sedang mengedit, tampilkan diagnosa yang sudah tersimpan --}}
        @if(isset($log) && $log->diagnosis)
            <option value="{{ $log->diagnosis }}" selected>{{ $log->diagnosis }}</option>
        @endif
    </select>
</div>
        <div class="card-body">
            <form action="{{ route('ppds.soap-logs.update',$log) }}" method="POST">
                @method('PUT')
                @include('soap_logs.form')
                <button class="btn btn-primary">Update</button>

                {{-- Letakkan di dalam file form create.blade.php dan edit.blade.php --}}

{{-- ... (input subjective, objective, assessment, plan) ... --}}



{{-- ... (tombol submit) ... --}}


{{-- Tambahkan section ini di bagian bawah file --}}
@section('css')
    {{-- Tom-select untuk dropdown yang bisa dicari --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tomSelect = new TomSelect('#diagnosis-select', {
                // Field mapping - sesuaikan dengan struktur data API
                valueField: 'id',
                labelField: 'nm_diagnosa',
                searchField: ['nm_diagnosa', 'kd_diagnosa', 'name', 'code'],

                // Izinkan pengguna mengetik diagnosa baru jika tidak ada di daftar
                create: true,
                persist: false,
                maxOptions: 50,

                // Fungsi untuk memuat data dari API saat pengguna mengetik
                load: function(query, callback) {
                    const url = `{{ url('/api/diagnoses') }}?q=${encodeURIComponent(query)}`;
                    fetch(url)
                        .then(response => {
                            if (!response.ok) throw new Error('API Error');
                            return response.json();
                        })
                        .then(json => {
                            // Handle berbagai format API response
                            let data = json.data || json.result || json || [];
                            callback(data);

                            // Log untuk debugging
                            console.log('API Response:', json);
                        })
                        .catch((err) => {
                            console.error('Error loading diagnoses:', err);
                            callback();
                        });
                },

                // Kustomisasi tampilan item di dropdown
                render: {
                    option: function(item, escape) {
                        // Format nama yang ditampilkan
                        const name = item.nm_diagnosa || item.name || 'Unknown';
                        const code = item.kd_diagnosa || item.code || '';
                        
                        return `<div>
                                    <strong>${escape(name)}</strong>
                                    ${code ? `<div class="text-muted small">${escape(code)}</div>` : ''}
                                </div>`;
                    },
                    item: function(item, escape) {
                        const name = item.nm_diagnosa || item.name || item;
                        return `<div>${escape(name)}</div>`;
                    }
                }
            });
        });
    </script>
@endsection

            </form>
        </div>
    </div>
</div>
@endsection
