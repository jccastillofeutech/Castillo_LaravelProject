const bulkForm = document.querySelector('[data-bulk-form]');

const singleDeleteForm = document.querySelector('#delete-member-form');
const singleDeleteDialog = document.querySelector('[data-single-delete-dialog]');

if (singleDeleteForm && singleDeleteDialog) {
    const deleteTrigger = document.querySelector('[data-single-delete-trigger]');
    const cancelDelete = singleDeleteDialog.querySelector('[data-single-delete-cancel]');
    const confirmDelete = singleDeleteDialog.querySelector('[data-single-delete-confirm]');

    deleteTrigger.addEventListener('click', () => singleDeleteDialog.showModal());
    cancelDelete.addEventListener('click', () => {
        singleDeleteDialog.close();
        deleteTrigger.focus();
    });
    confirmDelete.addEventListener('click', () => singleDeleteForm.requestSubmit());
    singleDeleteDialog.addEventListener('click', (event) => {
        if (event.target === singleDeleteDialog) {
            singleDeleteDialog.close();
            deleteTrigger.focus();
        }
    });
}

if (bulkForm) {
    const openButton = document.querySelector('[data-selection-open]');
    const toolbar = bulkForm.querySelector('[data-selection-toolbar]');
    const cancelButton = bulkForm.querySelector('[data-selection-cancel]');
    const deleteButton = bulkForm.querySelector('[data-bulk-delete]');
    const selectedCount = bulkForm.querySelector('[data-selected-count]');
    const selectPage = bulkForm.querySelector('[data-select-page]');
    const confirmationDialog = document.querySelector('[data-confirm-dialog]');
    const confirmationCount = confirmationDialog?.querySelector('[data-confirm-count]');
    const confirmationMembers = confirmationDialog?.querySelector('[data-confirm-members]');
    const confirmationPhrase = confirmationDialog?.querySelector('[data-confirm-phrase]');
    const confirmationCancel = confirmationDialog?.querySelector('[data-confirm-cancel]');
    const confirmationDelete = confirmationDialog?.querySelector('[data-confirm-delete]');
    const checkboxes = [...bulkForm.querySelectorAll('[data-member-checkbox]')];
    const selectionLabels = [...bulkForm.querySelectorAll('.member-select')];
    const profileLinks = [...bulkForm.querySelectorAll('.member-profile-link')];
    let confirmationGranted = false;

    const updateSelectedCount = () => {
        const count = checkboxes.filter((checkbox) => checkbox.checked).length;

        selectedCount.textContent = count;
        deleteButton.disabled = count === 0;
        selectPage.checked = count === checkboxes.length;
        selectPage.indeterminate = count > 0 && count < checkboxes.length;
    };

    openButton?.addEventListener('click', () => {
        bulkForm.classList.add('selection-mode');
        toolbar.hidden = false;
        checkboxes.forEach((checkbox) => {
            checkbox.disabled = false;
        });
        selectionLabels.forEach((label) => {
            label.hidden = false;
        });
        profileLinks.forEach((link) => {
            link.hidden = true;
        });
        openButton.hidden = true;
    });

    cancelButton?.addEventListener('click', () => {
        bulkForm.classList.remove('selection-mode');
        toolbar.hidden = true;
        checkboxes.forEach((checkbox) => {
            checkbox.checked = false;
            checkbox.disabled = true;
        });
        selectionLabels.forEach((label) => {
            label.hidden = true;
        });
        profileLinks.forEach((link) => {
            link.hidden = false;
        });
        openButton.hidden = false;
        updateSelectedCount();
    });

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    selectPage?.addEventListener('change', () => {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectPage.checked;
        });
        updateSelectedCount();
    });

    bulkForm.querySelectorAll('[data-member-row]').forEach((row) => {
        row.addEventListener('click', (event) => {
            if (!bulkForm.classList.contains('selection-mode') || event.target.closest('a, button, input, label')) {
                return;
            }

            const checkbox = row.querySelector('[data-member-checkbox]');

            checkbox.checked = !checkbox.checked;
            updateSelectedCount();
        });
    });

    bulkForm.addEventListener('submit', (event) => {
        const count = checkboxes.filter((checkbox) => checkbox.checked).length;

        if (confirmationGranted) {
            confirmationGranted = false;

            return;
        }

        event.preventDefault();

        if (count === 0 || !confirmationDialog) {
            return;
        }

        confirmationCount.textContent = `${count} ${count === 1 ? 'member' : 'members'}`;
        confirmationMembers.replaceChildren();
        checkboxes.filter((checkbox) => checkbox.checked).slice(0, 5).forEach((checkbox) => {
            const memberName = checkbox.closest('[data-member-row]').querySelector('.member-name').textContent.trim();
            const listItem = document.createElement('li');

            listItem.textContent = memberName;
            confirmationMembers.append(listItem);
        });

        if (count > 5) {
            const remainingMembers = document.createElement('li');

            remainingMembers.textContent = `and ${count - 5} more`;
            confirmationMembers.append(remainingMembers);
        }

        confirmationPhrase.value = '';
        confirmationDelete.disabled = true;
        confirmationDialog.showModal();
    });

    confirmationCancel?.addEventListener('click', () => {
        confirmationDialog.close();
        confirmationPhrase.value = '';
        deleteButton.focus();
    });

    confirmationPhrase?.addEventListener('input', () => {
        confirmationDelete.disabled = confirmationPhrase.value.trim() !== 'DELETE';
    });

    confirmationDelete?.addEventListener('click', () => {
        confirmationGranted = true;
        confirmationDialog.close();
        bulkForm.requestSubmit(deleteButton);
    });

    confirmationDialog?.addEventListener('click', (event) => {
        if (event.target === confirmationDialog) {
            confirmationDialog.close();
            confirmationPhrase.value = '';
            deleteButton.focus();
        }
    });

    confirmationDialog?.addEventListener('close', () => {
        confirmationPhrase.value = '';
        confirmationDelete.disabled = true;
    });
}
