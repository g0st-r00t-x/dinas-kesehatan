<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ formData: $wire.entangle('data') }" class="bg-white p-6 rounded-lg shadow-lg max-w-3xl mx-auto">
        <div class="text-center border-b pb-4 mb-4">
            <h2 class="text-2xl font-bold">PETIKAN KEPUTUSAN</h2>
            <p class="text-lg">KEPALA DINAS KESEHATAN KABUPATEN SUMENEP</p>
            <p class="text-sm" x-text="'NOMOR: 800/' + (formData?.id || '-') + '/2024'"></p>
            <p class="text-lg font-semibold">TENTANG</p>
            <p class="text-lg">ALIH JENJANG JABATAN FUNGSIONAL TENAGA KESEHATAN</p>
        </div>
        
        <div class="space-y-4">
            <p><strong>KEPALA DINAS KESEHATAN KABUPATEN SUMENEP,</strong></p>
            <p><strong>MEMUTUSKAN:</strong></p>
            
            <p><strong>KESATU:</strong> Mengangkat Pegawai Negeri Sipil dalam Alih Jenjang Jabatan Fungsional Tenaga Kesehatan dengan rincian sebagai berikut:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Nama: <span x-text="formData?.nama || '-'" class="font-semibold"></span></li>
                <li>Tempat/Tanggal Lahir: <span x-text="formData?.tanggal_lahir || '-'" class="font-semibold"></span></li>
                <li>NIP: <span x-text="formData?.nip || '-'" class="font-semibold"></span></li>
                <li>Pangkat/Gol.Ruang/TMT: <span x-text="formData?.pangkat_golongan || '-'" class="font-semibold"></span> / <span x-text="formData?.tmt_pemberian_tunjangan || '-'" class="font-semibold"></span></li>
                <li>Unit Kerja: <span x-text="formData?.unit_kerja?.nama || 'Data tidak tersedia'" class="font-semibold"></span></li>
                <li>Dari Jabatan Lama: <span class="font-semibold">[Jabatan Lama]</span></li>
                <li>Ke Jabatan Baru: <span x-text="formData?.jabatan || '-'" class="font-semibold"></span></li>
            </ul>
            
            <p><strong>KEDUA:</strong> Kepada Pegawai tersebut diberikan Tunjangan Jabatan sesuai ketentuan yang berlaku.</p>
            <p><strong>KETIGA:</strong> Keputusan ini mulai berlaku sejak tanggal ditetapkan.</p>
            <p><strong>KEEMPAT:</strong> Apabila di kemudian hari terdapat kekeliruan dalam Keputusan ini, akan diadakan perbaikan sebagaimana mestinya.</p>
        </div>
        
        <div class="mt-12 text-right">
            <p>Ditetapkan di: <span class="font-semibold">Sumenep</span></p>
            <p>Pada tanggal: <span x-text="formData?.tanggal_surat ? new Date(formData.tanggal_surat).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'" class="font-semibold"></span></p>
            <p class="mt-12 font-semibold">KEPALA DINAS KESEHATAN KABUPATEN SUMENEP</p>
            <p class="mt-20" x-text="formData?.kepala_dinas?.nama || '[Nama Kepala Dinas]'" class="font-semibold"></p>
            <p x-text="'NIP. ' + (formData?.kepala_dinas?.nip || '[NIP Kepala Dinas]')" class="font-semibold"></p>
        </div>
    </div>
</x-dynamic-component>
