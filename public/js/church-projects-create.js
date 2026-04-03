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
        console.log('Projects create init');
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

        const form = document.getElementById('projectCreateForm');
        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                AdminApp.showErrorBanner(null);
            }
            console.log('Project create submit');
            const churchId = profile.church_id || (profile.user ? profile.user.church_id : null);
            if (!churchId) {
                AdminApp.showErrorBanner('No church is assigned to your account.');
                return;
            }
            const payload = {
                church_id: churchId,
                name: form.name.value,
                description: form.description.value || null,
                start_date: form.start_date.value,
                end_date: form.end_date.value,
                budget: form.budget.value ? Number(form.budget.value) : null,
                progress: form.progress.value ? Number(form.progress.value) : 0,
                status: form.status.value,
            };

            if (form.leader_id) {
                payload.leader_id = form.leader_id.value || null;
            }
            console.log('Project payload', payload);

            try {
                form.querySelector('button[type="submit"]').disabled = true;
                console.log('Sending project create request');
                await fetchJson('/api/v1/projects', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                console.log('Project create success');
                AdminApp.showToast('Project created successfully', 'success');
                setTimeout(function () {
                    window.location.href = '/admin/church/projects';
                }, 400);
            } catch (error) {
                console.error('Project create error', error);
                if (error && error.status === 422) {
                    if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                        AdminApp.showErrorBanner(error.errors || error.message);
                    }
                    return;
                }
                if (window.AdminApp && typeof AdminApp.showErrorBanner === 'function') {
                    AdminApp.showErrorBanner(error && error.message ? error.message : 'Request failed.');
                }
                AdminApp.showToast('Failed to create project', 'error');
            } finally {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) btn.disabled = false;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
