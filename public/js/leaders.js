(function () {
    let leadersTable = null;
    let modal = null;
    let currentRole = null;
    let currentChurchId = null;

    function setSubmitting(isSubmitting) {
        const btn = document.getElementById('leaderSubmitBtn');
        const spinner = document.getElementById('leaderSubmitSpinner');
        if (btn) btn.disabled = isSubmitting;
        if (spinner) spinner.classList.toggle('d-none', !isSubmitting);
    }

    function clearErrors() {
        ['leaderName', 'leaderPosition', 'leaderEmail', 'leaderPhone', 'leaderChurch', 'leaderLevel', 'leaderStatus', 'leaderTermStart', 'leaderTermEnd'].forEach(function (id) {
            const input = document.getElementById(id);
            if (input) input.classList.remove('is-invalid');
        });
        const nameError = document.getElementById('leaderNameError');
        const positionError = document.getElementById('leaderPositionError');
        const emailError = document.getElementById('leaderEmailError');
        const phoneError = document.getElementById('leaderPhoneError');
        const churchError = document.getElementById('leaderChurchError');
        const levelError = document.getElementById('leaderLevelError');
        const statusError = document.getElementById('leaderStatusError');
        const termStartError = document.getElementById('leaderTermStartError');
        const termEndError = document.getElementById('leaderTermEndError');
        if (nameError) nameError.textContent = '';
        if (positionError) positionError.textContent = '';
        if (emailError) emailError.textContent = '';
        if (phoneError) phoneError.textContent = '';
        if (churchError) churchError.textContent = '';
        if (levelError) levelError.textContent = '';
        if (statusError) statusError.textContent = '';
        if (termStartError) termStartError.textContent = '';
        if (termEndError) termEndError.textContent = '';
    }

    function setFieldError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (input) input.classList.add('is-invalid');
        if (error) error.textContent = message;
    }

    async function loadChurchOptions(selectedId) {
        const select = document.getElementById('leaderChurch');
        if (!select) return;
        select.innerHTML = '<option value="">Select church</option>';
        try {
            const data = await AdminApp.apiGet('/api/v1/churches?per_page=100');
            const items = (data && data.data && data.data.data) ? data.data.data : (data.data || []);
            items.forEach(function (church) {
                const opt = document.createElement('option');
                opt.value = church.id || '';
                opt.textContent = church.name || '-';
                select.appendChild(opt);
            });
            if (selectedId) select.value = selectedId;
            if (currentRole === 'Church Admin') {
                select.value = currentChurchId || select.value;
                select.disabled = true;
                const wrapper = select.closest('.mb-3') || select.closest('.col-md-6');
                if (wrapper) wrapper.classList.add('d-none');
            }
        } catch (error) {
            AdminApp.showToast('Failed to load churches', 'error');
        }
    }

    function openCreateModal() {
        const title = document.getElementById('leaderModalTitle');
        const idInput = document.getElementById('leaderId');
        const nameInput = document.getElementById('leaderName');
        const positionInput = document.getElementById('leaderPosition');
        const emailInput = document.getElementById('leaderEmail');
        const phoneInput = document.getElementById('leaderPhone');
        const levelInput = document.getElementById('leaderLevel');
        const statusInput = document.getElementById('leaderStatus');
        const termStartInput = document.getElementById('leaderTermStart');
        const termEndInput = document.getElementById('leaderTermEnd');
        if (title) title.textContent = 'Create Leader';
        if (idInput) idInput.value = '';
        if (nameInput) nameInput.value = '';
        if (positionInput) positionInput.value = '';
        if (emailInput) emailInput.value = '';
        if (phoneInput) phoneInput.value = '';
        if (levelInput) levelInput.selectedIndex = 0;
        if (statusInput) statusInput.selectedIndex = 0;
        if (termStartInput) termStartInput.value = '';
        if (termEndInput) termEndInput.value = '';
        clearErrors();
        loadChurchOptions();
    }

    async function openEditModal(id) {
        const title = document.getElementById('leaderModalTitle');
        const idInput = document.getElementById('leaderId');
        const nameInput = document.getElementById('leaderName');
        const positionInput = document.getElementById('leaderPosition');
        const emailInput = document.getElementById('leaderEmail');
        const phoneInput = document.getElementById('leaderPhone');
        const levelInput = document.getElementById('leaderLevel');
        const statusInput = document.getElementById('leaderStatus');
        const termStartInput = document.getElementById('leaderTermStart');
        const termEndInput = document.getElementById('leaderTermEnd');
        if (title) title.textContent = 'Edit Leader';
        if (idInput) idInput.value = id;
        clearErrors();

        try {
            const data = await AdminApp.apiGet('/api/v1/leaders/' + id);
            const leader = data.data || data;
            if (nameInput) nameInput.value = leader.full_name || leader.name || '';
            if (positionInput) positionInput.value = leader.position || '';
            if (emailInput) emailInput.value = leader.email || '';
            if (phoneInput) phoneInput.value = leader.phone || '';
            if (levelInput) levelInput.value = leader.level || levelInput.value;
            if (statusInput) statusInput.value = leader.status || statusInput.value;
            if (termStartInput) termStartInput.value = leader.term_start || '';
            if (termEndInput) termEndInput.value = leader.term_end || '';
            await loadChurchOptions(leader.church_id || (leader.church ? leader.church.id : ''));
        } catch (error) {
            AdminApp.showToast('Failed to load leader', 'error');
        }
    }

    function initTable() {
        leadersTable = AdminApp.initTable({
            tableId: 'leaders-table',
            endpoint: '/api/v1/leaders',
            emptyMessage: 'No leaders found',
            mapRow: function (row) {
                const church = row.church ? row.church.name : '-';
                return '<tr>'
                    + '<td>' + (row.full_name || row.name || '-') + '</td>'
                    + '<td>' + (row.email || '-') + '</td>'
                    + '<td>' + church + '</td>'
                    + '<td>'
                    + '<div class="dropdown">'
                    + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                    + '<ul class="dropdown-menu dropdown-menu-end">'
                    + '<li><button class="dropdown-item" type="button" data-action="edit" data-id="' + row.id + '">Edit</button></li>'
                    + '<li><button class="dropdown-item text-danger" type="button" data-action="delete" data-id="' + row.id + '">Delete</button></li>'
                    + '</ul>'
                    + '</div>'
                    + '</td>'
                    + '</tr>';
            }
        });

        const table = document.getElementById('leaders-table');
        if (table) {
            table.addEventListener('click', function (event) {
                const target = event.target;
                if (!target || !target.dataset || !target.dataset.action) return;
                const id = target.dataset.id;
                if (target.dataset.action === 'edit') {
                    openEditModal(id).then(function () {
                        if (modal) modal.show();
                    });
                }
                if (target.dataset.action === 'delete') {
                    handleDelete(id);
                }
            });
        }
    }

    async function handleDelete(id) {
        if (!confirm('Delete this leader?')) return;
        try {
            await AdminApp.apiDelete('/api/v1/leaders/' + id);
            AdminApp.showToast('Leader deleted successfully', 'success');
            if (leadersTable) leadersTable.reload();
        } catch (error) {
            const message = error && error.message ? error.message : 'Failed to delete leader';
            AdminApp.showToast(message, 'error');
        }
    }

    function validateForm(name, position, churchId, level, status) {
        let valid = true;
        clearErrors();
        if (!name) {
            setFieldError('leaderName', 'leaderNameError', 'Name is required');
            valid = false;
        }
        if (!position) {
            setFieldError('leaderPosition', 'leaderPositionError', 'Position is required');
            valid = false;
        }
        if (!churchId) {
            setFieldError('leaderChurch', 'leaderChurchError', 'Church is required');
            valid = false;
        }
        if (!level) {
            setFieldError('leaderLevel', 'leaderLevelError', 'Level is required');
            valid = false;
        }
        if (!status) {
            setFieldError('leaderStatus', 'leaderStatusError', 'Status is required');
            valid = false;
        }
        return valid;
    }

    function applyApiErrors(errors) {
        if (!errors) return;
        const map = {
            full_name: { input: 'leaderName', error: 'leaderNameError' },
            name: { input: 'leaderName', error: 'leaderNameError' },
            position: { input: 'leaderPosition', error: 'leaderPositionError' },
            email: { input: 'leaderEmail', error: 'leaderEmailError' },
            phone: { input: 'leaderPhone', error: 'leaderPhoneError' },
            church_id: { input: 'leaderChurch', error: 'leaderChurchError' },
            level: { input: 'leaderLevel', error: 'leaderLevelError' },
            status: { input: 'leaderStatus', error: 'leaderStatusError' },
            term_start: { input: 'leaderTermStart', error: 'leaderTermStartError' },
            term_end: { input: 'leaderTermEnd', error: 'leaderTermEndError' },
        };
        Object.keys(errors).forEach(function (key) {
            const entry = map[key];
            if (!entry) return;
            const message = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
            setFieldError(entry.input, entry.error, message);
        });
    }

    async function submitLeader(payload, id) {
        const url = id ? '/api/v1/leaders/' + id : '/api/v1/leaders';
        const method = id ? 'PUT' : 'POST';
        const res = await AdminApp.fetchWithAuth(url, {
            method: method,
            body: JSON.stringify(payload),
        });
        if (res.status === 401) {
            localStorage.removeItem('api_token');
            if (window.AdminApp && typeof AdminApp.redirectToLogin === 'function') {
                AdminApp.redirectToLogin();
            } else {
                window.location.href = '/admin/login';
            }
            return null;
        }
        if (res.status === 403) {
            throw new Error('Unauthorized');
        }
        const data = await res.json().catch(function () { return {}; });
        if (!res.ok) {
            const error = new Error(data.message || 'Request failed');
            error.status = res.status;
            error.errors = data.errors || null;
            throw error;
        }
        return data;
    }

    function initForm() {
        const form = document.getElementById('leaderForm');
        if (!form) return;
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const id = document.getElementById('leaderId').value;
            const name = document.getElementById('leaderName').value.trim();
            const position = document.getElementById('leaderPosition').value.trim();
            const email = document.getElementById('leaderEmail').value.trim();
            const phone = document.getElementById('leaderPhone').value.trim();
            const churchId = document.getElementById('leaderChurch').value;
            const level = document.getElementById('leaderLevel').value;
            const status = document.getElementById('leaderStatus').value;
            const termStart = document.getElementById('leaderTermStart').value;
            const termEnd = document.getElementById('leaderTermEnd').value;

            if (!validateForm(name, position, churchId, level, status)) return;

            setSubmitting(true);
            try {
                const payload = {
                    church_id: churchId,
                    full_name: name,
                    position: position,
                    level: level,
                    status: status,
                    email: email || null,
                    phone: phone || null,
                    term_start: termStart || null,
                    term_end: termEnd || null,
                };
                await submitLeader(payload, id);
                AdminApp.showToast(id ? 'Leader updated successfully' : 'Leader created successfully', 'success');
                if (leadersTable) leadersTable.reload();
                if (modal) modal.hide();
            } catch (error) {
                if (error && error.status === 422 && error.errors) {
                    applyApiErrors(error.errors);
                    AdminApp.showToast('Please fix the highlighted fields', 'error');
                } else {
                    const message = error && error.message ? error.message : 'Failed to save leader';
                    AdminApp.showToast(message, 'error');
                }
            } finally {
                setSubmitting(false);
            }
        });
    }

    function init() {
        if (typeof AdminApp === 'undefined' || typeof bootstrap === 'undefined') return;
        modal = new bootstrap.Modal(document.getElementById('leaderModal'));
        const createBtn = document.getElementById('leaderCreateBtn');
        if (createBtn) {
            createBtn.addEventListener('click', function () {
                openCreateModal();
            });
        }
        AdminApp.getCurrentUser().then(function (profile) {
            currentRole = profile && (profile.role || (profile.roles ? profile.roles[0] : null));
            currentChurchId = profile && (profile.church_id || (profile.user ? profile.user.church_id : null));
            initTable();
            initForm();
        }).catch(function () {
            initTable();
            initForm();
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
