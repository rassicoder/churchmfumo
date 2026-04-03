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

    function formatDate(value) {
        if (!value) return '-';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;
        return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
    }

    async function loadMeetings() {
        const container = document.getElementById('meetingsList');
        const empty = document.getElementById('meetingsEmpty');
        if (!container) return;
        const params = new URLSearchParams();
        params.set('per_page', '10');
        if (state.churchId) params.set('church_id', state.churchId);
        const data = await fetchJson('/api/v1/meetings?' + params.toString());
        const items = data && data.data ? data.data : [];
        if (!items.length) {
            container.innerHTML = '';
            if (empty) empty.classList.remove('hidden');
            return;
        }
        if (empty) empty.classList.add('hidden');
        container.innerHTML = '';
        items.forEach(function (meeting) {
            const title = meeting.title || meeting.meeting_type || 'Meeting';
            const date = formatDate(meeting.meeting_date || meeting.date);
            const status = meeting.status || 'Scheduled';
            container.insertAdjacentHTML('beforeend',
                '<div class="flex items-center justify-between border border-slate-200 rounded-xl p-3">'
                + '<div>'
                + '<div class="font-semibold">' + title + '</div>'
                + '<div class="text-xs text-slate-500">' + date + '</div>'
                + '</div>'
                + '<span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600">' + status + '</span>'
                + '</div>'
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
        await loadMeetings();
    }

    document.addEventListener('DOMContentLoaded', init);
})();
