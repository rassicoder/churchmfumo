(function () {
    const state = { churchId: null, role: null };

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

    async function loadProjects() {
        const tbody = document.getElementById('projectsList');
        const empty = document.getElementById('projectsEmpty');
        if (!tbody) return;
        const params = new URLSearchParams();
        params.set('per_page', '20');
        if (state.churchId) params.set('church_id', state.churchId);
        const data = await fetchJson('/api/v1/projects?' + params.toString());
        const items = data && data.data ? data.data : [];
        if (!items.length) {
            tbody.innerHTML = '';
            if (empty) empty.classList.remove('hidden');
            return;
        }
        if (empty) empty.classList.add('hidden');
        tbody.innerHTML = '';
        items.forEach(function (project) {
            tbody.insertAdjacentHTML('beforeend',
                '<tr>'
                + '<td class="py-2">' + (project.name || '-') + '</td>'
                + '<td class="py-2">' + (project.status || '-') + '</td>'
                + '<td class="py-2">' + (project.budget || '-') + '</td>'
                + '<td class="py-2">' + (project.progress != null ? project.progress + '%' : '-') + '</td>'
                + '</tr>'
            );
        });
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
        await loadProjects();
    }

    document.addEventListener('DOMContentLoaded', init);
})();
