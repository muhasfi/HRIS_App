<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salary Slip - {{ $payroll->employee->fullname }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            background: #f0f0f0;
        }

        .container {
            width: 720px;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Header */
        .header {
            background: #1a3c6e;
            color: #fff;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .company-name {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header .slip-title {
            font-size: 14px;
            text-align: right;
            opacity: 0.85;
        }

        .header .slip-title span {
            display: block;
            font-size: 18px;
            font-weight: bold;
            opacity: 1;
        }

        /* Employee Info */
        .employee-info {
            background: #f7f9fc;
            padding: 15px 25px;
            border-bottom: 1px solid #dde3ed;
            display: flex;
            gap: 40px;
        }

        .employee-info .info-item label {
            display: block;
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .employee-info .info-item span {
            font-size: 14px;
            font-weight: bold;
            color: #1a3c6e;
        }

        /* Body */
        .body {
            padding: 20px 25px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 1px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table td {
            padding: 9px 8px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        table td:last-child {
            text-align: right;
            font-weight: 500;
        }

        .text-green {
            color: #28a745;
        }

        .text-red {
            color: #dc3545;
        }

        /* Net Salary */
        .net-box {
            background: #1a3c6e;
            color: #fff;
            border-radius: 6px;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
        }

        .net-box .net-label {
            font-size: 14px;
            opacity: 0.85;
        }

        .net-box .net-amount {
            font-size: 20px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 12px;
            font-size: 11px;
            color: #aaa;
            border-top: 1px solid #eee;
        }

        /* Print */
        @media print {
            body {
                background: #fff;
            }
            .container {
                box-shadow: none;
                margin: 0;
                border: none;
                border-radius: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">

    {{-- Header --}}
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="slip-title">
            Dokumen Resmi
            <span>SLIP GAJI</span>
        </div>
    </div>

    {{-- Employee Info --}}
    <div class="employee-info">
        <div class="info-item">
            <label>Nama Karyawan</label>
            <span>{{ $payroll->employee->fullname }}</span>
        </div>
        <div class="info-item">
            <label>Tanggal Pembayaran</label>
            <span>{{ \Carbon\Carbon::parse($payroll->pay_date)->translatedFormat('d F Y') }}</span>
        </div>
        {{-- Tambahkan kolom lain jika ada, misal: --}}
        {{-- 
        <div class="info-item">
            <label>Jabatan</label>
            <span>{{ $payroll->employee->position }}</span>
        </div>
        --}}
    </div>

    {{-- Body --}}
    <div class="body">

        {{-- Pendapatan --}}
        <div class="section-title">Pendapatan</div>
        <table>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-green">Rp {{ number_format($payroll->salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bonus</td>
                <td class="text-green">Rp {{ number_format($payroll->bonuses, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- Potongan --}}
        <div class="section-title">Potongan</div>
        <table>
            <tr>
                <td>Total Potongan</td>
                <td class="text-red">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- Gaji Bersih --}}
        <div class="net-box">
            <div class="net-label">Gaji Bersih (Take Home Pay)</div>
            <div class="net-amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem &mdash; {{ config('app.name') }}
    </div>

</div>

</body>
</html>