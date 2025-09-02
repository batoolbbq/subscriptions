  @extends('layouts.master')

  @section('title', 'لوحة الإحصائيات')

  @section('css')
      <style>
          body {
              font-family: "Tajawal", system-ui, -apple-system, Segoe UI, Roboto, sans-serif
          }

          .kpi-card {
              border: 0;
              background: #fff;
              border-radius: 18px;
              box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
          }

          .kpi-pill {
              width: 52px;
              height: 52px;
              border-radius: 50%;
              background: #FFE8D3;
              display: grid;
              place-items: center;
              color: #F58220;
              font-size: 22px;
          }

          .kpi-number {
              font-weight: 800;
              font-size: 28px;
              color: #111827;
          }

          .kpi-label {
              color: #6B7280;
              font-size: 13px;
          }

          .card-elev {
              border: 0;
              border-radius: 18px;
              background: #fff;
              box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
          }

          .section-title {
              font-weight: 800;
              color: #111827
          }

          .mun-card {
              margin-bottom: 10px;
              background: #fff;
              border: 1px solid #f0f0f0;
              border-radius: 16px;
              padding: 14px;
              box-shadow: 0 6px 14px rgba(0, 0, 0, .04);
              display: flex;
              align-items: center;
              justify-content: space-between;
              gap: 12px;
          }

          .mun-card .name {
              font-weight: 700;
              color: #374151
          }

          .mun-card .chip {
              background: #FFE8D3;
              color: #F58220;
              border-radius: 999px;
              padding: 4px 10px;
              font-size: 12px
          }

          /* Work categories (Bullet list) */
          .bullet-row {
              display: flex;
              align-items: center;
              gap: 12px;
              padding: 10px 0;
              border-bottom: 1px dashed #eee
          }

          .bullet-row:last-child {
              border-bottom: 0
          }

          .bullet-name {
              flex: 0 0 45%;
              font-weight: 700;
              color: #111827
          }

          .bullet-count {
              flex: 0 0 18%;
              font-weight: 800;
              color: #F58220;
              text-align: center
          }

          .bullet-pct {
              flex: 0 0 10%;
              color: #6B7280;
              font-size: 13px;
              text-align: center
          }

          .bullet-bar-wrap {
              flex: 1;
              height: 10px;
              background: #F3F4F6;
              border-radius: 999px;
              overflow: hidden
          }

          .bullet-bar {
              height: 10px;
              background: #F58220;
              border-radius: 999px
          }
      </style>
  @endsection

  {{-- ========== المحتوى ========== --}}
  @section('content')
      <div class="container-fluid px-3 px-md-4 py-4">

          <!-- KPIs -->
          <div class="row g-3 mb-3">
              <div class="col-md-3">
                  <div class="p-3 kpi-card h-100">
                      <div class="d-flex justify-content-between align-items-center">
                          <div>
                              <div class="kpi-label">إجمالي المستخدمين</div>
                              <div class="kpi-number">{{ $users }}</div>
                          </div>
                          <div class="kpi-pill"><i class="bi bi-people-fill"></i></div>
                      </div>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="p-3 kpi-card h-100">
                      <div class="d-flex justify-content-between align-items-center">
                          <div>
                              <div class="kpi-label">إجمالي المشتركين</div>
                              <div class="kpi-number">{{ $Customer }}</div>
                          </div>
                          <div class="kpi-pill"><i class="bi bi-person-vcard-fill"></i></div>
                      </div>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="p-3 kpi-card h-100">
                      <div class="d-flex justify-content-between align-items-center">
                          <div>
                              <div class="kpi-label">الوكلاء المفعّلون</div>
                              <div class="kpi-number">{{ $activeAgents }}</div>
                          </div>
                          <div class="kpi-pill"><i class="bi bi-person-check-fill"></i></div>
                      </div>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="p-3 kpi-card h-100">
                      <div class="d-flex justify-content-between align-items-center">
                          <div>
                              <div class="kpi-label">الوكلاء غير المفعّلين</div>
                              <div class="kpi-number">{{ $inactiveAgents }}</div>
                          </div>
                          <div class="kpi-pill"><i class="bi bi-person-x-fill"></i></div>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Charts -->
          <div class="row g-3">
              <!-- Health regions LINE -->
              <div class="col-lg-6">
                  <div class="card-elev p-3">
                      <div class="section-title mb-2">المشتركين حسب المنطقة الصحية</div>
                      <canvas id="regionsLine" height="220"></canvas>
                  </div>
              </div>

              <!-- Agents Doughnut + Work categories list -->
              <div class="col-lg-6">
                  <div class="card-elev p-3 text-center mb-3">
                      <div class="section-title mb-2">الوكلاء (مفعّل / غير مفعّل)</div>
                      <div style="height: 280px;">
                          <canvas id="agentsPie"></canvas>
                      </div>
                  </div>

                  <div class="card-elev p-3">
                      <div class="section-title mb-2">جهات العمل </div>
                      <div id="workcatList"></div>
                      <div class="text-muted small mt-2" id="workcatTotal"></div>
                  </div>
              </div>

              <!-- Municipalities Cards -->
              <div class="col-12">
                  <div class="card-elev p-3 m-3">
                      <div class="section-title mb-2">البلديات</div>
                      <div id="municipalCards" class="row g-3"></div>
                  </div>
              </div>
          </div>
      </div>
  @endsection

  {{-- ========== JS ========== --}}
  @section('js')
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <script>
          // بيانات من الباك-إند
          const municipals = @json($municipals);
          const cities = @json($cities);
          const workcategories = @json($workcategory);
          const activeAgents = {{ $activeAgents }};
          const inactiveAgents = {{ $inactiveAgents }};

          // 1) Health regions LINE
          new Chart(document.getElementById("regionsLine"), {
              type: "line",
              data: {
                  labels: cities.map(m => m.name),
                  datasets: [{
                      label: "عدد المشتركين",
                      data: cities.map(m => m.customer_count),
                      borderColor: "#F58220",
                      backgroundColor: "rgba(245,130,32,.15)",
                      borderWidth: 2,
                      fill: true,
                      tension: .35,
                      pointRadius: 3,
                      pointBackgroundColor: "#F58220"
                  }]
              },
              options: {
                  plugins: {
                      legend: {
                          display: false
                      }
                  },
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  }
              }
          });

          // 2) Agents DOUGHNUT
          // 2) Agents PIE (دائرة كاملة)
          new Chart(document.getElementById("agentsPie"), {
              type: "pie", // <-- بدل doughnut
              data: {
                  labels: ["مفعّل", "غير مفعّل"],
                  datasets: [{
                      data: [activeAgents, inactiveAgents],
                      backgroundColor: ["#F58220", "#FFB377"],
                      borderWidth: 1
                  }]
              },
              options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  plugins: {
                      legend: {
                          position: "bottom"
                      }
                  }
              }
          });

          // 3) Municipalities Cards
          const munWrap = document.getElementById("municipalCards");
          municipals.forEach((m) => {
              const col = document.createElement("div");
              col.className = "col-md-4";
              col.innerHTML = `
      <div class="mun-card">
        <div>
          <div class="name">${m.name || '—'}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="chip">${Number(m.customer_count||0).toLocaleString()} مشترك</span>
        </div>
      </div>`;
              munWrap.appendChild(col);
          });

          // 4) Work categories — Bullet / Progress List
          (function() {
              const container = document.getElementById('workcatList');
              const totalEl = document.getElementById('workcatTotal');

              const data = (workcategories || []).map(w => ({
                  name: w.name || 'غير مصنف',
                  count: Number(w.institucion_count || 0)
              }));

              data.sort((a, b) => b.count - a.count);
              const total = Math.max(1, data.reduce((s, d) => s + d.count, 0));
              totalEl.textContent = 'الإجمالي: ' + total.toLocaleString() + ' شركة';

              container.innerHTML = '';
              data.forEach(d => {
                  const pct = Math.round((d.count / total) * 100);
                  const row = document.createElement('div');
                  row.className = 'bullet-row';
                  row.innerHTML = `
        <div class="bullet-name">${d.name}</div>
        <div class="bullet-count">${d.count.toLocaleString()}</div>
        <div class="bullet-pct">${pct}%</div>
        <div class="bullet-bar-wrap">
          <div class="bullet-bar" style="width:${pct}%"></div>
        </div>
      `;
                  container.appendChild(row);
              });
          })();
      </script>
  @endsection
