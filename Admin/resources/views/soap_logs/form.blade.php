@csrf

<div class="mb-3">
    <label for="patient_search" class="form-label">Nomor Registrasi / Nama Pasien <span class="text-danger">*</span></label>
    <div class="position-relative">
        <input 
            type="text" 
            name="patient_search" 
            id="patient_search" 
            class="form-control @error('patient_id') is-invalid @enderror" 
            placeholder="Masukkan nomor registrasi atau nama pasien..." 
            autocomplete="off"
            required>
        <div id="patient_suggestions" class="list-group position-absolute w-100" style="display:none; top: 100%; z-index: 1000; max-height: 300px; overflow-y: auto;">
            <!-- Patient suggestions akan ditampilkan di sini -->
        </div>
    </div>
    @error('patient_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Ketik nomor registrasi atau nama pasien untuk mencari</small>
</div>

<!-- Hidden field untuk menyimpan patient_id yang dipilih -->
<input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id', $log->patient_id ?? '') }}">
<input type="hidden" name="patient_name_manual" id="patient_name_manual" value="{{ old('patient_name_manual', $log->patient_name_manual ?? '') }}">
<input type="hidden" name="patient_registration_no" id="patient_registration_no" value="{{ old('patient_registration_no', $log->patient_registration_no ?? '') }}">
<input type="hidden" name="medical_record_no" id="medical_record_no" value="{{ old('medical_record_no', $log->medical_record_no ?? '') }}">

<!-- Display patient information jika sudah dipilih -->
<div id="patient_info_display" style="display:none;" class="alert alert-info mb-3">
    <div><strong>Nama Pasien:</strong> <span id="display_patient_name"></span></div>
    <div><strong>No. Registrasi:</strong> <span id="display_registration_number"></span></div>
    <div><strong>Patient ID:</strong> <span id="display_patient_id"></span></div>
</div>

<div class="mb-3">
    <label for="visit_date" class="form-label">Visit Date</label>
    <input type="datetime-local" class="form-control" name="visit_date" id="visit_date" value="{{ old('visit_date', isset($log->visit_date) ? $log->visit_date->format('Y-m-d\TH:i') : '') }}">
</div>

<div class="mb-3">
    <label for="diagnosa_input" class="form-label">Diagnosa</label>
    <div class="input-group">
        <input type="text" class="form-control" id="diagnosa_input" placeholder="Klik untuk memilih diagnosa..." readonly>
        <button class="btn btn-outline-secondary" type="button" id="btn_diagnosa_modal" data-bs-toggle="modal" data-bs-target="#diagnosaModal">
            <i class="fas fa-search"></i> Cari
        </button>
    </div>
    <input type="hidden" name="diagnosa_id" id="diagnosa_id" value="{{ old('diagnosa_id', $log->diagnosa_id ?? '') }}">
    <small class="form-text text-muted">Klik tombol Cari untuk memilih diagnosa</small>
</div>

<div class="mb-3">
    <label class="form-label">Subjective</label>
    <textarea class="form-control" name="subjective" rows="3">{{ old('subjective', $log->subjective ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Objective</label>
    <textarea class="form-control" name="objective" rows="3">{{ old('objective', $log->objective ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Assessment</label>
    <textarea class="form-control" name="assessment" rows="3">{{ old('assessment', $log->assessment ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Plan</label>
    <textarea class="form-control" name="plan" rows="3">{{ old('plan', $log->plan ?? '') }}</textarea>
</div>
<div class="form-group mb-3">
    <label for="nama_dpjp">Nama DPJP <span class="text-danger">*</span></label>
    <div class="position-relative">
        <input 
            type="text" 
            name="nama_dpjp" 
            id="nama_dpjp" 
            class="form-control @error('nama_dpjp') is-invalid @enderror" 
            placeholder="Ketik untuk mencari dokter..." 
            autocomplete="off"
            required>
        <div id="dokter_suggestions" class="list-group position-absolute w-100" style="display:none; top: 100%; z-index: 1000; max-height: 300px; overflow-y: auto;">
            <!-- Suggestions akan ditampilkan di sini -->
        </div>
    </div>
    @error('nama_dpjp')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">Ketik nama dokter untuk mencari</small>
</div>

<!-- Modal Diagnosa -->
<div class="modal fade" id="diagnosaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Diagnosa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="diagnosa_search" placeholder="Cari diagnosa...">
                </div>
                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Diagnosa Code</th>
                                <th>Diagnosa Name</th>
                            </tr>
                        </thead>
                        <tbody id="diagnosa_table_body">
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination pagination-sm justify-content-center" id="diagnosa_pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script>
    (function() {
        function initSoapFormValidation() {
            const diagnosaInput = document.getElementById('diagnosa_input');
            const diagnosaIdInput = document.getElementById('diagnosa_id');
            const diagnosaSearchInput = document.getElementById('diagnosa_search');
            const diagnosaTableBody = document.getElementById('diagnosa_table_body');
            const diagnosaModal = document.getElementById('diagnosaModal');
            const diagnosaModalObj = new bootstrap.Modal(diagnosaModal);
            
            let allDiagnoses = [];
            let currentPage = 1;
            const itemsPerPage = 20;
            const apiBaseUrl = (document.querySelector('base') ? document.querySelector('base').href.replace(/\/$/, '') : window.location.origin) + '/api';

        // Fetch all diagnoses when modal is shown
        diagnosaModal.addEventListener('show.bs.modal', async function() {
            if (allDiagnoses.length === 0) {
                await fetchAllDiagnoses();
            }
            displayTable(allDiagnoses, 1);
        });

        // Fetch all diagnoses from API
        async function fetchAllDiagnoses() {
            try {
                const response = await fetch(apiBaseUrl + '/diagnosa', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Diagnosa API Response:', data);
                
                if (data.success && data.data) {
                    allDiagnoses = data.data;
                    diagnosaTableBody.innerHTML = '';

                    // Load saved diagnosa if in edit mode
                    const savedDiagnosaId = document.getElementById('diagnosa_id').value;
                    if (savedDiagnosaId) {
                        const savedDiagnosa = allDiagnoses.find(d => 
                            (d.diagnose_id || d.DiagnoseID || d.kd_diagnosa || '') === savedDiagnosaId
                        );
                        if (savedDiagnosa) {
                            selectDiagnosa(savedDiagnosa);
                        }
                    }

                    displayTable(allDiagnoses);
                } else {
                    diagnosaTableBody.innerHTML = '<tr><td colspan="2" class="text-danger">Gagal memuat data diagnosa</td></tr>';
                }
            } catch (error) {
                console.error('Error:', error);
                diagnosaTableBody.innerHTML = '<tr><td colspan="2" class="text-danger">Error: ' + error.message + '</td></tr>';
            }
        }

        // Display table with pagination
        function displayTable(data, page = 1) {
            currentPage = page;
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = data.slice(start, end);

            diagnosaTableBody.innerHTML = '';
            
            if (paginatedData.length === 0) {
                diagnosaTableBody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data</td></tr>';
                displayPagination(data.length);
                return;
            }

            paginatedData.forEach(item => {
                const row = document.createElement('tr');
                row.style.cursor = 'pointer';
                // Support multiple field name formats
                const diagnoseID = item.diagnose_id || item.DiagnoseID || item.kd_diagnosa || '';
                const diagnoseName = item.diagnose_name || item.DiagnoseName || item.nm_diagnosa || '';
                row.innerHTML = `
                    <td>${diagnoseID}</td>
                    <td>${diagnoseName}</td>
                `;
                row.onclick = () => selectDiagnosa(item);
                diagnosaTableBody.appendChild(row);
            });

            displayPagination(data.length);
        }

        // Display pagination
        function displayPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pagination = document.getElementById('diagnosa_pagination');
            pagination.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = 'page-item ' + (currentPage === 1 ? 'disabled' : '');
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault();">&laquo;</a>`;
            if (currentPage > 1) {
                prevLi.onclick = () => displayTable(filterDiagnoses(diagnosaSearchInput.value), currentPage - 1);
            }
            pagination.appendChild(prevLi);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    const li = document.createElement('li');
                    li.className = 'page-item ' + (i === currentPage ? 'active' : '');
                    li.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault();">${i}</a>`;
                    if (i !== currentPage) {
                        li.onclick = () => displayTable(filterDiagnoses(diagnosaSearchInput.value), i);
                    }
                    pagination.appendChild(li);
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    const li = document.createElement('li');
                    li.className = 'page-item disabled';
                    li.innerHTML = '<span class="page-link">...</span>';
                    pagination.appendChild(li);
                }
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = 'page-item ' + (currentPage === totalPages ? 'disabled' : '');
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault();">&raquo;</a>`;
            if (currentPage < totalPages) {
                nextLi.onclick = () => displayTable(filterDiagnoses(diagnosaSearchInput.value), currentPage + 1);
            }
            pagination.appendChild(nextLi);
        }

        // Filter diagnoses by search term
        function filterDiagnoses(query) {
            if (!query) return allDiagnoses;

            return allDiagnoses.filter(item => {
                const name = (item.diagnose_name || item.DiagnoseName || '').toLowerCase();
                const id = (item.diagnose_id || item.DiagnoseID || '').toLowerCase();
                return name.includes(query.toLowerCase()) || id.includes(query.toLowerCase());
            });
        }

        // Search input event listener
        diagnosaSearchInput.addEventListener('input', function() {
            const filtered = filterDiagnoses(this.value);
            displayTable(filtered, 1);
        });

        // Select diagnosa
        function selectDiagnosa(item) {
            const diagnoseID = item.diagnose_id || item.DiagnoseID || item.kd_diagnosa || '';
            const diagnoseName = item.diagnose_name || item.DiagnoseName || item.nm_diagnosa || '';

            // isi input diagnosa
            diagnosaInput.value = diagnoseID + " - " + diagnoseName;

            // isi hidden field untuk disimpan ke database
            diagnosaIdInput.value = diagnoseID;

            // tutup modal
            diagnosaModalObj.hide();
        }

        // Set visit_date to current date/time if empty
        const visitDateInput = document.getElementById('visit_date');
        if (visitDateInput && !visitDateInput.value) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            visitDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        // Fetch Dokter dari API - Autocomplete Search
        const dokterInput = document.getElementById('nama_dpjp');
        const dokterSuggestions = document.getElementById('dokter_suggestions');
        let allDokters = [];
        const oldValue = "{{ old('nama_dpjp', $log->nama_dpjp ?? '') }}";

        // Load dokter dari API
        async function loadDokters() {
            try {
                const response = await fetch(apiBaseUrl + '/dokter', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Dokter API Full Response:', JSON.stringify(result, null, 2));
                console.log('Result success:', result.success);
                console.log('Result data:', result.data);
                console.log('Result data type:', typeof result.data);
                console.log('Result data is array:', Array.isArray(result.data));

                if (result.success && result.data && Array.isArray(result.data)) {
                    console.log('Raw data count:', result.data.length);
                    
                    // Tampilkan 3 dokter pertama untuk debugging
                    if (result.data.length > 0) {
                        console.log('First dokter sample:', JSON.stringify(result.data[0], null, 2));
                    }

                    // Filter dokter yang punya nama
                    allDokters = result.data.filter(dokter => {
                       const nama = dokter.FullName || dokter.UserFullName || dokter.UserName || dokter.nama || '';
                        return nama.length > 0;
                    });

                    console.log('Filtered dokter count:', allDokters.length);

                    // Jika ada nilai lama (edit mode), set ke input
                    if (oldValue) {
                        dokterInput.value = oldValue;
                    }
                } else {
                    console.error('API returned error or invalid data:', {
                        success: result.success,
                        has_data: !!result.data,
                        is_array: Array.isArray(result.data),
                        message: result.message
                    });
                    showNotification('Gagal memuat data dokter', 'warning');
                }
            } catch (error) {
                console.error('Error loading dokter:', error);
                showNotification('Error memuat data dokter: ' + error.message, 'danger');
            }
        }

        // Filter dan tampilkan suggestions saat user mengetik
        function showSuggestions(query) {
            if (query.length < 1) {
                dokterSuggestions.style.display = 'none';
                return;
            }

            const filtered = allDokters.filter(dokter => {
                const nama = dokter.FullName || dokter.UserFullName || dokter.UserName || dokter.nama || '';
                return nama.toLowerCase().includes(query.toLowerCase());
            });

            if (filtered.length === 0) {
                dokterSuggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ada dokter yang cocok</div>';
                dokterSuggestions.style.display = 'block';
                return;
            }

            dokterSuggestions.innerHTML = filtered.slice(0, 10).map((dokter) => {
                const nama = dokter.FullName || dokter.UserFullName || dokter.UserName || dokter.nama || '';
                const kode = dokter.ParamedicCode || '';
                return `
                    <button type="button" class="list-group-item list-group-item-action py-2" onclick="selectDokter('${nama.replace(/'/g, "\\'")}'); return false;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${nama}</strong>
                                ${kode ? `<div class="small text-muted">${kode}</div>` : ''}
                            </div>
                        </div>
                    </button>
                `;
            }).join('');

            dokterSuggestions.style.display = 'block';
        }

        // Pilih dokter dari suggestions
        function selectDokter(nama) {
            dokterInput.value = nama; 
            dokterSuggestions.style.display = 'none';
        }
        window.selectDokter = selectDokter;
        // Event listener untuk input
        dokterInput.addEventListener('input', (e) => {
            showSuggestions(e.target.value);
        });

        // Sembunyikan suggestions saat klik di luar
        document.addEventListener('click', (e) => {
            if (e.target !== dokterInput) {
                dokterSuggestions.style.display = 'none';
            }
        });

        // Tampilkan suggestions saat focus
        dokterInput.addEventListener('focus', () => {
            if (dokterInput.value.length > 0) {
                showSuggestions(dokterInput.value);
            }
        });

        // Helper function untuk notifikasi
        function showNotification(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.insertBefore(alertDiv, document.body.firstChild);
            setTimeout(() => alertDiv.remove(), 5000);
        }

        // ============ PATIENT SEARCH AUTOCOMPLETE ============
        const patientSearchInput = document.getElementById('patient_search');
        const patientSuggestions = document.getElementById('patient_suggestions');
        const patientIdInput = document.getElementById('patient_id');
        const patientInfoDisplay = document.getElementById('patient_info_display');
        let allPatients = [];
        let selectedPatient = null;

        function normalizeKey(key) {
            return key.replace(/([A-Z])/g, '_$1').toLowerCase().replace(/^_/, '');
        }

        function getPatientValue(patient, keys) {
            if (!patient || typeof patient !== 'object') return '';

            const objectsToSearch = [patient];
            if (patient.Patient && typeof patient.Patient === 'object') {
                objectsToSearch.push(patient.Patient);
            }
            if (patient.patient && typeof patient.patient === 'object') {
                objectsToSearch.push(patient.patient);
            }
            // Cek PatientInfo field (bisa berisi nested patient data)
            if (patient.PatientInfo) {
                try {
                    const patientInfo = typeof patient.PatientInfo === 'string' 
                        ? JSON.parse(patient.PatientInfo)
                        : patient.PatientInfo;
                    if (typeof patientInfo === 'object') {
                        objectsToSearch.push(patientInfo);
                    }
                } catch (e) {
                    // If parse fails, continue with other objects
                }
            }

            // Tahap 1: Exact matches di semua object
            for (const key of keys) {
                for (const target of objectsToSearch) {
                    if (target[key] !== undefined && target[key] !== null && target[key] !== '') {
                        return target[key];
                    }
                }
            }

            // Tahap 2: Exact normalized matches di semua object
            for (const key of keys) {
                const normalizedKeyName = normalizeKey(key);
                const lowerKeyName = key.toLowerCase();
                
                for (const target of objectsToSearch) {
                    const normalizedTargetKeys = Object.keys(target).reduce((map, prop) => {
                        map[normalizeKey(prop)] = target[prop];
                        map[prop.toLowerCase()] = target[prop];
                        return map;
                    }, {});

                    if (normalizedTargetKeys[normalizedKeyName] !== undefined && normalizedTargetKeys[normalizedKeyName] !== null && normalizedTargetKeys[normalizedKeyName] !== '') {
                        return normalizedTargetKeys[normalizedKeyName];
                    }

                    if (normalizedTargetKeys[lowerKeyName] !== undefined && normalizedTargetKeys[lowerKeyName] !== null && normalizedTargetKeys[lowerKeyName] !== '') {
                        return normalizedTargetKeys[lowerKeyName];
                    }
                }
            }

            // Tahap 3: Fuzzy matches di semua object (sebagai fallback terakhir)
            for (const key of keys) {
                const normalizedKeyName = normalizeKey(key);
                for (const target of objectsToSearch) {
                    const normalizedTargetKeys = Object.keys(target).reduce((map, prop) => {
                        map[normalizeKey(prop)] = target[prop];
                        return map;
                    }, {});

                    for (const prop of Object.keys(normalizedTargetKeys)) {
                        if (prop.includes(normalizedKeyName) || normalizedKeyName.includes(prop)) {
                            const value = normalizedTargetKeys[prop];
                            if (value !== undefined && value !== null && value !== '') {
                                return value;
                            }
                        }
                    }
                }
            }

            return '';
        }

        // Load patients dari API saat halaman dimuat
        async function loadPatients(searchQuery = '') {
            try {
                // Gunakan endpoint pasien umum untuk semua pencarian, termasuk nomor registrasi OPR.
                let url = `${apiBaseUrl}/pasien`;
                if (searchQuery.length > 0) {
                    url = `${apiBaseUrl}/pasien?registrationNo=${encodeURIComponent(searchQuery)}&periodeRegistrationDate=${new Date().toISOString().split('T')[0]}`;
                }

                console.log('Fetching from:', url);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                console.log('Response status:', response.status);
                const result = await response.json();
                console.log('Patient API Response:', JSON.stringify(result, null, 2));

                if (result.success && result.data && Array.isArray(result.data)) {
                    console.log('Patient count:', result.data.length);
                    allPatients = result.data;

                    if (searchQuery.length > 0 && result.data.length > 0) {
                        showPatientSuggestions(searchQuery);
                    } else if (searchQuery.length > 0) {
                        patientSuggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ada pasien dengan nomor registrasi: <strong>' + searchQuery + '</strong></div>';
                        patientSuggestions.style.display = 'block';
                    }
                } else {
                    console.warn('API response format unexpected:', result);
                    allPatients = [];
                    if (result.message && searchQuery.length > 0) {
                        patientSuggestions.innerHTML = '<div class="list-group-item text-muted">' + result.message + '</div>';
                        patientSuggestions.style.display = 'block';
                    }
                }
            } catch (error) {
                console.error('Error loading patients:', error);
                allPatients = [];
                if (patientSearchInput.value.length > 0) {
                    showNotification('Error memuat data pasien: ' + error.message, 'danger');
                }
            }
        }

        // Tampilkan suggestions saat user mengetik
        function showPatientSuggestions(query) {
            if (query.length < 1) {
                patientSuggestions.style.display = 'none';
                return;
            }

            const filtered = allPatients.filter(patient => {
                const regNumber = getPatientValue(patient, ['RegistrationNumber', 'RegistrationNo', 'RegNo', 'no_registrasi', 'registrationNo', 'registration_no']).toString().toLowerCase();
                const medicalNo = getPatientValue(patient, ['MedicalNo', 'MedicalNumber', 'NoMedis', 'medicalNo', 'medical_no', 'no_medical', 'MedicalRecordNumber', 'MedicalRecordNo', 'NoMR', 'NoRM']).toString().toLowerCase();
                const patientName = getPatientValue(patient, ['PatientName', 'PatientFullName', 'FullName', 'Name', 'nama', 'NamePatient', 'patientName', 'full_name', 'name']).toLowerCase();
                const searchTerm = query.toLowerCase();
                
                return regNumber.includes(searchTerm) || 
                       medicalNo.includes(searchTerm) || 
                       patientName.includes(searchTerm);
            });

            if (filtered.length === 0) {
                patientSuggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ada pasien yang cocok</div>';
                patientSuggestions.style.display = 'block';
                return;
            }

            patientSuggestions.innerHTML = filtered.slice(0, 15).map((patient) => {
                const regNumber = getPatientValue(patient, ['RegistrationNumber', 'RegistrationNo', 'RegNo', 'no_registrasi', 'registrationNo', 'registration_no']) || '-';
                const medicalNo = getPatientValue(patient, ['MedicalNo', 'MedicalNumber', 'NoMedis', 'medicalNo', 'medical_no', 'no_medical', 'MedicalRecordNumber', 'MedicalRecordNo', 'NoMR', 'NoRM']) || '-';
                const patientName = getPatientValue(patient, ['PatientName', 'PatientFullName', 'FullName', 'Name', 'nama', 'NamePatient', 'patientName', 'full_name', 'name']) || 'N/A';
                const patientId = getPatientValue(patient, ['PatientID', 'ID', 'patient_id', 'MedicalNo', 'registrationNo', 'RegistrationNumber']) || regNumber;
                const dob = getPatientValue(patient, ['DateOfBirth', 'DOB', 'TanggalLahir', 'dateOfBirth', 'date_of_birth', 'tanggal_lahir']) || '';
                const penjamin = getPatientValue(patient, ['CustomerName', 'Customer', 'PayerName', 'Payer', 'Penjamin', 'Guarantor', 'Asuransi', 'Company', 'CompanyName', 'GrupCustomer', 'CustomerGroup', 'PaymentName', 'PaymentMethod']) || '';
                
                return `
                    <button type="button" class="list-group-item list-group-item-action py-2" 
                            onclick="selectPatient(${JSON.stringify(patient).replace(/"/g, '&quot;').replace(/'/g, '&#39;')}); return false;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <strong>${patientName}</strong>
                                <div class="small text-muted">
                                    <div>Reg: ${regNumber}</div>
                                    <div>No. Rekam Medis: ${medicalNo}</div>
                                    ${dob ? '<div>DOB: ' + dob + '</div>' : ''}
                                    ${penjamin ? '<div>Penjamin: ' + penjamin + '</div>' : ''}
                                </div>
                            </div>
                        </div>
                    </button>
                `;
            }).join('');

            patientSuggestions.style.display = 'block';
        }

        // Pilih pasien dari suggestions
        function selectPatient(patient) {
            console.log('=== FULL PATIENT DATA FROM API ===', JSON.stringify(patient, null, 2));
            console.log('=== PATIENT KEYS ===', Object.keys(patient));
            
            // Debug: Cek PatientInfo field (likely contains patient name)
            if (patient.PatientInfo) {
                console.log('=== PatientInfo (RAW) ===', patient.PatientInfo);
                console.log('=== PatientInfo (TYPE) ===', typeof patient.PatientInfo);
                try {
                    const patientInfoParsed = typeof patient.PatientInfo === 'string' 
                        ? JSON.parse(patient.PatientInfo)
                        : patient.PatientInfo;
                    console.log('=== PatientInfo (PARSED) ===', JSON.stringify(patientInfoParsed, null, 2));
                    console.log('=== PatientInfo KEYS ===', Object.keys(patientInfoParsed));
                } catch (e) {
                    console.log('=== PatientInfo PARSE ERROR ===', e.message);
                }
            }
            
            // Debug: Tampilkan semua field yang berisi "name" atau "Name"
            const nameFields = {};
            for (const key in patient) {
                if (key.toLowerCase().includes('name')) {
                    nameFields[key] = patient[key];
                }
            }
            console.log('=== NAME-RELATED FIELDS ===', nameFields);
            
            const patientName = getPatientValue(patient, ['PatientName', 'PatientFullName', 'FullName', 'Name', 'nama', 'NamePatient', 'patientName', 'full_name', 'name']);
            const regNumber = getPatientValue(patient, ['RegistrationNumber', 'RegistrationNo', 'RegNo', 'no_registrasi', 'registrationNo', 'registration_no']);
            const medicalNo = getPatientValue(patient, ['MedicalNo', 'MedicalNumber', 'NoMedis', 'medicalNo', 'medical_no', 'no_medical', 'MedicalRecordNumber', 'MedicalRecordNo', 'NoMR', 'NoRM']);
            const patientId = getPatientValue(patient, ['PatientID', 'ID', 'patient_id', 'MedicalNo', 'registrationNo', 'RegistrationNumber']) || regNumber;

            // Set input value
            patientSearchInput.value = patientName ? `${patientName} (${regNumber})` : regNumber;
            
            // Set hidden patient_id field
            patientIdInput.value = patientId;
            
            // Set hidden patient_name_manual field
            const patientNameManualInput = document.getElementById('patient_name_manual');
            if (patientNameManualInput) {
                patientNameManualInput.value = patientName;
            }
            
            // Set hidden patient_registration_no field
            const patientRegNoInput = document.getElementById('patient_registration_no');
            if (patientRegNoInput) {
                patientRegNoInput.value = regNumber;
            }
            
            // Set hidden medical_record_no field
            const medicalRecordNoInput = document.getElementById('medical_record_no');
            if (medicalRecordNoInput) {
                medicalRecordNoInput.value = medicalNo;
            }
            
            // Simpan selected patient
            selectedPatient = patient;
            
            // Display patient info
            displayPatientInfo(patient);
            
            // Sembunyikan suggestions
            patientSuggestions.style.display = 'none';
            
            console.log('Selected patient:', {
                name: patientName,
                regNumber: regNumber,
                medicalNo: medicalNo,
                patientId: patientId
            });
        }
        window.selectPatient = selectPatient;

        // Display patient information
        function displayPatientInfo(patient) {
            console.log('=== DISPLAY PATIENT INFO DEBUG ===');
            console.log('Full patient object:', JSON.stringify(patient, null, 2));
            
            const patientName = getPatientValue(patient, ['PatientName', 'PatientFullName', 'FullName', 'Name', 'nama', 'NamePatient', 'patientName', 'full_name', 'name']) || 'N/A';
            const regNumber = getPatientValue(patient, ['RegistrationNumber', 'RegistrationNo', 'RegNo', 'no_registrasi', 'registrationNo', 'registration_no']) || '-';
            const medicalNo = getPatientValue(patient, ['MedicalNo', 'MedicalNumber', 'NoMedis', 'medicalNo', 'medical_no', 'no_medical', 'MedicalRecordNumber', 'MedicalRecordNo', 'NoMR', 'NoRM']) || '-';
            const patientId = getPatientValue(patient, ['PatientID', 'ID', 'patient_id', 'MedicalNo', 'registrationNo', 'RegistrationNumber']) || regNumber;
            const penjamin = getPatientValue(patient, ['CustomerName', 'Customer', 'PayerName', 'Payer', 'Penjamin', 'Guarantor', 'Asuransi', 'Company', 'CompanyName', 'GrupCustomer', 'CustomerGroup', 'PaymentName', 'PaymentMethod']) || '';

            console.log('Extracted values - Name:', patientName, 'RegNo:', regNumber, 'MedicalNo:', medicalNo);

            const nameElement = document.getElementById('display_patient_name');
            const regNumberElement = document.getElementById('display_registration_number');
            const patientIdElement = document.getElementById('display_patient_id');
            const infoElement = document.getElementById('patient_info_display');

            if (nameElement) {
                nameElement.textContent = patientName;
            }
            if (regNumberElement) {
                regNumberElement.textContent = regNumber;
            }
            if (patientIdElement) {
                patientIdElement.textContent = patientId;
            }
            if (!infoElement) {
                return;
            }

            // Update display untuk menampilkan medical number dan patient ID
            let infoHtml = `
                <div><strong>Nama Pasien:</strong> ${patientName}</div>
                <div><strong>No. Registrasi:</strong> ${regNumber}</div>
            `;
            
            if (medicalNo && medicalNo !== '-') {
                infoHtml += `<div><strong>No. Rekam Medis:</strong> ${medicalNo}</div>`;
            }
            
            infoHtml += `<div><strong>Patient ID:</strong> ${patientId}</div>`;
            
            if (penjamin) {
                infoHtml += `<div><strong>Penjamin:</strong> ${penjamin}</div>`;
            }
            
            // Tampilkan info tambahan jika ada
            if (patient.DateOfBirth || patient.DOB || patient.TanggalLahir) {
                const dob = patient.DateOfBirth || patient.DOB || patient.TanggalLahir;
                infoHtml += `<div><strong>Tanggal Lahir:</strong> ${dob}</div>`;
            }
            
            if (patient.Gender || patient.Jenis_Kelamin) {
                const gender = patient.Gender || patient.Jenis_Kelamin;
                infoHtml += `<div><strong>Jenis Kelamin:</strong> ${gender}</div>`;
            }
            
            if (patient.Address || patient.Alamat) {
                const address = patient.Address || patient.Alamat;
                infoHtml += `<div><strong>Alamat:</strong> ${address}</div>`;
            }
            
            infoElement.innerHTML = infoHtml;
            infoElement.style.display = 'block';
        }

        // Event listener untuk input patient search
        patientSearchInput.addEventListener('input', (e) => {
            loadPatients(e.target.value);
        });

        // Sembunyikan suggestions saat klik di luar
        document.addEventListener('click', (e) => {
            if (e.target !== patientSearchInput && !patientSuggestions.contains(e.target)) {
                patientSuggestions.style.display = 'none';
            }
        });

        // Tampilkan suggestions saat focus
        patientSearchInput.addEventListener('focus', () => {
            if (patientSearchInput.value.length > 0) {
                showPatientSuggestions(patientSearchInput.value);
            } else {
                // Load all patients saat focus tanpa query
                loadPatients();
            }
        });

        // ============ END PATIENT SEARCH AUTOCOMPLETE ============

        // Load dokter saat halaman dimuat
        loadDokters();

        // Validasi form SOAP sebelum submit dan tampilkan SweetAlert jika ada field yang belum lengkap
        const soapForm = document.getElementById('soap_log_form') || document.querySelector('form');
        if (soapForm) {
            soapForm.setAttribute('novalidate', 'novalidate');
            console.log('[SOAP] validator attached to form', soapForm.id || soapForm.action);
            soapForm.addEventListener('submit', function(event) {
                console.log('[SOAP] submit handler fired');
                const patientId = document.getElementById('patient_id')?.value.trim() || document.getElementById('patient_search')?.value.trim() || '';
                const visitDate = document.getElementById('visit_date')?.value.trim() || '';
                const namaDpjp = document.getElementById('nama_dpjp')?.value.trim() || '';
                const subjective = document.querySelector('textarea[name="subjective"]')?.value.trim() || '';
                const objective = document.querySelector('textarea[name="objective"]')?.value.trim() || '';
                const assessment = document.querySelector('textarea[name="assessment"]')?.value.trim() || '';
                const plan = document.querySelector('textarea[name="plan"]')?.value.trim() || '';

                const missingFields = [];
                if (!patientId) {
                    missingFields.push('Pasien');
                }
                if (!visitDate) {
                    missingFields.push('Tanggal Visit');
                }
                if (!namaDpjp) {
                    missingFields.push('Nama DPJP');
                }
                if (!subjective) {
                    missingFields.push('Subjective');
                }
                if (!objective) {
                    missingFields.push('Objective');
                }
                if (!assessment) {
                    missingFields.push('Assessment');
                }
                if (!plan) {
                    missingFields.push('Plan');
                }

                if (missingFields.length > 0) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Form SOAP belum lengkap',
                        html: `<p>Silakan lengkapi field berikut sebelum menyimpan:</p><ul style="text-align:left;margin:0;padding-left:20px;">${missingFields.map(field => `<li>${field}</li>`).join('')}</ul>`,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        } else {
            console.warn('[SOAP] form validator tidak ditemukan');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSoapFormValidation);
    } else {
        initSoapFormValidation();
    }
})();
</script>
@endpush