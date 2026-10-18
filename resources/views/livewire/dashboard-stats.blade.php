<div>
    <form wire:submit.prevent="$refresh" class="card content-card p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-8 col-md-3">
                <label class="form-label small">Periode (Bulan)</label>
                <input type="month" wire:model.live="bulan" class="form-control">
            </div>
            <div class="col-4 col-md-9 text-muted small">Periode: {{ $start }} s/d {{ $end }}</div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card card-green p-3">
                <div class="small">Uang Masuk</div>
                <div class="stat-num">{{ rupiah($masuk) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-red p-3">
                <div class="small">Uang Keluar</div>
                <div class="stat-num">{{ rupiah($keluar) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-blue p-3">
                <div class="small">Balance</div>
                <div class="stat-num">{{ rupiah($masuk - $keluar) }}</div>
            </div>
        </div>
        @if (auth()->user()->isAdmin())
            <div class="col-6 col-md-3">
                <div class="card card-purple p-3">
                    <div class="small">Total Saldo Bank</div>
                    <div class="stat-num">{{ rupiah($totalSaldo) }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card content-card p-3">
                <h6 class="fw-bold">Grafik Harian (Masuk vs Keluar)</h6>
                <div style="height:280px"><canvas id="chartHarian"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card content-card p-3">
                <h6 class="fw-bold">Pengeluaran per Kategori</h6>
                <div style="height:280px"><canvas id="chartDonut"></canvas></div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:init', () => {
            const draw = () => {
                const c1 = document.getElementById('chartHarian');
                if (c1 && window.Chart) {
                    if (window._ch1) window._ch1.destroy();
                    window._ch1 = new Chart(c1, {
                        type: 'bar',
                        data: {
                            labels: @js($labels),
                            datasets: [{
                                    label: 'Masuk',
                                    data: @js($dataMasuk),
                                    backgroundColor: '#1ed6a0'
                                },
                                {
                                    label: 'Keluar',
                                    data: @js($dataKeluar),
                                    backgroundColor: '#ff6b6b'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
                const c2 = document.getElementById('chartDonut');
                if (c2 && window.Chart) {
                    if (window._ch2) window._ch2.destroy();
                    window._ch2 = new Chart(c2, {
                        type: 'doughnut',
                        data: {
                            labels: @js($perKategori->map(fn($x) => $x->kategori->nama_kategori ?? '-')),
                            datasets: [{
                                data: @js($perKategori->pluck('total'))
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            };
            draw();
            Livewire.hook('morph.updated', () => draw());
        });
    </script>
@endpush
