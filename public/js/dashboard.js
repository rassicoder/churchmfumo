(function () {
    const chartRegistry = {};

    function getToken() {
        return localStorage.getItem('api_token') || '';
    }

    function fetchWithAuth(token, url, options) {
        if (window.AdminApp && typeof AdminApp.fetchWithAuth === 'function') {
            return AdminApp.fetchWithAuth(token, url, options);
        }
        const headers = Object.assign({ 'Accept': 'application/json' }, options && options.headers ? options.headers : {});
        if (token) headers.Authorization = 'Bearer ' + token;
        return fetch(url, Object.assign({}, options || {}, { headers }));
    }

    async function requestJson(url, options) {
        const token = getToken();
        if (!token) {
            if (window.AdminApp && typeof AdminApp.redirectToLogin === 'function') {
                AdminApp.redirectToLogin();
            } else {
                window.location.href = '/admin/login';
            }
            throw new Error('Missing token');
        }
        const res = await fetchWithAuth(token, url, options);
        if (res.status === 401) {
            localStorage.removeItem('api_token');
            if (window.AdminApp && typeof AdminApp.redirectToLogin === 'function') {
                AdminApp.redirectToLogin();
            } else {
                window.location.href = '/admin/login';
            }
            throw new Error('Unauthorized');
        }
        if (res.status === 403) {
            const error = new Error('Unauthorized');
            error.status = 403;
            throw error;
        }
        if (!res.ok) {
            let message = 'Request failed';
            try {
                const data = await res.json();
                message = data.message || message;
            } catch (err) {
                message = await res.text();
            }
            if (res.status >= 500) message = 'Server error. Please try again.';
            const error = new Error(message);
            error.status = res.status;
            throw error;
        }
        return res.json();
    }

    async function loadCurrentUser() {
        const data = await requestJson('/api/v1/auth/me');
        const payload = data.data || {};
        const user = payload.user || payload;
        const role = payload.role || (payload.roles ? payload.roles[0] : null);
        const roles = Array.isArray(payload.roles) ? payload.roles : (role ? [role] : []);
        const churchId = payload.church_id || user.church_id || null;
        return { user: user, role: role, roles: roles, churchId: churchId };
    }

    function applyRoleUI() {
        const churchesCard = document.getElementById('totalChurchesCard');
        if (churchesCard) {
            churchesCard.classList.remove('d-none');
        }
    }

    function setLoading(isLoading) {
        document.body.classList.toggle('is-loading', isLoading);
        const loader = document.getElementById('dashboardLoading');
        if (loader) loader.style.display = isLoading ? 'flex' : 'none';
    }

    function toast(message, type) {
        if (window.AdminApp && typeof AdminApp.showToast === 'function') {
            AdminApp.showToast(message, type);
            return;
        }
        console.warn(message);
    }

    function setDashboardError(message) {
        const el = document.getElementById('dashboardError');
        if (!el) return;
        if (message) {
            el.textContent = message;
            el.classList.remove('d-none');
        } else {
            el.textContent = '';
            el.classList.add('d-none');
        }
    }

    function extractPayload(data) {
        if (!data) return null;
        if (data.data) return data.data;
        return data;
    }

    function extractItems(data) {
        if (!data) return [];
        if (Array.isArray(data)) return data;
        if (Array.isArray(data.data)) return data.data;
        if (data.data && Array.isArray(data.data.data)) return data.data.data;
        return [];
    }

    function normalizeNumber(value) {
        const parsed = Number(value);
        if (Number.isFinite(parsed)) return parsed;
        return 0;
    }

    function animateCount(el, target) {
        const start = Number(String(el.textContent || '0').replace(/[^0-9.-]/g, '')) || 0;
        const duration = 800;
        const startTime = performance.now();

        function tick(now) {
            const progress = Math.min(1, (now - startTime) / duration);
            const value = Math.round(start + (target - start) * progress);
            el.textContent = value.toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    function setStat(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        const safeValue = normalizeNumber(value);
        animateCount(el, safeValue);
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

    function formatRelativeTime(dateValue) {
        if (!dateValue) return 'Just now';
        const date = new Date(dateValue);
        if (Number.isNaN(date.getTime())) return String(dateValue);
        const diff = Date.now() - date.getTime();
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        if (days >= 2) return days + ' days ago';
        if (days === 1) return 'Yesterday';
        if (hours >= 1) return hours + ' hours ago';
        if (minutes >= 1) return minutes + ' minutes ago';
        return 'Just now';
    }

    function activityIcon(action) {
        const label = String(action || '').toLowerCase();
        if (label.includes('leader')) return 'bi-person-plus';
        if (label.includes('meeting')) return 'bi-calendar-event';
        if (label.includes('project')) return 'bi-kanban';
        if (label.includes('finance') || label.includes('budget')) return 'bi-cash-coin';
        return 'bi-bell';
    }

    function alertBadgeType(item) {
        const label = String(item.status || item.type || item.category || '').toLowerCase();
        if (label.includes('overdue')) return { text: 'Overdue', cls: 'text-bg-danger' };
        if (label.includes('upcoming')) return { text: 'Upcoming', cls: 'text-bg-warning' };
        if (label.includes('attention') || label.includes('expiring')) return { text: 'Attention', cls: 'text-bg-primary' };
        return { text: 'Info', cls: 'text-bg-info' };
    }

    async function loadStats() {
        try {
            const data = await requestJson('/api/v1/dashboards/summary');
            const payload = extractPayload(data) || {};
            const totals = payload.totals || payload;
            setStat('totalChurches', totals.total_churches || totals.churches || 0);
            setStat('totalLeaders', totals.total_leaders || totals.leaders || 0);
            setStat('totalMeetings', totals.total_meetings || totals.meetings || 0);
            setStat('totalProjects', totals.total_projects || totals.projects || totals.active_projects || 0);
        } catch (error) {
            setStat('totalChurches', 0);
            setStat('totalLeaders', 0);
            setStat('totalMeetings', 0);
            setStat('totalProjects', 0);
            throw error;
        }
    }

    async function loadChurchPerformanceChart() {
        const canvas = document.getElementById('churchPerformanceChart');
        if (!canvas || typeof Chart === 'undefined') return;
        try {
            const data = await requestJson('/api/v1/churches');
            const items = extractItems(data);
            if (!items.length) {
                showChartPlaceholder('churchPerformanceChart', 'No church data');
                return;
            }
            const labels = [];
            const values = [];
            items.slice(0, 8).forEach(function (item) {
                labels.push(item.name || 'Church');
                const count = item.leaders_count || item.leader_count || (item.leaders ? item.leaders.length : 0) || item.total_leaders || 0;
                values.push(normalizeNumber(count));
            });

            if (chartRegistry.churches) chartRegistry.churches.destroy();
            chartRegistry.churches = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Leaders',
                        data: values,
                        backgroundColor: 'rgba(43, 78, 255, 0.85)',
                        borderRadius: 10,
                        barThickness: 24,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#e5e9f2' }, ticks: { color: '#5f6b7a' } },
                        x: { grid: { display: false }, ticks: { color: '#5f6b7a' } }
                    }
                }
            });
            hideChartPlaceholder('churchPerformanceChart');
        } catch (error) {
            const message = error && error.status === 403 ? 'Unauthorized' : 'Failed to load chart';
            showChartPlaceholder('churchPerformanceChart', message);
            throw error;
        }
    }

    function normalizeFinanceSeries(payload) {
        if (!payload) return null;
        if (Array.isArray(payload)) {
            return {
                labels: payload.map(function (row) { return row.month || row.label || row.period || '-'; }),
                budgets: payload.map(function (row) { return normalizeNumber(row.budget || row.budgets || row.planned || 0); }),
                expenses: payload.map(function (row) { return normalizeNumber(row.expense || row.expenses || row.actual || 0); })
            };
        }
        if (Array.isArray(payload.months)) {
            return {
                labels: payload.months,
                budgets: (payload.budgets || payload.budget || []).map(normalizeNumber),
                expenses: (payload.expenses || payload.expense || payload.actual || []).map(normalizeNumber)
            };
        }
        if (Array.isArray(payload.labels)) {
            return {
                labels: payload.labels,
                budgets: (payload.budgets || []).map(normalizeNumber),
                expenses: (payload.expenses || []).map(normalizeNumber)
            };
        }
        return null;
    }

    async function loadFinanceChart() {
        const canvas = document.getElementById('financialTrendChart');
        if (!canvas || typeof Chart === 'undefined') return;
        try {
            const data = await requestJson('/api/v1/finance/summary');
            const payload = extractPayload(data);
            const series = normalizeFinanceSeries(payload);
            if (!series || !series.labels.length) {
                showChartPlaceholder('financialTrendChart', 'No finance data');
                return;
            }

            const datasets = [];
            if (series.budgets && series.budgets.length) {
                datasets.push({
                    label: 'Budget',
                    data: series.budgets,
                    borderColor: 'rgba(43, 78, 255, 0.9)',
                    backgroundColor: 'rgba(43, 78, 255, 0.15)',
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'rgba(43, 78, 255, 0.9)',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.4,
                });
            }
            if (series.expenses && series.expenses.length) {
                datasets.push({
                    label: 'Expenses',
                    data: series.expenses,
                    borderColor: 'rgba(245, 180, 0, 0.95)',
                    backgroundColor: 'rgba(245, 180, 0, 0.2)',
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'rgba(245, 180, 0, 0.95)',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.4,
                });
            }

            if (!datasets.length) {
                showChartPlaceholder('financialTrendChart', 'No finance data');
                return;
            }

            if (chartRegistry.finance) chartRegistry.finance.destroy();
            chartRegistry.finance = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: series.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: true, position: 'bottom' } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#e5e9f2' }, ticks: { color: '#5f6b7a' } },
                        x: { grid: { display: false }, ticks: { color: '#5f6b7a' } }
                    }
                }
            });
            hideChartPlaceholder('financialTrendChart');
        } catch (error) {
            const message = error && error.status === 403 ? 'Unauthorized' : 'Failed to load chart';
            showChartPlaceholder('financialTrendChart', message);
            throw error;
        }
    }

    async function loadActivityFeed() {
        const container = document.getElementById('activityFeed');
        if (!container) return;
        try {
            const data = await requestJson('/api/v1/admin/activity-logs');
            const items = extractItems(data);
            container.innerHTML = '';
            if (!items.length) {
                container.innerHTML = '<div class="text-muted small">No recent activity</div>';
                return;
            }
            items.slice(0, 6).forEach(function (item) {
                const title = item.title || item.action || 'Activity';
                const description = item.description || item.details || item.table || '-';
                const time = formatRelativeTime(item.created_at || item.timestamp);
                const icon = activityIcon(title);
                container.insertAdjacentHTML('beforeend',
                    '<div class="d-flex align-items-center gap-3">'
                    + '<div class="icon-pill"><i class="bi ' + icon + '"></i></div>'
                    + '<div>'
                    + '<div class="fw-semibold">' + title + '</div>'
                    + '<div class="text-muted small">' + description + '</div>'
                    + '</div>'
                    + '<div class="ms-auto text-muted small">' + time + '</div>'
                    + '</div>'
                );
            });
        } catch (error) {
            const message = error && error.status === 403 ? 'Unauthorized' : (error && error.message ? error.message : 'Failed to load activity');
            container.innerHTML = '<div class="text-muted small">' + message + '</div>';
            throw error;
        }
    }

    async function loadAlertsFeed() {
        const container = document.getElementById('alertsFeed');
        if (!container) return;
        try {
            const data = await requestJson('/api/v1/action-items');
            const items = extractItems(data);
            container.innerHTML = '';
            if (!items.length) {
                container.innerHTML = '<div class="text-muted small">No alerts right now</div>';
                return;
            }
            items.slice(0, 5).forEach(function (item) {
                const title = item.title || item.name || 'Action item';
                const message = item.description || item.message || 'Requires attention';
                const badge = alertBadgeType(item);
                const time = formatRelativeTime(item.due_at || item.updated_at || item.created_at);
                container.insertAdjacentHTML('beforeend',
                    '<div class="border rounded-3 p-3 d-flex align-items-start gap-3">'
                    + '<div class="icon-pill"><i class="bi bi-exclamation-circle"></i></div>'
                    + '<div class="flex-grow-1">'
                    + '<div class="fw-semibold">' + title + '</div>'
                    + '<div class="text-muted small">' + message + '</div>'
                    + '<div class="d-flex align-items-center gap-2 mt-2">'
                    + '<span class="badge ' + badge.cls + '">' + badge.text + '</span>'
                    + '<span class="text-muted small">' + time + '</span>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                );
            });
        } catch (error) {
            const message = error && error.status === 403 ? 'Unauthorized' : (error && error.message ? error.message : 'Failed to load alerts');
            container.innerHTML = '<div class="text-muted small">' + message + '</div>';
            throw error;
        }
    }

    async function loadAll() {
        setLoading(true);
        setDashboardError('');
        try {
            await loadCurrentUser();
            applyRoleUI();
            const tasks = [
                loadStats(),
                loadChurchPerformanceChart(),
                loadFinanceChart(),
                loadAlertsFeed(),
            ];
            if (userInfo.role !== 'Church Admin') {
                tasks.push(loadActivityFeed());
            }
            const results = await Promise.allSettled(tasks);
            const rejected = results.find(function (item) { return item.status === 'rejected'; });
            if (rejected) {
                const error = rejected.reason || {};
                if (error.status === 403) {
                    setDashboardError('');
                    return;
                }
                const message = error.message || 'Failed to load dashboard';
                toast(message, 'error');
                setDashboardError(message);
            }
        } finally {
            setLoading(false);
        }
    }

    function init() {
        const refreshBtn = document.getElementById('dashboardRefresh');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function () {
                loadAll().catch(function () {});
            });
        }
        loadAll().catch(function () {});
    }

    document.addEventListener('DOMContentLoaded', init);
})();
