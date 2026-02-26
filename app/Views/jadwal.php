<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div class="text-center md:text-left">
        <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-2">Kalender Laboratorium</h2>
        <p class="text-gray-500 text-sm md:text-base">Pantau jadwal pemakaian lab yang telah disetujui secara real-time.</p>
    </div>
    
    <div class="w-full md:w-1/3">
        <label for="labFilter" class="block text-xs font-bold text-gray-500 mb-1 ml-1 uppercase">Filter Ruangan</label>
        <div class="relative">
            <i class="ph ph-funnel absolute left-3 top-1/2 -translate-y-1/2 text-navy-800 text-lg"></i>
            <select id="labFilter" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-navy-800 bg-white font-semibold text-navy-900 cursor-pointer appearance-none">
                <option value="">Semua Laboratorium</option>
                <?php foreach($labs as $lab): ?>
                    <option value="<?= $lab->id ?>"><?= esc($lab->nama_lab) ?></option>
                <?php endforeach; ?>
            </select>
            <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>
</div>

<div class="flex flex-wrap items-center justify-center md:justify-start gap-3 md:gap-6 mb-6 text-xs md:text-sm font-bold bg-white p-3 rounded-lg border border-gray-100 shadow-sm w-fit mx-auto md:mx-0">
    <div class="flex items-center gap-2">
        <span class="w-3 h-3 md:w-4 md:h-4 rounded-full bg-navy-800"></span> KBM (Reguler)
    </div>
    <div class="flex items-center gap-2">
        <span class="w-3 h-3 md:w-4 md:h-4 rounded-full bg-purple-600"></span> Kegiatan Khusus
    </div>
</div>

<div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div id='calendar' class="min-h-[500px]"></div>
</div>

<style>
    /* VARIAN WARNA TOMBOL */
    :root {
        --fc-button-bg-color: #1e3a8a;
        --fc-button-border-color: #1e3a8a;
        --fc-button-hover-bg-color: #172554;
        --fc-button-hover-border-color: #172554;
        --fc-button-active-bg-color: #172554;
        --fc-button-active-border-color: #172554;
        --fc-today-bg-color: #eff6ff;
        --fc-now-indicator-color: #ef4444;
    }

    /* 1. JARAK ANTAR TOMBOL (Agar tidak nempel) */
    .fc-header-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px; /* Jarak minimal antar grup */
        margin-bottom: 1.5em !important;
    }

    /* 2. RESPONSIVE MOBILE TWEAKS */
    @media (max-width: 768px) {
        /* Susun toolbar jadi vertikal jika judul terlalu panjang */
        .fc-header-toolbar {
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px; 
        }
        
        /* Judul Kalender lebih kecil di HP */
        .fc-toolbar-title { 
            font-size: 1.1rem !important; 
            font-weight: 800; 
            color: #1e3a8a;
            width: 100%;
            text-align: center;
            order: -1; /* Taruh judul paling atas */
        }

        /* Ukuran Tombol Navigasi HP */
        .fc-button { 
            font-size: 0.75rem !important; 
            padding: 0.5rem 0.8rem !important; 
            border-radius: 0.5rem !important; /* Tombol membulat penuh, tidak kotak nempel */
            margin: 0 3px !important; /* Beri jarak antar tombol */
        }

        /* Sembunyikan grup tombol container bawaan agar margin kita bekerja */
        .fc-button-group {
            display: flex;
            gap: 5px; /* Jarak antar tombol dalam grup */
        }
        
        /* Paksa tombol dalam grup untuk tidak nempel sisinya */
        .fc-button-group > .fc-button {
            border-radius: 0.5rem !important;
            margin-left: 0 !important;
            flex: 1; /* Tombol sama lebar */
        }
    }
    
    /* Desktop Style */
    @media (min-width: 769px) {
        .fc-toolbar-title { font-size: 1.5rem !important; }
        .fc-button { font-size: 0.9rem !important; }
    }

    /* Hilangkan garis bawah link di list view */
    .fc-list-event-title a { text-decoration: none !important; color: inherit; cursor: pointer; }
    .fc-list-day-cushion { background-color: #f8fafc !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var labFilter = document.getElementById('labFilter');

        // Deteksi Mobile
        var isMobile = window.innerWidth < 768;

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: isMobile ? 'listWeek' : 'timeGridWeek',
            
            // KONFIGURASI TOOLBAR RESPONSIF
            headerToolbar: {
                // Mobile: Gunakan SPASI (' ') bukan KOMA (',') agar tombol terpisah
                left: isMobile ? 'prev next' : 'prev,next today', 
                center: 'title',
                // Mobile: Pisahkan tombol ganti view
                right: isMobile ? 'dayGridMonth listWeek' : 'dayGridMonth,timeGridWeek,listWeek'
            },
            
            slotMinTime: '06:00:00', 
            slotMaxTime: '17:00:00',
            allDaySlot: false,
            locale: 'id',
            contentHeight: 'auto',
            nowIndicator: true,
            
            windowResize: function(view) {
                if (window.innerWidth < 768) {
                    calendar.changeView('listWeek');
                    calendar.setOption('headerToolbar', {
                        left: 'prev next', // Tombol terpisah
                        center: 'title', 
                        right: 'dayGridMonth listWeek' // Tombol terpisah
                    });
                } else {
                    calendar.changeView('timeGridWeek');
                    calendar.setOption('headerToolbar', {
                        left: 'prev,next today', 
                        center: 'title', 
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    });
                }
            },

            events: function(info, successCallback, failureCallback) {
                var labId = labFilter.value;
                var url = '<?= base_url('/api/events') ?>';
                if (labId) url += '?lab_id=' + labId;
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
            
            eventClick: function(info) {
                let badgeClass = info.event.extendedProps.jenis === 'KBM' ? 'bg-navy-100 text-navy-800' : 'bg-purple-100 text-purple-800';
                Swal.fire({
                    title: `<span class="text-lg font-bold">${info.event.title}</span>`,
                    html: `
                        <div class="text-left mt-4 space-y-3 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500"><i class="ph ph-user"></i></div>
                                <div><p class="text-xs text-gray-500">Peminjam / Guru</p><p class="font-bold text-gray-800">${info.event.extendedProps.peminjam}</p></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500"><i class="ph ph-clock"></i></div>
                                <div><p class="text-xs text-gray-500">Waktu Pemakaian</p><p class="font-bold text-gray-800">${info.event.extendedProps.waktu}</p></div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500"><i class="ph ph-tag"></i></div>
                                <div><p class="text-xs text-gray-500">Jenis Kegiatan</p><span class="px-2 py-1 rounded-md text-xs font-bold ${badgeClass}">${info.event.extendedProps.jenis}</span></div>
                            </div>
                        </div>
                    `,
                    showConfirmButton: false, showCloseButton: true,
                    customClass: { popup: 'rounded-2xl', title: 'text-navy-900' }
                });
            }
        });

        calendar.render();
        labFilter.addEventListener('change', function() { calendar.refetchEvents(); });
    });
</script>
<?= $this->endSection() ?>