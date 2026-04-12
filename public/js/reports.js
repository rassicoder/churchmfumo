(function () {
    const charts = {};
    const state = {
        role: null,
        churchId: null,
        range: 'year',
        overview: null,
        leaderGrowth: null,
        churchGrowth: null,
        finance: null,
    };

    function fetchJson(url) {
        return AdminApp.fetchWithAuth(url, {}).then(function (res) {
            if (res.status === 401) {
                localStorage.removeItem('api_token');
                if (window.AdminApp && typeof AdminApp.redirectToLogin === 'function') {
                    AdminApp.redirectToLogin();
                } else {
                    window.location.href = '/admin/login';
                }
                return null;
            }
            if (res.status === 403) {
                AdminApp.showToast('Unauthorized', 'error');
                return null;
            }
            return res.json();
        });
    }

    function hideChartPlaceholder(canvasId) {
        const placeholder = document.querySelector('[data-chart-placeholder="' + canvasId + '"]');
        if (placeholder) placeholder.style.display = 'none';
    }

    function showChartPlaceholder(canvasId, text) {
        const placeholder = document.querySelector('[data-chart-placeholder="' + canvasId + '"]');
        if (placeholder) {
            placeholder.textContent = text || 'No data available';
            placeholder.style.display = 'flex';
        }
    }

    function setStat(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = Number(value || 0).toLocaleString();
    }

    function getFilters() {
        const range = document.getElementById('reportRange').value;
        const churchSelect = document.getElementById('reportChurch');
        const churchId = churchSelect && !churchSelect.disabled ? churchSelect.value : '';
        return { range: range, church_id: churchId || undefined };
    }

    function buildQuery(params) {
        const search = new URLSearchParams();
        Object.keys(params).forEach(function (key) {
            if (params[key]) search.set(key, params[key]);
        });
        return search.toString();
    }

    async function loadCurrentUser() {
        const data = await fetchJson('/api/v1/auth/me');
        if (!data) return;
        const payload = data.data || {};
        const role = payload.role || (payload.roles ? payload.roles[0] : null);
        state.role = role;
        state.churchId = payload.church_id || (payload.user ? payload.user.church_id : null);

        const churchFilterWrap = document.getElementById('churchFilterWrap');
        const churchCard = document.getElementById('reportChurchesCard');
        const churchGrowthCard = document.getElementById('churchGrowthCard');
        if (role === 'Church Admin') {
            if (churchFilterWrap) churchFilterWrap.classList.add('d-none');
            if (churchCard) churchCard.classList.add('d-none');
            if (churchGrowthCard) churchGrowthCard.classList.add('d-none');
        } else {
            if (churchFilterWrap) churchFilterWrap.classList.remove('d-none');
            if (churchCard) churchCard.classList.remove('d-none');
            if (churchGrowthCard) churchGrowthCard.classList.remove('d-none');
        }
    }

    async function loadChurchOptions() {
        const select = document.getElementById('reportChurch');
        if (!select) return;
        select.innerHTML = '<option value="">All churches</option>';
        if (state.role === 'Church Admin') {
            select.disabled = true;
            return;
        }
        const data = await fetchJson('/api/v1/churches?per_page=100');
        const items = (data && data.data && data.data.data) ? data.data.data : (data.data || []);
        items.forEach(function (church) {
            const opt = document.createElement('option');
            opt.value = church.id || '';
            opt.textContent = church.name || '-';
            select.appendChild(opt);
        });
    }

    async function loadOverview() {
        const params = getFilters();
        const query = buildQuery(params);
        const data = await fetchJson('/api/v1/reports/overview?' + query);
        if (!data) return;
        state.overview = data.data || {};
        const totals = state.overview.totals || {};
        setStat('reportLeaders', totals.leaders || 0);
        setStat('reportMeetings', totals.meetings || 0);
        setStat('reportProjects', totals.projects || 0);
        setStat('reportChurches', totals.churches || 0);
    }

    async function loadLeaderGrowth() {
        const series = state.overview && state.overview.leader_growth ? state.overview.leader_growth : null;
        if (!series) {
            const params = getFilters();
            const query = buildQuery(params);
            const data = await fetchJson('/api/v1/reports/overview?' + query);
            if (!data) return;
            state.overview = data.data || {};
        }
        const resolved = state.overview && state.overview.leader_growth ? state.overview.leader_growth : null;
        if (!resolved || !resolved.labels.length) {
            showChartPlaceholder('leaderGrowthChart', 'No data');
            return;
        }

        const ctx = document.getElementById('leaderGrowthChart');
        if (charts.leader) charts.leader.destroy();
        charts.leader = new Chart(ctx, {
            type: 'line',
            data: {
                labels: resolved.labels,
                datasets: [{
                    label: 'Leader Growth',
                    data: resolved.data,
                    borderColor: 'rgba(43, 78, 255, 0.9)',
                    backgroundColor: 'rgba(43, 78, 255, 0.2)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
        hideChartPlaceholder('leaderGrowthChart');
    }

    async function loadChurchGrowth() {
        if (state.role === 'Church Admin') {
            showChartPlaceholder('churchGrowthChart', 'Restricted to super admins');
            return;
        }
        const params = getFilters();
        const query = buildQuery(params);
        const data = await fetchJson('/api/v1/reports/church-growth?' + query);
        if (!data) return;
        const series = data.data || { labels: [], data: [] };
        if (!series.labels.length) {
            showChartPlaceholder('churchGrowthChart', 'No data');
            return;
        }
        const ctx = document.getElementById('churchGrowthChart');
        if (charts.church) charts.church.destroy();
        charts.church = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: series.labels,
                datasets: [{
                    label: 'Growth',
                    data: series.data,
                    backgroundColor: 'rgba(245, 180, 0, 0.8)',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
        hideChartPlaceholder('churchGrowthChart');
    }

    async function loadFinance() {
        const params = getFilters();
        const query = buildQuery(params);
        const data = await fetchJson('/api/v1/reports/finance?' + query);
        if (!data) return;
        const series = data.data || { labels: [], budgets: [], expenses: [] };
        if (!series.labels.length) {
            showChartPlaceholder('financeReportChart', 'No data');
            return;
        }
        state.finance = series;
        const ctx = document.getElementById('financeReportChart');
        if (charts.finance) charts.finance.destroy();
        charts.finance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: series.labels,
                datasets: [
                    {
                        label: 'Budget',
                        data: series.budgets,
                        borderColor: 'rgba(43, 78, 255, 0.9)',
                        backgroundColor: 'rgba(43, 78, 255, 0.2)',
                        fill: true,
                        tension: 0.35,
                    },
                    {
                        label: 'Expenses',
                        data: series.expenses,
                        borderColor: 'rgba(245, 180, 0, 0.9)',
                        backgroundColor: 'rgba(245, 180, 0, 0.15)',
                        fill: true,
                        tension: 0.35,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
        hideChartPlaceholder('financeReportChart');
    }

    async function loadAll() {
        await loadOverview();
        await loadLeaderGrowth();
        await loadChurchGrowth();
        await loadFinance();
    }

    function exportCsv() {
        const params = getFilters();
        const query = buildQuery(params);
        const url = '/api/v1/reports/export/csv?' + query;
        AdminApp.fetchWithAuth(url, {}).then(function (res) {
            if (!res.ok) throw new Error('Export failed');
            return res.blob();
        }).then(function (blob) {
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'reports.csv';
            link.click();
        }).catch(function () {
            AdminApp.showToast('Failed to export CSV', 'error');
        });
    }

    function exportPdf() {
        const reportWindow = window.open('', '_blank');
        if (!reportWindow) return;
        const totals = (state.overview && state.overview.totals) ? state.overview.totals : {};
        reportWindow.document.write('<html><head><title>Reports</title></head><body>');
        reportWindow.document.write('<h2>Reports Summary</h2>');
        reportWindow.document.write('<ul>');
        reportWindow.document.write('<li>Leaders: ' + (totals.leaders || 0) + '</li>');
        reportWindow.document.write('<li>Meetings: ' + (totals.meetings || 0) + '</li>');
        reportWindow.document.write('<li>Projects: ' + (totals.projects || 0) + '</li>');
        reportWindow.document.write('<li>Churches: ' + (totals.churches || 0) + '</li>');
        reportWindow.document.write('</ul>');
        reportWindow.document.write('<p>Use your browser Print dialog to save as PDF.</p>');
        reportWindow.document.write('</body></html>');
        reportWindow.document.close();
        reportWindow.focus();
        reportWindow.print();
    }

    function initEvents() {
        const range = document.getElementById('reportRange');
        const church = document.getElementById('reportChurch');
        const refresh = document.getElementById('reportRefresh');
        const exportCsvBtn = document.getElementById('reportExportCsv');
        const exportPdfBtn = document.getElementById('reportExportPdf');

        if (range) range.addEventListener('change', function () { loadAll(); });
        if (church) church.addEventListener('change', function () { loadAll(); });
        if (refresh) refresh.addEventListener('click', function () { loadAll(); });
        if (exportCsvBtn) exportCsvBtn.addEventListener('click', exportCsv);
        if (exportPdfBtn) exportPdfBtn.addEventListener('click', exportPdf);
    }

    async function init() {
        if (typeof AdminApp === 'undefined' || typeof Chart === 'undefined') return;
        await loadCurrentUser();
        await loadChurchOptions();
        initEvents();
        loadAll();
    }

    document.addEventListener('DOMContentLoaded', init);
})();
