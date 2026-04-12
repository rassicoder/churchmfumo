(function () {
    function getToken() {
        return localStorage.getItem('api_token') || '';
    }

    function setToken(token) {
        if (token) {
            localStorage.setItem('api_token', token);
        }
    }

    function clearToken() {
        localStorage.removeItem('api_token');
        localStorage.removeItem('current_user');
        localStorage.removeItem('user');
    }

    function getStoredCurrentUser() {
        const cached = localStorage.getItem('current_user');
        if (!cached) return null;
        try {
            return JSON.parse(cached);
        } catch (e) {
            return null;
        }
    }

    function resolveLoginPath() {
        const profile = getStoredCurrentUser();
        const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
        const path = window.location.pathname || '';

        if (String(role || '').toLowerCase() === 'church admin') {
            return '/church/login';
        }

        if (
            path === '/admin/church-dashboard' ||
            path.startsWith('/admin/church/') ||
            path.startsWith('/admin/church-')
        ) {
            return '/church/login';
        }

        return '/admin/login';
    }

    function redirectToLogin() {
        window.location.href = resolveLoginPath();
    }

    function showToast(message, type) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        const variant = type || 'info';
        toast.className = 'toast-message toast-' + variant;
        toast.innerHTML = '<span class="dot"></span><div>' + message + '</div>';
        container.appendChild(toast);
        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s ease';
            setTimeout(function () {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 450);
        }, 3500);
    }

    function showErrorBanner(errors) {
        const banner = document.getElementById('errorBanner');
        const list = document.getElementById('errorBannerList');
        if (!banner || !list) return;
        if (!errors || (Array.isArray(errors) && errors.length === 0) || errors === true) {
            banner.classList.add('hidden');
            list.innerHTML = '';
            return;
        }
        list.innerHTML = '';
        const items = [];
        if (typeof errors === 'string') {
            items.push(errors);
        } else if (Array.isArray(errors)) {
            errors.forEach(function (msg) {
                if (msg) items.push(String(msg));
            });
        } else if (typeof errors === 'object') {
            Object.keys(errors).forEach(function (key) {
                const value = errors[key];
                if (Array.isArray(value)) {
                    value.forEach(function (msg) {
                        if (msg) items.push(String(msg));
                    });
                } else if (value) {
                    items.push(String(value));
                }
            });
        }
        if (!items.length) {
            banner.classList.add('hidden');
            return;
        }
        items.forEach(function (message) {
            const li = document.createElement('li');
            li.textContent = message;
            list.appendChild(li);
        });
        banner.classList.remove('hidden');
    }

    function fetchWithAuth(arg1, arg2, arg3) {
        let token = '';
        let url = '';
        let options = {};
        if (typeof arg1 === 'string' && (arg1.startsWith('/') || arg1.startsWith('http'))) {
            url = arg1;
            options = arg2 || {};
            token = getToken();
        } else {
            token = arg1;
            url = arg2;
            options = arg3 || {};
        }
        const headers = Object.assign({ 'Accept': 'application/json', 'Content-Type': 'application/json' }, options && options.headers ? options.headers : {});
        if (token) headers['Authorization'] = 'Bearer ' + token;
        return fetch(url, Object.assign({}, options || {}, { headers }));
    }

    async function apiRequest(url, options) {
        const token = getToken();
        const res = await fetchWithAuth(token, url, options);
        if (res.status === 401) {
            clearToken();
            redirectToLogin();
            const authError = new Error('Unauthorized');
            authError.status = 401;
            throw authError;
        }
        if (res.status === 403) {
            const forbidden = new Error('Unauthorized');
            forbidden.status = 403;
            throw forbidden;
        }
        if (!res.ok) {
            let message = 'Request failed';
            try {
                const data = await res.json();
                message = data.message || message;
            } catch (e) {
                message = await res.text();
            }
            if (res.status >= 500) {
                message = 'Server error. Please try again.';
            }
            const err = new Error(message);
            err.status = res.status;
            throw err;
        }
        return res.json();
    }

    function apiGet(url) {
        return apiRequest(url);
    }

    function apiPost(url, payload) {
        return apiRequest(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
    }

    function apiPut(url, payload) {
        return apiRequest(url, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
    }

    function apiDelete(url) {
        return apiRequest(url, { method: 'DELETE' });
    }

    async function login(email, password) {
        localStorage.removeItem('user');
        localStorage.removeItem('current_user');
        const payload = { email: email, password: password };
        const data = await apiPost('/api/v1/auth/login', payload);
        if (data && data.data && data.data.token) {
            setToken(data.data.token);
            try {
                const me = await apiGet('/api/v1/auth/me');
                localStorage.setItem('current_user', JSON.stringify(me.data || {}));
                return me.data || {};
            } catch (e) {
                // ignore profile fetch errors
            }
        }
        return data;
    }

    async function logout() {
        try {
            await apiRequest('/api/v1/auth/logout', { method: 'POST' });
        } catch (e) {
            // Ignore logout errors, still clear token.
        }
        clearToken();
        redirectToLogin();
    }

    function ensureAuth() {
        if (!getToken()) {
            redirectToLogin();
        }
    }

    async function getCurrentUser() {
        const cached = localStorage.getItem('current_user');
        if (cached) {
            try {
                return JSON.parse(cached);
            } catch (e) {
                // ignore parse
            }
        }
        const data = await apiGet('/api/v1/auth/me');
        localStorage.setItem('current_user', JSON.stringify(data.data || {}));
        return data.data || {};
    }

    function redirectNotAllowed(targetUrl) {
        showToast('You are not allowed to access this page', 'error');
        setTimeout(function () {
            window.location.href = targetUrl || (resolveLoginPath() === '/church/login' ? '/admin/church-dashboard' : '/admin/dashboard');
        }, 700);
    }

    async function loadDashboardStats() {
        const data = await apiRequest('/api/v1/dashboards/association');
        if (!data || !data.data || !data.data.totals) return;
        const totals = data.data.totals;
        setText('stat-churches', totals.churches);
        setText('stat-leaders', totals.leaders);
        setText('stat-projects', totals.active_projects);
        setText('stat-overdue', totals.overdue_actions);
    }

    function statusBadge(status) {
        const text = status ? String(status) : 'Unknown';
        const normalized = String(status || '').toLowerCase();
        let cls = 'text-bg-secondary';
        if (normalized === 'active') cls = 'text-bg-success';
        if (normalized === 'pending') cls = 'text-bg-warning';
        if (normalized === 'overdue') cls = 'text-bg-danger';
        return '<span class="badge ' + cls + ' badge-status">' + text + '</span>';
    }

    function parsePaginated(data) {
        if (!data) return { items: [], page: 1, totalPages: 1, total: 0, perPage: 10, from: 0, to: 0 };
        if (Array.isArray(data.data)) {
            if (data.meta && typeof data.meta === 'object') {
                return {
                    items: data.data,
                    page: data.meta.current_page || 1,
                    totalPages: data.meta.last_page || 1,
                    total: data.meta.total || data.data.length,
                    perPage: data.meta.per_page || data.data.length || 10,
                    from: data.meta.from || 0,
                    to: data.meta.to || data.data.length || 0,
                };
            }
            return {
                items: data.data,
                page: 1,
                totalPages: 1,
                total: data.data.length,
                perPage: data.data.length || 10,
                from: data.data.length ? 1 : 0,
                to: data.data.length,
            };
        }
        if (data.data && Array.isArray(data.data.data) && data.data.meta) {
            return {
                items: data.data.data,
                page: data.data.meta.current_page || 1,
                totalPages: data.data.meta.last_page || 1,
                total: data.data.meta.total || data.data.data.length,
                perPage: data.data.meta.per_page || data.data.data.length || 10,
                from: data.data.meta.from || 0,
                to: data.data.meta.to || data.data.data.length || 0,
            };
        }
        return { items: [], page: 1, totalPages: 1, total: 0, perPage: 10, from: 0, to: 0 };
    }

    function updatePager(tableId, meta) {
        const summary = document.querySelector('[data-table-summary="' + tableId + '"]');
        const prev = document.querySelector('[data-table-prev="' + tableId + '"]');
        const next = document.querySelector('[data-table-next="' + tableId + '"]');
        const pageLabel = document.querySelector('[data-table-page="' + tableId + '"]');
        const pagesWrap = document.querySelector('[data-table-pages="' + tableId + '"]');

        const page = meta.page || 1;
        const totalPages = meta.totalPages || 1;
        const total = meta.total || 0;
        const from = meta.from || (total ? (page - 1) * (meta.perPage || 10) + 1 : 0);
        const to = meta.to || Math.min(total, page * (meta.perPage || 10));

        if (summary) summary.textContent = 'Showing ' + from + '–' + to + ' of ' + total + ' results';
        if (pageLabel) pageLabel.textContent = page;
        if (prev) prev.disabled = page <= 1;
        if (next) next.disabled = page >= totalPages;

        if (pagesWrap) {
            pagesWrap.innerHTML = '';
            const totalToShow = Math.min(totalPages, 5);
            const start = Math.max(1, Math.min(page - 2, totalPages - totalToShow + 1));
            const end = Math.min(totalPages, start + totalToShow - 1);
            for (let i = start; i <= end; i += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm ' + (i === page ? 'btn-primary' : 'btn-outline-secondary');
                btn.textContent = String(i);
                btn.addEventListener('click', function () {
                    const event = new CustomEvent('table:page', { detail: { page: i } });
                    pagesWrap.dispatchEvent(event);
                });
                pagesWrap.appendChild(btn);
            }
        }
    }

    function initTable(options) {
        const tableId = options.tableId;
        const endpoint = options.endpoint;
        const mapRow = options.mapRow;
        const searchParam = options.searchParam || 'search';
        const pageParam = options.pageParam || 'page';
        const perPageParam = options.perPageParam || 'per_page';
        const perPage = options.perPage || 10;
        const emptyMessage = options.emptyMessage || 'No data found';

        const table = document.getElementById(tableId);
        if (!table) return { reload: function () {} };
        const tbody = table.querySelector('tbody');
        const searchInput = document.querySelector('[data-table-search="' + tableId + '"]');
        const prevBtn = document.querySelector('[data-table-prev="' + tableId + '"]');
        const nextBtn = document.querySelector('[data-table-next="' + tableId + '"]');
        const pagesWrap = document.querySelector('[data-table-pages="' + tableId + '"]');
        const columnCount = table.querySelectorAll('thead th').length || 1;

        const state = { page: 1, search: '' };

        async function load() {
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="' + columnCount + '" class="text-muted">Loading...</td></tr>';
            }
            const url = new URL(endpoint, window.location.origin);
            url.searchParams.set(pageParam, state.page);
            url.searchParams.set(perPageParam, perPage);
            if (state.search) url.searchParams.set(searchParam, state.search);

            try {
                const data = await apiRequest(url.toString());
                const parsed = parsePaginated(data);
                updatePager(tableId, parsed);

                if (!tbody) return;
                tbody.innerHTML = '';
                if (!parsed.items.length) {
                    tbody.innerHTML = '<tr><td colspan="' + columnCount + '" class="text-muted">' + emptyMessage + '</td></tr>';
                    return;
                }
                parsed.items.forEach(function (row) {
                    tbody.insertAdjacentHTML('beforeend', mapRow(row));
                });
            } catch (err) {
                if (!tbody) return;
                const message = err && err.status === 403 ? 'Unauthorized' : (err && err.message ? err.message : 'Failed to load data');
                tbody.innerHTML = '<tr><td colspan="' + columnCount + '" class="text-muted">' + message + '</td></tr>';
                showToast(message, 'error');
            }

        }

        if (searchInput) {
            let timer = null;
            searchInput.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    state.search = searchInput.value.trim();
                    state.page = 1;
                    load().catch(function () {});
                }, 300);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                if (state.page > 1) {
                    state.page -= 1;
                    load().catch(function () {});
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                state.page += 1;
                load().catch(function () {});
            });
        }

        if (pagesWrap) {
            pagesWrap.addEventListener('table:page', function (event) {
                if (!event.detail || !event.detail.page) return;
                state.page = event.detail.page;
                load().catch(function () {});
            });
        }

        load().catch(function () {});

        return { reload: load };
    }

    async function initSelect(options) {
        const select = document.getElementById(options.selectId);
        if (!select) return;
        const endpoint = options.endpoint;
        const valueKey = options.valueKey || 'id';
        const labelKey = options.labelKey || 'name';
        const placeholder = options.placeholder || 'Select';

        select.innerHTML = '<option value="">' + placeholder + '</option>';
        try {
            const data = await apiGet(endpoint);
            const parsed = parsePaginated(data);
            parsed.items.forEach(function (item) {
                const opt = document.createElement('option');
                opt.value = item[valueKey] || '';
                opt.textContent = item[labelKey] || '-';
                select.appendChild(opt);
            });
        } catch (e) {
            // leave select with placeholder
        }
    }

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    function initThemeToggle() {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        btn.addEventListener('click', function () {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('admin_theme', next);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initThemeToggle();
        const logoutButtons = document.querySelectorAll('[data-logout]');
        logoutButtons.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                logout();
            });
        });
    });

    window.AdminApp = {
        login: login,
        logout: logout,
        ensureAuth: ensureAuth,
        loadDashboardStats: loadDashboardStats,
        initTable: initTable,
        statusBadge: statusBadge,
        apiGet: apiGet,
        apiPost: apiPost,
        apiPut: apiPut,
        apiDelete: apiDelete,
        initSelect: initSelect,
        showToast: showToast,
        showErrorBanner: showErrorBanner,
        fetchWithAuth: fetchWithAuth,
        getCurrentUser: getCurrentUser,
        redirectNotAllowed: redirectNotAllowed,
        resolveLoginPath: resolveLoginPath,
        redirectToLogin: redirectToLogin,
    };
})();
