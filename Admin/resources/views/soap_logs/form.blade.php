@csrf

<div class="mb-3">
    <label for="patient_id" class="form-label">Patient ID</label>
    <input type="number" class="form-control" name="patient_id" value="{{ old('patient_id', $log->patient_id ?? '') }}" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        const diagnosaInput = document.getElementById('diagnosa_input');
        const diagnosaIdInput = document.getElementById('diagnosa_id');
        const diagnosaSearchInput = document.getElementById('diagnosa_search');
        const diagnosaTableBody = document.getElementById('diagnosa_table_body');
        const diagnosaModal = document.getElementById('diagnosaModal');
        const diagnosaModalObj = new bootstrap.Modal(diagnosaModal);
        
        let allDiagnoses = [];
        let currentPage = 1;
        const itemsPerPage = 20;

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
                const response = await fetch('/api/diagnosa', {
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
            visitDateInput.value = now.toISOString().slice(0, 16);
        }

        // Fetch Dokter dari API - Autocomplete Search
        const dokterInput = document.getElementById('nama_dpjp');
        const dokterSuggestions = document.getElementById('dokter_suggestions');
        let allDokters = [];
        const oldValue = "{{ old('nama_dpjp', $log->nama_dpjp ?? '') }}";

        // Load dokter dari API
        async function loadDokters() {
            try {
                const response = await fetch('/api/dokter', {
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

        // Load dokter saat halaman dimuat
        loadDokters();
    });
</script>
@endpush