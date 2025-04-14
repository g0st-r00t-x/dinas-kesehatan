<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>SK Alih Jenjang</title>
  <style>
    @page {
        size: A4 portrait;
        font-family: "Times New Roman";
        margin: 8mm;
    }
    
    body {
        font-family: "Times New Roman";
        font-size: 20px; /* Diperbesar sedikit dari sebelumnya */
        line-height: 1.4;
        margin: 0;
        padding: 0;
    }
    
    .container {
        width: 210mm;
        height: 297mm;
        word-wrap: break-word;
        box-sizing: border-box;
        position: relative;
        line-height: 130%;
        page-break-after: auto;
        overflow: hidden;
        max-width: 100%;
        height: auto;
    }
    
    .header {
        text-align: center;
    }

    .header h2 {
        font-size: 24px; /* Sedikit lebih besar */
        margin-bottom: -2mm;
    }

    .header h3 {
        font-size: 22px; /* Ukuran sedang */
        margin-bottom: -1mm;
    }

    .header p {
        font-size: 18px; /* Sedikit lebih kecil dari body */
        }
    
    .title {
        text-align: center;
    }

    .title h3 {
        font-size: 20px;
        margin: -2mm;
    }

    .title p {
        font-size: 18px;
        margin: 2mm;
        line-height: 120%;
    }
    
    h3, .title, p {
        font-size: 18px;
        margin: 2mm;
        line-height: 120%;
    }

    .content {
        width: 100%;
        text-wrap: initial;
    }

    .content p {
        text-align: justify;
    }

    .content ul {
        list-style-type: none;
        padding-left: 10mm;
    }

    .content ul li {
        font-size: 18px; /* Ukuran daftar diperbesar sedikit */
        margin: 2mm 0;
    }

    .signature {
        margin-top: 60px;
        text-align: right;
        font-size: 15px;
    }

    hr {
        border: none;
        border-top: 1px solid black;
        margin: 5mm 0;
    }

    .space {
        margin-top: 50px; /* Jarak lebih proporsional */
    }
    
    @media print {
        body {
            background: none;
        }
        
        .container {
            box-shadow: none;
            min-height: auto;
            max-height: none;
        }
    }
  </style>
</head>
<body>
  <div class="container">
      <div class="header">
          <h2>PEMERINTAH KABUPATEN SUMENEP</h2>
          <h3>DINAS KESEHATAN</h3>
          <p>Jl. Dr. Cipto No. 35, Sumenep, Jawa Timur 69417</p>
          <hr>
      </div>
      <div class="title">
          <h3>PETIKAN KEPUTUSAN</h3>
          <p>KEPALA DINAS KESEHATAN KABUPATEN SUMENEP</p>
          <p>NOMOR: 800/{{ $record->id }}/2024</p>
          <p>TENTANG</p>
          <p>ALIH JENJANG JABATAN FUNGSIONAL TENAGA KESEHATAN</p>
      </div>
      <div class="content">
          <p><strong>KEPALA DINAS KESEHATAN KABUPATEN SUMENEP,</strong></p>
          <p>Menimbang: dst;</p>
          <p>Mengingat: dst;</p>
          <p>Memperhatikan: dst;</p>
          <p><strong>MEMUTUSKAN:</strong></p>
          <p><strong>KESATU:</strong> Mengangkat Pegawai Negeri Sipil dalam Alih Jenjang Jabatan Fungsional Tenaga Kesehatan dengan rincian sebagai berikut dalama perkembangan jabatan pada dinas kesehatan sumenep semua hal yang berkaitan dengan jabatan fungsional akan ditakdirkan sebagaia pelaksanaan kerja pada bualan masyarakat:</p>
          <ul>
              <li>Nama: {{ $record->nama }}</li>
              <li>Tempat/Tanggal Lahir: {{ $record->tanggal_lahir }}</li>
              <li>NIP: {{ $record->nip }}</li>
              <li>Pangkat/Gol.Ruang/TMT: {{ $record->pangkat_golongan }} / {{ $record->tmt_pemberian_tunjangan }}</li>
              <li>Unit Kerja: {{ $record?->unit_kerja?->nama ?? 'Data tidak tersedia' }}</li>
              <li>Dari Jabatan Lama: [Jabatan Lama]</li>
              <li>Ke Jabatan Baru: {{ $record->jabatan }}</li>
          </ul>
          <p><strong>KEDUA:</strong> Kepada Pegawai tersebut diberikan Tunjangan Jabatan sesuai ketentuan yang berlaku;</p>
          <p><strong>KETIGA:</strong> Keputusan ini mulai berlaku sejak tanggal ditetapkan;</p>
          <p><strong>KEEMPAT:</strong> Apabila di kemudian hari terdapat kekeliruan dalam Keputusan ini, akan diadakan perbaikan sebagaimana mestinya.</p>
      </div>
      <div class="signature">
          <p>Ditetapkan di: Sumenep</p>
          <p>Pada tanggal: {{ date('d F Y', strtotime($record->created_at)) }}</p>
          <p class="space"><strong>KEPALA DINAS KESEHATAN KABUPATEN SUMENEP</strong></p>
          <div class="space">
              <p>[Nama Kepala Dinas]</p>
              <p>NIP. [NIP Kepala Dinas]</p>
          </div>
      </div>
  </div>
</body>
</html>
