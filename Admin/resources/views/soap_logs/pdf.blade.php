<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan SOAP - {{ $log->patient->name ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .patient-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .patient-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .patient-info th, .patient-info td {
            text-align: left;
            padding: 5px;
            vertical-align: top;
        }
        .patient-info th {
            width: 150px;
        }
        .ttv-table-container {
            margin-bottom: 15px;
            overflow-x: auto;
        }
        .ttv-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 2px 0;
            text-align: center;
        }
        .ttv-table th {
            background-color: #888;
            color: #fff;
            font-style: italic;
            padding: 4px;
            font-weight: normal;
            font-size: 11px;
        }
        .ttv-table td {
            border: 1px solid #aaa;
            padding: 4px;
            font-weight: bold;
            font-size: 12px;
            background-color: #fff;
        }
        .soap-section {
            margin-bottom: 15px;
        }
        .soap-section h3 {
            background-color: #f2f2f2;
            padding: 8px;
            margin: 0;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .soap-section .content {
            padding: 10px;
            border: 1px solid #ddd;
            border-top: none;
            white-space: pre-wrap; /* Agar baris baru tetap tampil */
            word-wrap: break-word;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .signature {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Ganti dengan nama instansi Anda --}}
            <h1>Laporan PPDS SOAP Pasien</h1>
            <p>RS STELLA MARIS MAKASSAR</p>
        </div>

        <div class="patient-info"> 
            <table>
                <tr>
                    <th>Nama Pasien</th>
                    <td>: {{ $log->display_patient_name ?? 'Data tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <th>No. Registrasi</th>
                    <td>: {{ $log->patient_registration_no ?? '-' }}</td>
                </tr>
                <tr>
                    <th>No. Rekam Medis</th>
                    <td>: {{ $log->medical_record_no ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kunjungan</th>
                    <td>: {{ \Carbon\Carbon::parse($log->visit_date)->isoFormat('dddd, D MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <th>Dokter Pemeriksa</th>
                    <td>: {{ $log->doctor->name ?? 'Data tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <th>Nama DPJP</th>
                    <td>: {{ $log->nama_dpjp ?? 'Data tidak ditemukan' }}</td>
                </tr>
                <tr>
                    <th>Diagnosis</th>
                    <td>
                        @if($log->diagnosa_id)
                            : {{ $log->diagnosa_id }} - {{ $diagnosisName ?? 'Tidak ditemukan' }}
                        @else
                            : Tidak ada
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        @if($log->ttv_td || $log->ttv_hr || $log->ttv_rr || $log->ttv_temp || $log->ttv_spo2 || $log->ttv_vas)
        <div class="ttv-table-container">
            <table class="ttv-table">
                <tr>
                    <th>TD</th>
                    <th>HR</th>
                    <th>RR</th>
                    <th>TEMP</th>
                    <th>SpO2</th>
                    <th>VAS</th>
                </tr>
                <tr>
                    <td>{{ $log->ttv_td ?? '-' }}</td>
                    <td>{{ $log->ttv_hr ?? '-' }}</td>
                    <td>{{ $log->ttv_rr ?? '-' }}</td>
                    <td>{{ $log->ttv_temp ?? '-' }}</td>
                    <td>{{ $log->ttv_spo2 ?? '-' }}</td>
                    <td>{{ $log->ttv_vas ?? '-' }}</td>
                </tr>
            </table>
        </div>
        @endif

        <div class="soap-section">
            <h3>S (Subjective)</h3>
            <div class="content">{{ $log->subjective }}</div>
        </div>

        <div class="soap-section">
            <h3>O (Objective)</h3>
            <div class="content">
                {{ $log->objective }}
            </div>
        </div>

        <div class="soap-section">
            <h3>A (Assessment)</h3>
            <div class="content">{{ $log->assessment }}</div>
        </div>

        <div class="soap-section">
            <h3>P (Plan)</h3>
            <div class="content">{{ $log->plan }}</div>
        </div>

        @if(!empty($log->foto_visite))
        <div class="soap-section" style="page-break-inside: avoid;">
            <h3>Foto Hasil Visite</h3>
            <div class="content" style="text-align: center; white-space: normal; padding: 15px;">
                @if(is_array($log->foto_visite))
                    @foreach($log->foto_visite as $foto)
                        <img src="{{ public_path('storage/' . $foto) }}" alt="Foto Visite" style="height: 250px; width: auto; max-width: 100%; margin: 5px; border: 1px solid #ddd; padding: 3px; display: inline-block; vertical-align: top;">
                    @endforeach
                @else
                    <img src="{{ public_path('storage/' . $log->foto_visite) }}" alt="Foto Visite" style="height: 250px; width: auto; max-width: 100%; margin: 5px; border: 1px solid #ddd; padding: 3px; display: inline-block; vertical-align: top;">
                @endif
            </div>
        </div>
        @endif

        <div class="footer">
            <div class="printed-date">
                Dicetak pada: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}
            </div>
            <div class="signature">
                <p>Hormat kami,</p>
                <br><br><br>
                <p><strong>{{ $log->creator->name ?? 'Dokter Pemeriksa' }}</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
