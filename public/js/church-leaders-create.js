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
        console.log('Leaders create init');
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

        const form = document.getElementById('leaderCreateForm');
        if (!form) return;
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                AdminApp.showErrorBanner(null);
            }
            console.log('Leader create submit');
            const churchId = profile.church_id || (profile.user ? profile.user.church_id : null);
            if (!churchId) {
                AdminApp.showErrorBanner('No church is assigned to your account.');
                return;
            }
            const payload = {
                church_id: churchId,
                full_name: form.full_name.value,
                position: form.position.value,
                level: form.level.value,
                status: form.status.value,
                email: form.email.value || null,
                phone: form.phone.value || null,
                term_start: form.term_start.value || null,
                term_end: form.term_end.value || null,
            };
            console.log('Leader payload', payload);
            try {
                form.querySelector('button[type="submit"]').disabled = true;
                console.log('Sending leader create request');
                await fetchJson('/api/v1/leaders', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                console.log('Leader create success');
                AdminApp.showToast('Leader created successfully', 'success');
                setTimeout(function () {
                    window.location.href = '/admin/church/leaders';
                }, 400);
            } catch (error) {
                console.error('Leader create error', error);
                if (error && error.status === 422) {
                    if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                        AdminApp.showErrorBanner(error.errors || error.message);
                    }
                    return;
                }
                if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                    AdminApp.showErrorBanner(error && error.message ? error.message : 'Request failed.');
                }
                AdminApp.showToast('Failed to create leader', 'error');
            } finally {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) btn.disabled = false;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
