@extends('layouts.master')
@section('title') Tambah Kegiatan Harian @endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Tambah Kegiatan Harian</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Logbook</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('daily-activities.index') }}">Kegiatan Harian</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-10 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white pt-4 pb-3">
                <h4 class="card-title mb-0 text-white"><i class="ri-add-circle-line align-middle me-2"></i>Form Tambah Kegiatan Harian</h4>
                <p class="card-title-desc text-white-50 mb-0 mt-1">Silakan isi form di bawah ini untuk mencatat tindakan yang Anda lakukan hari ini.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('daily-activities.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-calendar-event-line text-primary me-1"></i> Tanggal Kegiatan <span class="text-danger">*</span></label>
                            <input type="date" name="activity_date" class="form-control form-control-lg @error('activity_date') is-invalid @enderror" value="{{ old('activity_date', date('Y-m-d')) }}" required>
                            @error('activity_date')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label class="form-label fw-bold"><i class="ri-stethoscope-line text-primary me-1"></i> Tindakan Medis <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg @error('medical_activity_id') is-invalid @enderror" name="medical_activity_id" required>
                                <option value="">-- Pilih Tindakan Medis --</option>
                                @foreach($medicalActivities as $ma)
                                    <option value="{{ $ma->id }}" {{ old('medical_activity_id') == $ma->id ? 'selected' : '' }}>
                                        {{ $ma->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medical_activity_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="card border border-light bg-light shadow-none mb-4">
                        <div class="card-body">
                            <h5 class="font-size-15 mb-3 text-primary"><i class="ri-user-heart-line me-1"></i> Data Pasien</h5>
                            
                            <div class="mb-4">
                                <label for="patient_search" class="form-label fw-bold">Pencarian Pasien (No. Reg / Nama) <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="ri-search-line text-muted"></i></span>
                                        <input 
                                            type="text" 
                                            id="patient_search" 
                                            class="form-control form-control-lg" 
                                            placeholder="Ketik nama atau nomor registrasi lalu pilih dari daftar..." 
                                            autocomplete="off"
                                            required>
                                    </div>
                                    <div id="patient_suggestions" class="list-group position-absolute w-100 shadow-lg border-0" style="display:none; top: 100%; z-index: 1000; max-height: 300px; overflow-y: auto; border-radius: 0 0 0.5rem 0.5rem;">
                                        <!-- Patient suggestions akan ditampilkan di sini -->
                                    </div>
                                </div>
                                <small class="form-text text-muted mt-2"><i class="ri-information-line me-1"></i>Data ditarik otomatis dan terintegrasi secara *real-time* dari server Medinfras.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-muted">Nama Pasien</label>
                                    <input type="text" name="patient_name" id="patient_name" class="form-control bg-white @error('patient_name') is-invalid @enderror" value="{{ old('patient_name') }}" readonly required tabindex="-1">
                                    @error('patient_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">No. Rekam Medis (RM)</label>
                                    <input type="text" name="medical_record_no" id="medical_record_no" class="form-control bg-white @error('medical_record_no') is-invalid @enderror" value="{{ old('medical_record_no') }}" readonly required tabindex="-1">
                                    @error('medical_record_no')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="ri-file-text-line text-primary me-1"></i> Keterangan / Catatan Tambahan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="5" placeholder="Tulis catatan, instruksi, atau observasi tambahan di sini (opsional)...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end align-items-center">
                        <a href="{{ route('daily-activities.index') }}" class="btn btn-light btn-lg waves-effect me-3">
                            <i class="ri-arrow-go-back-line align-middle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">
                            <i class="ri-save-line align-middle me-1"></i> Simpan Kegiatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const apiBaseUrl = (document.querySelector('base') ? document.querySelector('base').href.replace(/\/$/, '') : window.location.origin) + '/api';
        const patientSearchInput = document.getElementById('patient_search');
        const patientSuggestions = document.getElementById('patient_suggestions');
        const patientNameInput = document.getElementById('patient_name');
        const medicalRecordNoInput = document.getElementById('medical_record_no');
        let allPatients = [];

        function normalizeKey(key) {
            return key.replace(/([A-Z])/g, '_$1').toLowerCase().replace(/^_/, '');
        }

        function getPatientValue(patient, keys) {
            if (!patient || typeof patient !== 'object') return '';
            const objectsToSearch = [patient];
            if (patient.Patient && typeof patient.Patient === 'object') objectsToSearch.push(patient.Patient);
            if (patient.patient && typeof patient.patient === 'object') objectsToSearch.push(patient.patient);
            if (patient.PatientInfo) {
                try {
                    const patientInfo = typeof patient.PatientInfo === 'string' ? JSON.parse(patient.PatientInfo) : patient.PatientInfo;
                    if (typeof patientInfo === 'object') objectsToSearch.push(patientInfo);
                } catch (e) {}
            }

            for (const key of keys) {
                for (const target of objectsToSearch) {
                    if (target[key] !== undefined && target[key] !== null && target[key] !== '') return target[key];
                }
            }
            return '';
        }

        async function loadPatients(searchQuery = '') {
            try {
                let url = `${apiBaseUrl}/pasien`;
                if (searchQuery.length > 0) {
                    url = `${apiBaseUrl}/pasien?registrationNo=${encodeURIComponent(searchQuery)}&periodeRegistrationDate=${new Date().toISOString().split('T')[0]}`;
                }

                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();
                if (result.success && result.data && Array.isArray(result.data)) {
                    allPatients = result.data;
                    if (searchQuery.length > 0 && result.data.length > 0) {
                        showPatientSuggestions(searchQuery);
                    } else if (searchQuery.length > 0) {
                        patientSuggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ada pasien yang ditemukan</div>';
                        patientSuggestions.style.display = 'block';
                    }
                } else {
                    allPatients = [];
                }
            } catch (error) {
                console.error('Error loading patients:', error);
            }
        }

        function showPatientSuggestions(query) {
            if (query.length < 1) {
                patientSuggestions.style.display = 'none';
                return;
            }

            const filtered = allPatients.filter(patient => {
                const regNumber = getPatientValue(patient, ['RegistrationNumber', 'RegistrationNo', 'no_registrasi']).toString().toLowerCase();
                const medicalNo = getPatientValue(patient, ['MedicalNo', 'NoMedis', 'medicalNo', 'MedicalRecordNo', 'NoRM']).toString().toLowerCase();
                const patientName = getPatientValue(patient, ['PatientName', 'FullName', 'Name', 'nama']).toLowerCase();
                const searchTerm = query.toLowerCase();
                
                return regNumber.includes(searchTerm) || medicalNo.includes(searchTerm) || patientName.includes(searchTerm);
            });

            if (filtered.length === 0) {
                patientSuggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ada pasien yang cocok</div>';
                patientSuggestions.style.display = 'block';
                return;
            }

            patientSuggestions.innerHTML = filtered.slice(0, 15).map((patient) => {
                const regNumber = getPatientValue(patient, ['RegistrationNumber', 'RegistrationNo']) || '-';
                const medicalNo = getPatientValue(patient, ['MedicalNo', 'MedicalRecordNo', 'NoRM']) || '-';
                const patientName = getPatientValue(patient, ['PatientName', 'FullName', 'Name', 'nama']) || 'N/A';
                
                return `
                    <button type="button" class="list-group-item list-group-item-action py-2" 
                            onclick="selectPatient(${JSON.stringify(patient).replace(/"/g, '&quot;').replace(/'/g, '&#39;')}); return false;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <strong>${patientName}</strong>
                                <div class="small text-muted">
                                    <div>Reg: ${regNumber} | No. RM: ${medicalNo}</div>
                                </div>
                            </div>
                        </div>
                    </button>
                `;
            }).join('');

            patientSuggestions.style.display = 'block';
        }

        function selectPatient(patient) {
            const patientName = getPatientValue(patient, ['PatientName', 'FullName', 'Name', 'nama']);
            const medicalNo = getPatientValue(patient, ['MedicalNo', 'MedicalRecordNo', 'NoRM']);

            patientSearchInput.value = patientName;
            patientNameInput.value = patientName;
            medicalRecordNoInput.value = medicalNo;
            
            patientSuggestions.style.display = 'none';
        }
        window.selectPatient = selectPatient;

        let debounceTimer;
        patientSearchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                loadPatients(e.target.value);
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (e.target !== patientSearchInput && !patientSuggestions.contains(e.target)) {
                patientSuggestions.style.display = 'none';
            }
        });
    })();
</script>
@endpush
