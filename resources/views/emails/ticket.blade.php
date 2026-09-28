<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: 0 auto;
        }

        .title {
            color: #1e293b;
            font-size: 20px;
            font-weight: bold;
        }

        .code {
            background: #f1f5f9;
            padding: 10px;
            font-family: monospace;
            font-size: 16px;
            text-align: center;
            margin: 15px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2 class="title">Halo, {{ $ticket->booking->user->name }}!</h2>
        <p>Pendaftaran Anda untuk event <strong>{{ $ticket->booking->slot->event->title }}</strong> telah berhasil.</p>

        <p>Kode Tiket Unik Anda:</p>
        <div class="code">{{ $ticket->ticket_code }}</div>

        <p>Tunjukkan kode ini atau QR Code pada e-ticket Anda saat melakukan <em>check-in</em> di lokasi event.</p>
        <p>Terima kasih!</p>
    </div>
</body>

</html>
