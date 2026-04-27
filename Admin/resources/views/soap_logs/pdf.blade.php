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
                    <td>: {{ $log->patient->name ?? 'Data tidak ditemukan' }}</td>
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
    @if($log->diagnosis)
        : {{ $log->diagnosis->diagnose_id }} - 
          {{ $log->diagnosis->diagnose_name }}
    @else
        : {{ $log->diagnosa_id ?? 'Tidak ada' }}
    @endif
</td>
                </tr>
            </table>
        </div>

        <div class="soap-section">
            <h3>S (Subjective)</h3>
            <div class="content">{{ $log->subjective }}</div>
        </div>

        <div class="soap-section">
            <h3>O (Objective)</h3>
            <div class="content">{{ $log->objective }}</div>
        </div>

        <div class="soap-section">
            <h3>A (Assessment)</h3>
            <div class="content">{{ $log->assessment }}</div>
        </div>

        <div class="soap-section">
            <h3>P (Plan)</h3>
            <div class="content">{{ $log->plan }}</div>
        </div>

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
