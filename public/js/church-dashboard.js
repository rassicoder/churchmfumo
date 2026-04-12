(function () {
    const state = {
        churchId: null,
        role: null,
    };

    function formatDate(value) {
        if (!value) return '-';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;
        return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
    }

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

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

    async function loadProfile() {
        const data = await fetchJson('/api/v1/auth/me');
        if (!data) return;
        const payload = data.data || {};
        const user = payload.user || payload;
        state.role = payload.role || (payload.roles ? payload.roles[0] : null);
        state.churchId = payload.church_id || user.church_id || null;
        if (normalizeRole(state.role) !== 'church admin') {
            if (window.AdminApp && typeof AdminApp.redirectNotAllowed === 'function') {
                AdminApp.redirectNotAllowed('/admin/dashboard');
            } else {
                window.location.href = '/admin/dashboard';
            }
            return;
        }
        setText('welcomeName', user.name || 'Admin');
    }

    async function loadChurchName() {
        if (!state.churchId) {
            setText('churchName', 'No church assigned');
            return;
        }
        const data = await fetchJson('/api/v1/churches/' + state.churchId);
        const church = data && data.data ? data.data : null;
        setText('churchName', church && church.name ? church.name : 'Your Church');
    }

    async function loadStats() {
        if (!state.churchId) return;
        const dashboard = await fetchJson('/api/v1/dashboards/church?church_id=' + state.churchId);
        const counts = dashboard && dashboard.data && dashboard.data.counts ? dashboard.data.counts : {};
        setText('statDepartments', counts.departments || 0);
        setText('statProjects', counts.ongoing_projects || 0);

        const leadersData = await fetchJson('/api/v1/leaders?church_id=' + state.churchId + '&per_page=1');
        const leadersTotal = leadersData && leadersData.meta ? leadersData.meta.total : (leadersData && leadersData.data ? leadersData.data.length : 0);
        setText('statLeaders', leadersTotal || 0);

        const meetingsData = await fetchJson('/api/v1/meetings?church_id=' + state.churchId + '&per_page=5');
        const meetingsItems = meetingsData && meetingsData.data ? meetingsData.data : [];
        const upcomingCount = meetingsItems.length;
        setText('statMeetings', upcomingCount || 0);
    }

    async function loadLeaders() {
        const tbody = document.getElementById('leadersTable');
        if (!tbody) return;
        const data = await fetchJson('/api/v1/leaders?church_id=' + state.churchId + '&per_page=5');
        const items = data && data.data ? data.data : [];
        if (!items.length) {
            tbody.innerHTML = '<tr><td class="py-3" colspan="3">No leaders available</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        items.forEach(function (leader) {
            const row = '<tr>'
                + '<td class="py-2">' + (leader.full_name || leader.name || '-') + '</td>'
                + '<td class="py-2">' + (leader.position || '-') + '</td>'
                + '<td class="py-2">' + (leader.phone || '-') + '</td>'
                + '</tr>';
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    async function loadMeetings() {
        const container = document.getElementById('meetingsList');
        if (!container) return;
        const data = await fetchJson('/api/v1/meetings?church_id=' + state.churchId + '&per_page=5');
        const items = data && data.data ? data.data : [];
        if (!items.length) {
            container.innerHTML = '<div class="text-slate-500">No upcoming meetings</div>';
            return;
        }
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

    async function loadActivity() {
        const feed = document.getElementById('activityFeed');
        if (!feed) return;
        const data = await fetchJson('/api/v1/leaders?church_id=' + state.churchId + '&per_page=3');
        const items = data && data.data ? data.data : [];
        if (!items.length) {
            feed.innerHTML = '<div class="text-slate-500">No recent activity</div>';
            return;
        }
        feed.innerHTML = '';
        items.forEach(function (leader) {
            feed.insertAdjacentHTML('beforeend',
                '<div class="rounded-xl border border-slate-200 p-3">'
                + '<div class="font-semibold">Leader added</div>'
                + '<div class="text-xs text-slate-500">' + (leader.full_name || leader.name || '-') + ' joined</div>'
                + '</div>'
            );
        });
    }

    async function init() {
        if (typeof AdminApp === 'undefined') return;
        try {
            await loadProfile();
            await loadChurchName();
            await Promise.all([loadStats(), loadLeaders(), loadMeetings(), loadActivity()]);
        } catch (error) {
            AdminApp.showToast('Failed to load dashboard', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', init);
})();
