<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-article"></i> <?= $berita ? 'Edit Berita' : 'Tulis Berita Baru' ?>
        </h2>
        <a href="<?= base_url('berita/manage') ?>" class="text-gray-500 hover:text-navy-800"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 text-red-600 p-3 rounded-md mb-4 text-sm font-semibold border border-red-200"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form id="formBerita" action="<?= base_url('berita/save') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        <?php if($berita): ?> <input type="hidden" name="id" value="<?= $berita->id ?>"> <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Judul Berita</label>
            <input type="text" name="judul" required value="<?= $berita ? esc($berita->judul) : '' ?>" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 text-lg font-semibold" placeholder="Masukkan judul yang menarik...">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Gambar Thumbnail (Sampul)</label>
                <input type="file" name="thumbnail" accept="image/*" <?= $berita ? '' : 'required' ?> class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-navy-700 hover:file:bg-blue-100">
                <?php if($berita && $berita->thumbnail): ?>
                    <p class="text-xs text-gray-500 mt-2">Gambar saat ini: <a href="<?= base_url('uploads/berita/' . $berita->thumbnail) ?>" target="_blank" class="text-blue-600 underline">Lihat Gambar</a></p>
                <?php else: ?>
                    <p class="text-xs text-gray-400 mt-1">Rekomendasi rasio 16:9 (Landscape)</p>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Status Publikasi</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white">
                    <option value="draft" <?= ($berita && $berita->status == 'draft') ? 'selected' : '' ?>>Simpan sebagai Draft (Hanya Admin yang lihat)</option>
                    <option value="publish" <?= ($berita && $berita->status == 'publish') ? 'selected' : '' ?>>Publish (Tampil di Publik)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Isi Konten Berita</label>
            <div id="editor-container" class="bg-white" style="height: 400px; font-size: 16px;">
                <?= $berita ? $berita->konten : '' ?>
            </div>
            <textarea name="konten" id="konten-hidden" class="hidden"></textarea>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t">
            <a href="<?= base_url('berita/manage') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 text-white px-8 py-2 rounded-md font-bold hover:bg-navy-900 shadow-sm flex items-center gap-2 transition">
                <i class="ph ph-paper-plane-right"></i> Simpan & Posting
            </button>
        </div>
    </form>
</div>

<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tuliskan detail kegiatan atau pengumuman di sini...',
        modules: {
            toolbar: {
                container: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['link', 'image', 'blockquote'], // Tombol 'image' sudah ditambahkan di sini
                    ['clean']
                ],
                // Mengganti perilaku default unggah gambar Quill ke AJAX kita sendiri
                handlers: {
                    image: imageHandler
                }
            }
        }
    });

    // Fungsi memproses upload gambar saat tombol gambar diklik
    function imageHandler() {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            var file = input.files[0];
            var formData = new FormData();
            formData.append('image', file);
            
            // Ambil token CSRF dari form utama agar tidak ditolak oleh server
            var csrfName = '<?= csrf_token() ?>';
            var csrfInput = document.querySelector('input[name="' + csrfName + '"]');
            formData.append(csrfName, csrfInput.value);

            Swal.fire({ title: 'Mengunggah...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

            fetch('<?= base_url('berita/upload-image') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                Swal.close();
                if (result.url) {
                    // Update token CSRF di form utama agar form nanti bisa ditekan 'Simpan'
                    csrfInput.value = result.csrfHash;
                    
                    // Sisipkan gambar yang berhasil diunggah ke dalam teks editor
                    var range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', result.url);
                } else {
                    Swal.fire('Gagal', result.error || 'Terjadi kesalahan sistem.', 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire('Gagal', 'Koneksi ke server terputus.', 'error');
            });
        };
    }

    // Menangkap momen sebelum form disubmit untuk menyalin isi HTML Quill
    var form = document.getElementById('formBerita');
    form.onsubmit = function() {
        var kontenHidden = document.querySelector('#konten-hidden');
        kontenHidden.value = quill.root.innerHTML;
        
        if (quill.getText().trim().length === 0 && !quill.root.innerHTML.includes('<img')) {
            Swal.fire({ icon: 'warning', title: 'Konten Kosong', text: 'Silakan isi konten berita terlebih dahulu.' });
            return false;
        }
        return true;
    };
</script>
<?= $this->endSection() ?>