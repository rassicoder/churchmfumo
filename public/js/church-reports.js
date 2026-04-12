(function () {
    let financeChart = null;
    const state = { role: null, churchId: null };

    function ensureAuth(res) {
        if (res.status === 401) {
            localStorage.removeItem('api_token');
            if (window.AdminApp && typeof AdminApp.redirectToLogin === 'function') {
                AdminApp.redirectToLogin();
            } else {
                window.location.href = '/church/login';
            }
            return false;
        }
        return true;
    }

    async function fetchJson(url) {
        const res = await AdminApp.fetchWithAuth(url, {});
        if (!ensureAuth(res)) return null;
        if (!res.ok) throw new Error('Request failed');
        return res.json();
    }

    function normalizeRole(role) {
        return String(role || '').toLowerCase();
    }

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = Number(value || 0).toLocaleString();
    }

    async function loadOverview() {
        const data = await fetchJson('/api/v1/reports/overview');
        const totals = data && data.data && data.data.totals ? data.data.totals : {};
        setText('reportLeaders', totals.leaders || 0);
        setText('reportMeetings', totals.meetings || 0);
        setText('reportProjects', totals.projects || 0);
        setText('reportDepartments', totals.departments || 0);
    }

    async function loadFinance() {
        const data = await fetchJson('/api/v1/reports/finance');
        const series = data && data.data ? data.data : { labels: [], budgets: [], expenses: [] };
        const ctx = document.getElementById('financeChart');
        if (!ctx || !series.labels.length) return;
        if (financeChart) financeChart.destroy();
        financeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: series.labels,
                datasets: [
                    {
                        label: 'Budget',
                        data: series.budgets,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderRadius: 8,
                    },
                    {
                        label: 'Expenses',
                        data: series.expenses,
                        backgroundColor: 'rgba(245, 158, 11, 0.6)',
                        borderRadius: 8,
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
    }

    async function init() {
        if (typeof AdminApp === 'undefined' || typeof Chart === 'undefined') return;
        const profile = await AdminApp.getCurrentUser();
        state.role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
        state.churchId = profile && (profile.church_id || (profile.user ? profile.user.church_id : null));
        if (normalizeRole(state.role) !== 'church admin') {
            if (window.AdminApp && typeof AdminApp.redirectNotAllowed === 'function') {
                AdminApp.redirectNotAllowed('/admin/dashboard');
            } else {
                window.location.href = '/admin/dashboard';
            }
            return;
        }
        await loadOverview();
        await loadFinance();
    }

    document.addEventListener('DOMContentLoaded', init);
})();
