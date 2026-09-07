@csrf

<!-- DATA PASIEN & KUNJUNGAN -->
<div class="card border border-light bg-light shadow-none mb-4">
    <div class="card-body">
        <h5 class="font-size-15 mb-3 text-primary"><i class="ri-user-heart-line me-1"></i> Data Pasien & Kunjungan</h5>
        
        <div class="mb-4">
            <label for="patient_search" class="form-label fw-bold">Pencarian Pasien (No. Reg / Nama) <span class="text-danger">*</span></label>
            <div class="position-relative">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="ri-search-line text-muted"></i></span>
                    <input 
                        type="text" 
                        name="patient_search" 
                        id="patient_search" 
                        class="form-control form-control-lg @error('patient_id') is-invalid @enderror" 
                        placeholder="Ketik nomor registrasi atau nama pasien..." 
                        autocomplete="off"
                        required>
                </div>
                <div id="patient_suggestions" class="list-group position-absolute w-100 shadow-lg border-0" style="display:none; top: 100%; z-index: 1000; max-height: 300px; overflow-y: auto; border-radius: 0 0 0.5rem 0.5rem;">
                    <!-- Patient suggestions akan ditampilkan di sini -->
                </div>
            </div>
            @error('patient_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted mt-2"><i class="ri-information-line me-1"></i>Data ditarik otomatis dan terintegrasi secara *real-time* dari server Medinfras.</small>
        </div>

        <!-- Hidden field untuk menyimpan data pasien -->
        <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id', $log->patient_id ?? '') }}">
        <input type="hidden" name="patient_name_manual" id="patient_name_manual" value="{{ old('patient_name_manual', $log->patient_name_manual ?? '') }}">
        <input type="hidden" name="patient_registration_no" id="patient_registration_no" value="{{ old('patient_registration_no', $log->patient_registration_no ?? '') }}">
        <input type="hidden" name="medical_record_no" id="medical_record_no" value="{{ old('medical_record_no', $log->medical_record_no ?? '') }}">

        <!-- Display patient information jika sudah dipilih -->
        <div id="patient_info_display" style="display:none;" class="alert alert-info border-0 shadow-sm mb-4">
            <div><strong>Nama Pasien:</strong> <span id="display_patient_name"></span></div>
            <div><strong>No. Registrasi:</strong> <span id="display_registration_number"></span></div>
            <div><strong>Patient ID:</strong> <span id="display_patient_id"></span></div>
        </div>

        <div class="mb-2">
            <label for="visit_date" class="form-label fw-bold text-muted"><i class="ri-calendar-event-line text-primary me-1"></i> Tanggal Visit</label>
            <input type="datetime-local" class="form-control form-control-lg bg-white" name="visit_date" id="visit_date" value="{{ old('visit_date', isset($log->visit_date) ? $log->visit_date->format('Y-m-d\TH:i') : '') }}">
        </div>
    </div>
</div>

<!-- PEMERIKSAAN MEDIS (SOAP) -->
<div class="card border border-light bg-light shadow-none mb-4">
    <div class="card-body">
        <h5 class="font-size-15 mb-3 text-success"><i class="ri-heart-pulse-line me-1"></i> Pemeriksaan Medis (SOAP)</h5>

        <div class="mb-4">
            <label for="diagnosa_input" class="form-label fw-bold"><i class="ri-stethoscope-line text-success me-1"></i> Diagnosa Utama</label>
            <div class="input-group input-group-lg">
                <input type="text" class="form-control bg-white" id="diagnosa_input" placeholder="Klik tombol cari untuk memilih diagnosa..." readonly>
                <button class="btn btn-success waves-effect waves-light" type="button" id="btn_diagnosa_modal" data-bs-toggle="modal" data-bs-target="#diagnosaModal">
                    <i class="ri-search-line me-1"></i> Cari Diagnosa
                </button>
            </div>
            <input type="hidden" name="diagnosa_id" id="diagnosa_id" value="{{ old('diagnosa_id', $log->diagnosa_id ?? '') }}">
            <small class="form-text text-muted mt-2">Diagnosa sesuai standar ICD-10 Medinfras</small>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label class="form-label fw-bold text-muted"><i class="ri-heart-pulse-fill text-danger me-1"></i> Tanda-Tanda Vital (TTV)</label>
                <div class="row g-2">
                    <div class="col-md-2 col-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light" title="Tekanan Darah">TD</span>
                            <input type="text" class="form-control" name="ttv_td" value="{{ old('ttv_td', $log->ttv_td ?? '') }}" placeholder="120/80">
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light" title="Heart Rate">HR</span>
                            <input type="text" class="form-control" name="ttv_hr" value="{{ old('ttv_hr', $log->ttv_hr ?? '') }}" placeholder="80">
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light" title="Respiratory Rate">RR</span>
                            <input type="text" class="form-control" name="ttv_rr" value="{{ old('ttv_rr', $log->ttv_rr ?? '') }}" placeholder="20">
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light" title="Suhu Badan">Suhu</span>
                            <input type="text" class="form-control" name="ttv_temp" value="{{ old('ttv_temp', $log->ttv_temp ?? '') }}" placeholder="36.5">
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light" title="Saturasi Oksigen">SpO2</span>
                            <input type="text" class="form-control" name="ttv_spo2" value="{{ old('ttv_spo2', $log->ttv_spo2 ?? '') }}" placeholder="99%">
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light" title="Skala Nyeri (0-10)">VAS</span>
                            <input type="text" class="form-control" name="ttv_vas" value="{{ old('ttv_vas', $log->ttv_vas ?? '') }}" placeholder="0-10">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-muted">S - Subjective</label>
                <textarea class="form-control" name="subjective" rows="4" placeholder="Keluhan utama pasien yang dirasakan...">{{ old('subjective', $log->subjective ?? '') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-muted">O - Objective</label>
                <textarea class="form-control" name="objective" rows="4" placeholder="Hasil pemeriksaan fisik, lab, dan penunjang...">{{ old('objective', $log->objective ?? '') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-muted">A - Assessment</label>
                <textarea class="form-control" name="assessment" rows="4" placeholder="Penilaian medis / Kesimpulan diagnosis...">{{ old('assessment', $log->assessment ?? '') }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-muted">P - Plan</label>
                <textarea class="form-control" name="plan" rows="4" placeholder="Rencana pengobatan, terapi, atau tindakan...">{{ old('plan', $log->plan ?? '') }}</textarea>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold text-muted">Foto Hasil Visite (Opsional)</label>
                <input type="file" class="form-control" name="foto_visite[]" accept="image/*" multiple>
                @if(isset($log) && !empty($log->foto_visite))
                    <div class="mt-2">
                        <p class="mb-1 text-muted">Foto saat ini:</p>
                        <div class="d-flex flex-wrap gap-2">
                        @if(is_array($log->foto_visite))
                            @foreach($log->foto_visite as $foto)
                                <img src="{{ asset('storage/' . $foto) }}" alt="Foto Visite" class="img-thumbnail" style="max-height: 200px;">
                            @endforeach
                        @else
                            <img src="{{ asset('storage/' . $log->foto_visite) }}" alt="Foto Visite" class="img-thumbnail" style="max-height: 200px;">
                        @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold text-muted">Body Diagram (Opsional)</label>
                <div>
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#bodyDiagramModal">
                        <i class="ri-body-scan-line align-middle me-1"></i> Buka Body Diagram
                    </button>
                    <input type="hidden" name="body_diagram_base64" id="body_diagram_base64">
                    
                    <div id="body_diagram_preview_container" class="mt-2" style="{{ isset($log) && $log->body_diagram ? '' : 'display:none;' }}">
                        <p class="mb-1 text-muted">Diagram tersimpan:</p>
                        <img id="body_diagram_preview" src="{{ isset($log) && $log->body_diagram ? asset('storage/' . $log->body_diagram) : '' }}" alt="Body Diagram" class="img-thumbnail" style="max-height: 250px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA DPJP -->
<div class="card border border-light bg-light shadow-none mb-4">
    <div class="card-body">
        <h5 class="font-size-15 mb-3 text-info"><i class="ri-nurse-line me-1"></i> Data DPJP (Dokter Penanggung Jawab)</h5>

        <div class="form-group mb-2">
            <label for="nama_dpjp" class="form-label fw-bold text-muted">Pilih DPJP <span class="text-danger">*</span></label>
            <div class="position-relative">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="ri-user-search-line text-muted"></i></span>
                    <input 
                        type="text" 
                        name="nama_dpjp" 
                        id="nama_dpjp" 
                        class="form-control form-control-lg @error('nama_dpjp') is-invalid @enderror" 
                        placeholder="Ketik nama dokter untuk mencari..." 
                        autocomplete="off"
                        required>
                </div>
                <div id="dokter_suggestions" class="list-group position-absolute w-100 shadow-lg border-0" style="display:none; top: 100%; z-index: 1000; max-height: 300px; overflow-y: auto; border-radius: 0 0 0.5rem 0.5rem;">
                    <!-- Suggestions akan ditampilkan di sini -->
                </div>
            </div>
            <input type="hidden" name="api_dpjp_id" id="api_dpjp_id" value="{{ old('api_dpjp_id', $log->api_dpjp_id ?? '') }}">
            @error('nama_dpjp')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<!-- SUPERVISOR / APPROVAL -->
<div class="card border border-light bg-light shadow-none mb-4">
    <div class="card-body">
        <h5 class="font-size-15 mb-3 text-warning"><i class="ri-user-star-line me-1"></i> Supervisor / DPJP Utama</h5>
        
        @if(isset($log) && $log->approval_status == 'rejected')
            <div class="alert alert-danger mb-3">
                <strong>Laporan ditolak:</strong> {{ $log->supervisor_note }}
            </div>
        @endif

        <div class="p-3 bg-white rounded border">
            <h5 class="mb-1 text-warning" id="display_supervisor_name">
                {{ isset($log) && $log->nama_dpjp ? $log->nama_dpjp : 'Belum Ditentukan' }}
            </h5>
            <small class="text-muted">Laporan ini akan secara otomatis dikirimkan ke DPJP Anda untuk diverifikasi.</small>
        </div>
    </div>
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

<!-- Modal Body Diagram -->
<div class="modal fade" id="bodyDiagramModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Body Diagram Annotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-10 d-flex justify-content-center">
                        <div class="canvas-container border bg-white shadow-sm" style="overflow: auto;">
                            <canvas id="bodyDiagramCanvas"></canvas>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <div class="mb-2">
                                <label for="diagramType" class="form-label small fw-bold">Pilih Diagram:</label>
                                <select class="form-select form-select-sm" id="diagramType">
                                    <option value="male">Tubuh Laki-laki</option>
                                    <option value="female">Tubuh Perempuan</option>
                                    <option value="dental">Gigi & Mulut</option>
                                </select>
                            </div>
                            
                            <hr class="my-1">
                            <button type="button" class="btn btn-outline-primary active" id="btn_draw_free"><i class="fas fa-pencil-alt me-1"></i> Free Draw</button>
                            
                            <div class="mt-2">
                                <label for="drawing-color" class="form-label small">Warna:</label>
                                <input type="color" id="drawing-color" class="form-control form-control-color w-100" value="#ff0000" title="Choose color">
                            </div>
                            
                            <div class="mt-2">
                                <label for="drawing-line-width" class="form-label small">Ketebalan:</label>
                                <input type="range" id="drawing-line-width" class="form-range" value="3" min="1" max="50">
                            </div>
                            
                            <hr class="my-2">
                            <button type="button" class="btn btn-outline-secondary" id="btn_draw_circle"><i class="far fa-circle me-1"></i> Lingkaran</button>
                            <button type="button" class="btn btn-outline-secondary" id="btn_draw_rect"><i class="far fa-square me-1"></i> Kotak</button>
                            <button type="button" class="btn btn-outline-secondary" id="btn_draw_text"><i class="fas fa-font me-1"></i> Teks</button>
                            <button type="button" class="btn btn-outline-secondary" id="btn_select_mode"><i class="fas fa-mouse-pointer me-1"></i> Pilih Objek</button>
                            
                            <hr class="my-2">
                            <button type="button" class="btn btn-outline-danger" id="btn_delete_object"><i class="fas fa-eraser me-1"></i> Hapus Terpilih</button>
                            <button type="button" class="btn btn-danger" id="btn_clear_canvas"><i class="fas fa-trash me-1"></i> Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_save_diagram">Apply & Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
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

            Swal.fire({
                title: 'Diagnosa Dipilih!',
                text: `${diagnoseID} - ${diagnoseName}`,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
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
                    <button type="button" class="list-group-item list-group-item-action py-2" onclick="selectDokter('${nama.replace(/'/g, "\\'")}', '${kode.replace(/'/g, "\\'")}'); return false;">
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
        function selectDokter(nama, kode) {
            dokterInput.value = nama; 
            document.getElementById('api_dpjp_id').value = kode;
            dokterSuggestions.style.display = 'none';

            // Update nama supervisor di kotak kuning secara live
            const displaySupervisor = document.getElementById('display_supervisor_name');
            if (displaySupervisor) {
                displaySupervisor.textContent = nama;
            }
            
            
            Swal.fire({
                title: 'DPJP Terpilih!',
                text: nama,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
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
            
            Swal.fire({
                title: 'Pasien Terpilih!',
                text: `${patientName} (${medicalNo})`,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            
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
        document.addEventListener('DOMContentLoaded', () => {
            initSoapFormValidation();
            initBodyDiagram();
        });
    } else {
        initSoapFormValidation();
        initBodyDiagram();
    }

    function initBodyDiagram() {
        let canvas = null;
        const modalEl = document.getElementById('bodyDiagramModal');
        if (!modalEl) return;
        
        // Only initialize canvas when modal is shown to ensure correct dimensions
        modalEl.addEventListener('shown.bs.modal', function () {
            if (!canvas) {
                canvas = new fabric.Canvas('bodyDiagramCanvas', {
                    isDrawingMode: true,
                    width: 800,
                    height: 600
                });

                const maleImgUrl = "{{ asset('build/images/body-diagram.jpg') }}";
                const femaleImgUrl = "{{ asset('build/images/body-diagram-female.jpg') }}";
                const dentalImgUrl = "{{ asset('build/images/body-diagram-dental.jpg') }}";
                
                function loadBackgroundImage(url) {
                    fabric.Image.fromURL(url, function(img) {
                        if(!img) return;
                        
                        // Clear canvas objects (but keeping them might be desired in a real app, for simplicity we clear when switching bg)
                        canvas.clear();
                        
                        const scale = Math.min(800 / img.width, 600 / img.height);
                        img.scale(scale);
                        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                            originX: 'center',
                            originY: 'center',
                            left: 400,
                            top: 300
                        });
                    }, { crossOrigin: 'anonymous' });
                }

                // Initial load
                const diagramType = document.getElementById('diagramType');
                
                function updateDiagramBackground() {
                    if (!diagramType) return;
                    let url = maleImgUrl;
                    if (diagramType.value === 'female') url = femaleImgUrl;
                    if (diagramType.value === 'dental') url = dentalImgUrl;
                    loadBackgroundImage(url);
                }

                if (diagramType) {
                    diagramType.addEventListener("change", updateDiagramBackground);
                }
                updateDiagramBackground();
                
                setupCanvasTools(canvas, modalEl);
            }
        });
        
        function setupCanvasTools(canvas, modalEl) {
            // Free Draw
            document.getElementById('btn_draw_free').addEventListener('click', function() {
                canvas.isDrawingMode = true;
                setActiveButton(this);
            });
            
            // Color & Width
            const colorEl = document.getElementById('drawing-color');
            const widthEl = document.getElementById('drawing-line-width');
            
            canvas.freeDrawingBrush.color = colorEl.value;
            canvas.freeDrawingBrush.width = parseInt(widthEl.value, 10) || 3;
            
            colorEl.onchange = function() {
                canvas.freeDrawingBrush.color = this.value;
            };
            widthEl.onchange = function() {
                canvas.freeDrawingBrush.width = parseInt(this.value, 10) || 3;
            };
            
            // Select Mode
            document.getElementById('btn_select_mode').addEventListener('click', function() {
                canvas.isDrawingMode = false;
                setActiveButton(this);
            });
            
            // Circle
            document.getElementById('btn_draw_circle').addEventListener('click', function() {
                canvas.isDrawingMode = false;
                const circle = new fabric.Circle({
                    radius: 30, fill: 'transparent', stroke: colorEl.value, strokeWidth: parseInt(widthEl.value, 10) || 3,
                    left: 100, top: 100
                });
                canvas.add(circle);
                canvas.setActiveObject(circle);
                setActiveButton(this);
            });
            
            // Rectangle
            document.getElementById('btn_draw_rect').addEventListener('click', function() {
                canvas.isDrawingMode = false;
                const rect = new fabric.Rect({
                    width: 60, height: 60, fill: 'transparent', stroke: colorEl.value, strokeWidth: parseInt(widthEl.value, 10) || 3,
                    left: 100, top: 100
                });
                canvas.add(rect);
                canvas.setActiveObject(rect);
                setActiveButton(this);
            });
            
            // Text
            document.getElementById('btn_draw_text').addEventListener('click', function() {
                canvas.isDrawingMode = false;
                const text = new fabric.IText('Teks', {
                    left: 100, top: 100, fill: colorEl.value, fontSize: 24
                });
                canvas.add(text);
                canvas.setActiveObject(text);
                setActiveButton(this);
            });
            
            // Delete Object
            document.getElementById('btn_delete_object').addEventListener('click', function() {
                const activeObjects = canvas.getActiveObjects();
                if (activeObjects.length) {
                    canvas.discardActiveObject();
                    activeObjects.forEach(function(object) {
                        canvas.remove(object);
                    });
                }
            });
            
            // Clear All (except background)
            document.getElementById('btn_clear_canvas').addEventListener('click', function() {
                canvas.getObjects().forEach(function(obj) {
                    canvas.remove(obj);
                });
            });
            
            // Save Diagram
            document.getElementById('btn_save_diagram').addEventListener('click', function() {
                // Remove selection before saving
                canvas.discardActiveObject();
                canvas.renderAll();
                
                // Export canvas to base64
                const dataURL = canvas.toDataURL({
                    format: 'png',
                    quality: 1
                });
                
                document.getElementById('body_diagram_base64').value = dataURL;
                
                // Show preview
                const previewContainer = document.getElementById('body_diagram_preview_container');
                const previewImg = document.getElementById('body_diagram_preview');
                previewImg.src = dataURL;
                previewContainer.style.display = 'block';
                
                bootstrap.Modal.getInstance(modalEl).hide();
            });
            
            function setActiveButton(btn) {
                const buttons = ['btn_draw_free', 'btn_select_mode', 'btn_draw_circle', 'btn_draw_rect', 'btn_draw_text'];
                buttons.forEach(id => {
                    const el = document.getElementById(id);
                    if(!el) return;
                    el.classList.remove('active', 'btn-primary', 'btn-outline-primary');
                    if (id === btn.id) {
                        el.classList.add('active', 'btn-primary');
                    } else {
                        el.classList.add('btn-outline-secondary');
                    }
                });
            }
        }
    }
})();
</script>
@endpush