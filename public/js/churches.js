(function () {
    let churchesTable = null;
    let modal = null;
    let isSuperAdmin = true;

    function formatDate(value) {
        if (!value) return '-';
        const parsed = new Date(value);
        if (Number.isNaN(parsed.getTime())) return value;
        return parsed.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function setSubmitting(isSubmitting) {
        const btn = document.getElementById('churchSubmitBtn');
        const spinner = document.getElementById('churchSubmitSpinner');
        if (btn) btn.disabled = isSubmitting;
        if (spinner) spinner.classList.toggle('d-none', !isSubmitting);
    }

    function clearErrors() {
        ['churchName', 'churchLocation', 'churchStatus', 'churchAdminName', 'churchAdminEmail', 'churchAdminPassword'].forEach(function (id) {
            const input = document.getElementById(id);
            if (input) input.classList.remove('is-invalid');
        });
        const nameError = document.getElementById('churchNameError');
        const locationError = document.getElementById('churchLocationError');
        const statusError = document.getElementById('churchStatusError');
        const adminNameError = document.getElementById('churchAdminNameError');
        const adminEmailError = document.getElementById('churchAdminEmailError');
        const adminPasswordError = document.getElementById('churchAdminPasswordError');
        if (nameError) nameError.textContent = '';
        if (locationError) locationError.textContent = '';
        if (statusError) statusError.textContent = '';
        if (adminNameError) adminNameError.textContent = '';
        if (adminEmailError) adminEmailError.textContent = '';
        if (adminPasswordError) adminPasswordError.textContent = '';
    }

    function setFieldError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (input) input.classList.add('is-invalid');
        if (error) error.textContent = message;
    }

    function openCreateModal() {
        const title = document.getElementById('churchModalTitle');
        const idInput = document.getElementById('churchId');
        const nameInput = document.getElementById('churchName');
        const locationInput = document.getElementById('churchLocation');
        const statusInput = document.getElementById('churchStatus');
        const adminNameInput = document.getElementById('churchAdminName');
        const adminEmailInput = document.getElementById('churchAdminEmail');
        const adminPasswordInput = document.getElementById('churchAdminPassword');
        const adminSection = document.getElementById('churchAdminSection');
        const adminFields = document.getElementById('churchAdminFields');
        const adminFieldsEmail = document.getElementById('churchAdminFieldsEmail');
        const adminFieldsPassword = document.getElementById('churchAdminFieldsPassword');
        if (title) title.textContent = 'Create Church';
        if (idInput) idInput.value = '';
        if (nameInput) nameInput.value = '';
        if (locationInput) locationInput.value = '';
        if (statusInput) statusInput.value = 'active';
        if (adminNameInput) adminNameInput.value = '';
        if (adminEmailInput) adminEmailInput.value = '';
        if (adminPasswordInput) adminPasswordInput.value = '';
        if (adminSection) adminSection.classList.remove('d-none');
        if (adminFields) adminFields.classList.remove('d-none');
        if (adminFieldsEmail) adminFieldsEmail.classList.remove('d-none');
        if (adminFieldsPassword) adminFieldsPassword.classList.remove('d-none');
        clearErrors();
    }

    async function openEditModal(id) {
        const title = document.getElementById('churchModalTitle');
        const idInput = document.getElementById('churchId');
        const nameInput = document.getElementById('churchName');
        const locationInput = document.getElementById('churchLocation');
        const statusInput = document.getElementById('churchStatus');
        const adminSection = document.getElementById('churchAdminSection');
        const adminFields = document.getElementById('churchAdminFields');
        const adminFieldsEmail = document.getElementById('churchAdminFieldsEmail');
        const adminFieldsPassword = document.getElementById('churchAdminFieldsPassword');
        if (title) title.textContent = 'Edit Church';
        if (idInput) idInput.value = id;
        if (adminSection) adminSection.classList.add('d-none');
        if (adminFields) adminFields.classList.add('d-none');
        if (adminFieldsEmail) adminFieldsEmail.classList.add('d-none');
        if (adminFieldsPassword) adminFieldsPassword.classList.add('d-none');
        clearErrors();

        try {
            const data = await AdminApp.apiGet('/api/v1/churches/' + id);
            const church = data.data || data;
            if (nameInput) nameInput.value = church.name || '';
            if (locationInput) locationInput.value = church.location || '';
            if (statusInput) statusInput.value = church.status || 'active';
        } catch (error) {
            AdminApp.showToast('Failed to load church', 'error');
        }
    }

    function initTable() {
        churchesTable = AdminApp.initTable({
            tableId: 'churches-table',
            endpoint: '/api/v1/churches',
            emptyMessage: 'No churches found',
            mapRow: function (row) {
                const actionsCell = isSuperAdmin
                    ? '<td>'
                        + '<div class="dropdown">'
                        + '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Actions</button>'
                        + '<ul class="dropdown-menu dropdown-menu-end">'
                        + '<li><button class="dropdown-item" type="button" data-action="edit" data-id="' + row.id + '">Edit</button></li>'
                        + '<li><button class="dropdown-item text-danger" type="button" data-action="delete" data-id="' + row.id + '">Delete</button></li>'
                        + '</ul>'
                        + '</div>'
                        + '</td>'
                    : '<td class="d-none"></td>';
                return '<tr>'
                    + '<td>' + (row.name || '-') + '</td>'
                    + '<td>' + (row.location || '-') + '</td>'
                    + '<td>' + formatDate(row.created_at) + '</td>'
                    + actionsCell
                    + '</tr>';
            }
        });

        const table = document.getElementById('churches-table');
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
        if (!confirm('Delete this church?')) return;
        try {
            await AdminApp.apiDelete('/api/v1/churches/' + id);
            AdminApp.showToast('Church deleted successfully', 'success');
            if (churchesTable) churchesTable.reload();
        } catch (error) {
            const message = error && error.status === 403
                ? 'You are not authorized to perform this action'
                : (error && error.message ? error.message : 'Failed to delete church');
            AdminApp.showToast(message, 'error');
        }
    }

    function validateForm(name, location, status, adminName, adminEmail, adminPassword, isCreate) {
        let valid = true;
        clearErrors();
        if (!name) {
            setFieldError('churchName', 'churchNameError', 'Name is required');
            valid = false;
        }
        if (!location) {
            setFieldError('churchLocation', 'churchLocationError', 'Location is required');
            valid = false;
        }
        if (!status) {
            setFieldError('churchStatus', 'churchStatusError', 'Status is required');
            valid = false;
        }
        if (isCreate) {
            if (!adminName) {
                setFieldError('churchAdminName', 'churchAdminNameError', 'Admin name is required');
                valid = false;
            }
            if (!adminEmail) {
                setFieldError('churchAdminEmail', 'churchAdminEmailError', 'Admin email is required');
                valid = false;
            }
            if (!adminPassword) {
                setFieldError('churchAdminPassword', 'churchAdminPasswordError', 'Admin password is required');
                valid = false;
            }
        }
        return valid;
    }

    function applyApiErrors(errors) {
        if (!errors) return;
        const map = {
            name: { input: 'churchName', error: 'churchNameError' },
            location: { input: 'churchLocation', error: 'churchLocationError' },
            status: { input: 'churchStatus', error: 'churchStatusError' },
            admin_name: { input: 'churchAdminName', error: 'churchAdminNameError' },
            admin_email: { input: 'churchAdminEmail', error: 'churchAdminEmailError' },
            admin_password: { input: 'churchAdminPassword', error: 'churchAdminPasswordError' },
        };
        Object.keys(errors).forEach(function (key) {
            const entry = map[key];
            if (!entry) return;
            const message = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
            setFieldError(entry.input, entry.error, message);
        });
    }

    function initForm() {
        const form = document.getElementById('churchForm');
        if (!form) return;
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const id = document.getElementById('churchId').value;
            const name = document.getElementById('churchName').value.trim();
            const location = document.getElementById('churchLocation').value.trim();
            const status = document.getElementById('churchStatus').value;
            const adminName = document.getElementById('churchAdminName').value.trim();
            const adminEmail = document.getElementById('churchAdminEmail').value.trim();
            const adminPassword = document.getElementById('churchAdminPassword').value;
            const isCreate = !id;

            if (!validateForm(name, location, status, adminName, adminEmail, adminPassword, isCreate)) return;

            setSubmitting(true);
            try {
                const payload = { name: name, location: location, status: status };
                if (isCreate) {
                    payload.admin_name = adminName;
                    payload.admin_email = adminEmail;
                    payload.admin_password = adminPassword;
                }
                if (id) {
                    await AdminApp.apiPut('/api/v1/churches/' + id, payload);
                    AdminApp.showToast('Church updated successfully', 'success');
                } else {
                    await AdminApp.apiPost('/api/v1/churches', payload);
                    AdminApp.showToast('Church created successfully', 'success');
                }
                if (churchesTable) churchesTable.reload();
                if (modal) modal.hide();
            } catch (error) {
                if (error && error.status === 422 && error.errors) {
                    applyApiErrors(error.errors);
                    AdminApp.showToast('Please fix the highlighted fields', 'error');
                } else if (error && error.status === 403) {
                    AdminApp.showToast('You are not authorized to perform this action', 'error');
                } else {
                    const message = error && error.message ? error.message : 'Failed to save church';
                    AdminApp.showToast(message, 'error');
                }
            } finally {
                setSubmitting(false);
            }
        });
    }

    function init() {
        if (typeof AdminApp === 'undefined' || typeof bootstrap === 'undefined') return;
        modal = new bootstrap.Modal(document.getElementById('churchModal'));
        const createBtn = document.getElementById('churchCreateBtn');
        AdminApp.getCurrentUser().then(function (profile) {
            if (createBtn) {
                createBtn.addEventListener('click', function () {
                    openCreateModal();
                });
            }
            initTable();
            initForm();
        }).catch(function () {
            if (createBtn) {
                createBtn.addEventListener('click', function () {
                    openCreateModal();
                });
            }
            initTable();
            initForm();
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
