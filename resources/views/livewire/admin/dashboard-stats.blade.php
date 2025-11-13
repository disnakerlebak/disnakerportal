<div class="py-8">
  <div class="max-w-6xl mx-auto px-6 space-y-8 text-slate-100">
    <!-- KPI cards -->
    <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-4">
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow text-center">
        <p class="text-slate-400 text-xs">Antrian Verifikasi</p>
        <div class="text-3xl font-bold text-indigo-400 mt-1">{{ $pending }}</div>
      </div>
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow text-center">
        <p class="text-slate-400 text-xs">AK1 Aktif</p>
        <div class="text-3xl font-bold text-emerald-400 mt-1">{{ $approvedActive }}</div>
      </div>
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4 shadow text-center">
        <p class="text-slate-400 text-xs">Ditolak Bulan Ini</p>
        <div class="text-3xl font-bold text-rose-400 mt-1">{{ $rejectedThisMonth }}</div>
      </div>
    </div>

    <!-- Summary cards -->
    <div class="grid md:grid-cols-3 gap-4">
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <p class="text-slate-400 text-sm">Total Pencaker</p>
        <div class="text-3xl font-bold">{{ $totalPencaker }}</div>
        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
          <div class="rounded border border-slate-800 p-3">
            <div class="text-slate-400">Lengkap Profil</div>
            <div class="font-semibold text-emerald-400">{{ $lengkapProfil }}</div>
          </div>
          <div class="rounded border border-slate-800 p-3">
            <div class="text-slate-400">Belum Lengkap</div>
            <div class="font-semibold text-yellow-400">{{ $belumLengkap }}</div>
          </div>
        </div>
      </div>

      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <p class="text-slate-400 text-sm">Pencaker Aktif (punya AK1)</p>
        <div class="text-3xl font-bold text-indigo-300">{{ $activeSeekers }}</div>
        @php
          $activeBase = max(1, (int)$activeSeekers);
          $pctTrain = round(100 * (int)$withTraining / $activeBase);
          $pctWork  = round(100 * (int)$withWork / $activeBase);
        @endphp
        <div class="mt-3 space-y-2 text-sm">
          <div class="flex items-center justify-between"><span class="text-slate-400">Dengan Pelatihan</span><span class="font-medium">{{ $withTraining }} ({{ $pctTrain }}%)</span></div>
          <div class="w-full h-2 rounded bg-slate-800 overflow-hidden"><div class="h-2 bg-emerald-500" style="width: {{ $pctTrain }}%"></div></div>
          <div class="flex items-center justify-between mt-2"><span class="text-slate-400">Dengan Pengalaman</span><span class="font-medium">{{ $withWork }} ({{ $pctWork }}%)</span></div>
          <div class="w-full h-2 rounded bg-slate-800 overflow-hidden"><div class="h-2 bg-sky-500" style="width: {{ $pctWork }}%"></div></div>
        </div>
      </div>

      <!-- Donut Gender -->
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <p class="text-slate-400 text-sm mb-3">Komposisi Jenis Kelamin (Disetujui)</p>
        <div class="h-72 opacity-0 scale-90 transition duration-300 overflow-visible" wire:ignore
             x-data="chartComponent({type:'gender', initialData: @js($genderChart)})" x-init="init()">
          <canvas x-ref="canvas"></canvas>
        </div>
      </div>
    </div>

    <!-- Pendidikan & Kecamatan charts -->
    <div class="grid md:grid-cols-2 gap-4">
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <h3 class="text-lg font-semibold mb-3">Tingkat Pendidikan (Disetujui)</h3>
        <div class="h-80 opacity-0 scale-90 transition duration-300" wire:ignore
             x-data="chartComponent({type:'education', initialData: @js($educationChart)})" x-init="init()">
          <canvas x-ref="canvas"></canvas>
        </div>
      </div>
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <h3 class="text-lg font-semibold mb-3">Sebaran Kecamatan (Disetujui)</h3>
        <div class="h-96 opacity-0 scale-90 transition duration-300" wire:ignore
             x-data="chartComponent({type:'district', initialData: @js($districtChart)})" x-init="init()">
          <canvas x-ref="canvas"></canvas>
        </div>
      </div>
    </div>

    <!-- Rekap bulanan & log -->
    <div class="grid md:grid-cols-2 gap-4">
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <h3 class="text-lg font-semibold mb-3">Rekap Disetujui per Bulan (12 Bulan)</h3>
        <div class="h-80 opacity-0 scale-90 transition duration-300" wire:ignore
             x-data="chartComponent({type:'monthly', initialData: @js(['labels'=>$monthlyLabels, 'data'=>$monthlyCounts])})" x-init="init()">
          <canvas x-ref="canvas"></canvas>
        </div>
      </div>
      <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 shadow">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
          <a href="{{ route('admin.ak1.index') }}" class="text-blue-400 text-sm hover:underline">Buka Verifikasi</a>
        </div>
        <div class="max-h-72 overflow-y-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-slate-800 text-slate-300">
              <tr>
                <th class="p-2 text-left">Waktu</th>
                <th class="p-2 text-left">Aktor</th>
                <th class="p-2 text-left">Aksi</th>
                <th class="p-2 text-left">Ke Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
              @forelse($recentLogs as $log)
                <tr class="hover:bg-slate-800/40">
                  <td class="p-2">{{ $log['time'] ?? '-' }}</td>
                  <td class="p-2">{{ $log['actor'] ?? '-' }}</td>
                  <td class="p-2">{{ ucfirst($log['action'] ?? '-') }}</td>
                  <td class="p-2">{{ $log['to'] ?? '-' }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="p-3 text-slate-400">Belum ada aktivitas</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <p class="text-xs text-slate-400">Data diperbarui: {{ $lastUpdated }} WIB</p>
  </div>

  @once
    @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
      <script>
        window.chartComponent = function({ type, initialData }) {
          return {
            chart: null,
            created: false,
            observer: null,
            init() {
              // Always create the chart immediately to avoid missing render on certain navigations
              if (!this.created) {
                this.createChart(initialData);
                this.created = true;
              }

              // Reveal animation only when in viewport
              const revealNow = () => this.$el.classList.remove('opacity-0','scale-90');
              const r = this.$refs.canvas.getBoundingClientRect();
              const vh = (window.innerHeight || document.documentElement.clientHeight);
              if (r.top < vh && r.bottom > 0) {
                revealNow();
              } else {
                const io = new IntersectionObserver((entries) => {
                  if (entries[0].isIntersecting) {
                    revealNow();
                    io.disconnect();
                  }
                }, { threshold: 0.1 });
                io.observe(this.$refs.canvas);
              }

              // Listen to Livewire updates
              Livewire.on('updateChart', (payload) => {
                if (payload?.type !== type) return;
                if (!this.chart) { initialData = payload.data; this.createChart(initialData); this.created = true; return; }
                if (type === 'gender' && this._updateGender) { this._updateGender(payload); return; }
                if (payload.data?.labels) this.chart.data.labels = payload.data.labels;
                if (payload.data?.data) this.chart.data.datasets[0].data = payload.data.data;
                this.chart.update('none');
              });
            },
            createChart(data) {
              const isDark = document.documentElement.classList.contains('dark') || localStorage.getItem('theme') === 'dark';
              const gridColor = isDark ? 'rgba(148,163,184,0.25)' : 'rgba(100,116,139,0.25)';
              const textColor = isDark ? '#e2e8f0' : '#0f172a';
              const tooltipBg = isDark ? '#0b1220' : '#ffffff';

              const baseAnimation = { duration: 800, easing: 'easeOutQuart' };
              const commonOpts = {
                responsive: true,
                animation: baseAnimation,
                plugins: {
                  legend: { labels: { color: textColor, boxWidth: 10, usePointStyle: true, pointStyle: 'circle', padding: 8, font: { size: 10 } } },
                  tooltip: { backgroundColor: tooltipBg, titleColor: textColor, bodyColor: textColor }
                }
              };
              // Staggered bar reveal so each column animates in sequence
              const barAnimation = {
                ...baseAnimation,
                delay(ctx) {
                  if (ctx.type !== 'data' || ctx.mode !== 'default') return 0;
                  if (ctx.chart?.$staggeredDone) return 0;
                  return ctx.dataIndex * 120;
                },
                onComplete(animationContext) {
                  animationContext.chart.$staggeredDone = true;
                }
              };

              // Ensure canvas stretches to container to avoid size jitter
              this.$refs.canvas.style.width = '100%';
              this.$refs.canvas.style.height = '100%';
              this.$refs.canvas.style.display = 'block';
              const ctx = this.$refs.canvas.getContext('2d');
              if (type === 'gender') {
                const decorate = (payload) => {
                  const total = (payload.data || []).reduce((a,b)=>a + (Number(b)||0), 0);
                  return (payload.labels || []).map((l,i)=>{
                    const v = Number(payload.data?.[i]||0);
                    const pct = total ? Math.round((v/total)*100) : 0;
                    return `${l} — ${v} (${pct}%)`;
                  });
                };
                let decorated = decorate(data);
                this.chart = new Chart(ctx, {
                  type: 'doughnut',
                  data: { labels: decorated, datasets: [{ data: data.data || [], backgroundColor: ['#3b82f6','#ef4444','#64748b','#10b981','#f59e0b'] }] },
                  options: { 
                    ...commonOpts,
                    maintainAspectRatio:false,
                    cutout:'60%',
                    layout: { padding: { bottom: 8 } },
                    plugins: {
                      ...commonOpts.plugins,
                      legend: { display: true, position: 'bottom', labels: commonOpts.plugins.legend.labels },
                      tooltip: { ...commonOpts.plugins.tooltip, displayColors:false, callbacks:{ title:(items)=>decorated[items?.[0]?.dataIndex ?? 0], label:()=>'' } }
                    }
                  }
                });
                // Helper for later updates
                this._updateGender = (payload) => {
                  decorated = decorate(payload);
                  this.chart.data.labels = decorated;
                  this.chart.data.datasets[0].data = payload.data || [];
                  this.chart.update('none');
                };
                return;
              }

              if (type === 'education' || type === 'district') {
                this.chart = new Chart(ctx, {
                  type: 'bar',
                  data: { labels: data.labels || [], datasets: [{ label: 'Jumlah', data: data.data || [], backgroundColor: type==='education' ? '#6366f1' : '#10b981' }] },
                  options: { ...commonOpts, animation: barAnimation, indexAxis:'y', scales:{ x:{ grid:{ color:gridColor }, ticks:{ color:textColor, font:{ size:12 } } }, y:{ grid:{ color:gridColor }, ticks:{ color:textColor, font:{ size:12 } } } } }
                });
                return;
              }

              if (type === 'monthly') {
                this.chart = new Chart(ctx, {
                  type: 'bar',
                  data: { labels: data.labels || [], datasets: [{ label: 'Disetujui', data: data.data || [], backgroundColor: '#3b82f6' }] },
                  options: { ...commonOpts, animation: barAnimation, scales:{ x:{ grid:{ color:gridColor }, ticks:{ color:textColor, maxRotation:45, minRotation:35, font:{ size:12 } } }, y:{ grid:{ color:gridColor }, ticks:{ color:textColor, precision:0, font:{ size:12 } } } } }
                });
                return;
              }
            }
          }
        }
      </script>
    @endpush
  @endonce
</div>
