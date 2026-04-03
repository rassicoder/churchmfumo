(function () {
    const state = { churchId: null, role: null };

    function ensureAuth(res) {
        if (res.status === 401) {
            localStorage.removeItem('api_token');
            window.location.href = '/admin/login';
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

    function renderRows(items) {
        const tbody = document.getElementById('leadersList');
        const empty = document.getElementById('leadersEmpty');
        if (!tbody) return;
        if (!items.length) {
            tbody.innerHTML = '';
            if (empty) empty.classList.remove('hidden');
            return;
        }
        if (empty) empty.classList.add('hidden');
        tbody.innerHTML = '';
        items.forEach(function (leader) {
            tbody.insertAdjacentHTML('beforeend',
                '<tr>'
                + '<td class="py-2">' + (leader.full_name || leader.name || '-') + '</td>'
                + '<td class="py-2">' + (leader.position || '-') + '</td>'
                + '<td class="py-2">' + (leader.phone || '-') + '</td>'
                + '<td class="py-2">' + (leader.email || '-') + '</td>'
                + '</tr>'
            );
        });
    }

    async function loadLeaders(query) {
        const params = new URLSearchParams();
        params.set('per_page', '20');
        if (state.churchId) params.set('church_id', state.churchId);
        if (query) params.set('search', query);
        const data = await fetchJson('/api/v1/leaders?' + params.toString());
        const items = data && data.data ? data.data : [];
        renderRows(items);
    }

    async function init() {
        if (typeof AdminApp === 'undefined') return;
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
        await loadLeaders('');
        const search = document.getElementById('leaderSearch');
        if (search) {
            let timer = null;
            search.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    loadLeaders(search.value.trim()).catch(function () {});
                }, 300);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', init);
})();
