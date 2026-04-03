(function () {
    let profile = null;

    function ensureAuth(res) {
        if (res.status === 401) {
            localStorage.removeItem('api_token');
            window.location.href = '/admin/login';
            return false;
        }
        return true;
    }

    async function fetchJson(url, options) {
        const res = await AdminApp.fetchWithAuth(url, options || {});
        if (!ensureAuth(res)) return null;
        const data = await res.json().catch(function () { return {}; });
        if (!res.ok) {
            const err = new Error(data.message || 'Request failed');
            err.status = res.status;
            err.errors = data.errors || null;
            throw err;
        }
        return data;
    }

    function normalizeRole(role) {
        return String(role || '').toLowerCase();
    }

    async function init() {
        if (typeof AdminApp === 'undefined') return;
        console.log('Meetings create init');
        try {
            const me = await AdminApp.apiGet('/api/v1/auth/me');
            profile = (me && me.data) ? me.data : {};
        } catch (error) {
            AdminApp.showToast('Failed to load profile', 'error');
            return;
        }
        const role = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
        if (normalizeRole(role) !== 'church admin') {
            if (window.AdminApp && typeof AdminApp.redirectNotAllowed === 'function') {
                AdminApp.redirectNotAllowed('/admin/dashboard');
            } else {
                window.location.href = '/admin/dashboard';
            }
            return;
        }

        const form = document.getElementById('meetingCreateForm');
        if (!form) return;
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                AdminApp.showErrorBanner(null);
            }
            console.log('Meeting create submit');
            const churchId = profile.church_id || (profile.user ? profile.user.church_id : null);
            const createdBy = profile.user ? profile.user.id : profile.id;
            if (!churchId) {
                AdminApp.showErrorBanner('No church is assigned to your account.');
                return;
            }
            if (!createdBy) {
                AdminApp.showErrorBanner('User profile missing.');
                return;
            }
            const payload = {
                church_id: churchId,
                created_by: createdBy,
                meeting_type: form.meeting_type.value,
                meeting_date: form.meeting_date.value,
                agenda: form.agenda.value || null,
                minutes: form.minutes.value || null,
            };
            console.log('Meeting payload', payload);
            try {
                form.querySelector('button[type="submit"]').disabled = true;
                console.log('Sending meeting create request');
                await fetchJson('/api/v1/meetings', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                console.log('Meeting create success');
                AdminApp.showToast('Meeting scheduled successfully', 'success');
                setTimeout(function () {
                    window.location.href = '/admin/church/meetings';
                }, 400);
            } catch (error) {
                console.error('Meeting create error', error);
                if (error && error.status === 422) {
                    if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                        AdminApp.showErrorBanner(error.errors || error.message);
                    }
                    return;
                }
                if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                    AdminApp.showErrorBanner(error && error.message ? error.message : 'Request failed.');
                }
                AdminApp.showToast('Failed to create meeting', 'error');
            } finally {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) btn.disabled = false;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
