/**
 * Absence Request Acknowledge JavaScript
 * Handles individual and bulk acknowledgment of absence requests
 */

import Ajax from 'core/ajax';
import Notification from 'core/notification';

/**
 * AbsenceAcknowledge class for handling acknowledgment functionality
 */
class AbsenceAcknowledge {

    /**
     * Initialize the acknowledge functionality
     */
    init() {
        this.bindEvents();
    }

    /**
     * Bind event handlers
     */
    bindEvents() {
        // Individual acknowledge toggle
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('local-absence-request-acknowledge')) {
                e.preventDefault();
                const id = e.target.getAttribute('data-id');
                this.toggleAcknowledge(id, e.target);
            }
        });

        // Select all checkbox functionality
        document.addEventListener('change', (e) => {
            if (e.target.id === 'select_all_absences') {
                const isChecked = e.target.checked;
                const checkboxes = document.querySelectorAll('.absence-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                this.updateBulkButton();
            }
        });

        // Individual checkbox change
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('absence-checkbox')) {
                this.updateSelectAllState();
                this.updateBulkButton();
            }
        });

        // Bulk acknowledge button
        document.addEventListener('click', (e) => {
            if (e.target.id === 'bulk_acknowledge_btn') {
                e.preventDefault();
                this.bulkAcknowledge();
            }
        });
    }

    /**
     * Toggle individual acknowledgment status
     * @param {number} id The absence request ID
     * @param {HTMLElement} element The DOM element that was clicked
     */
    toggleAcknowledge(id, element) {
        const isAcknowledged = element.classList.contains('fa-check');
        const newStatus = isAcknowledged ? 0 : 1;

        Ajax.call([{
            methodname: 'local_absence_request_acknowledge_request',
            args: {
                id: parseInt(id),
                acknowledged: newStatus
            }
        }])[0].done((response) => {
            if (response.success) {
                this.updateAcknowledgeIcon(element, newStatus);
                // Update the corresponding checkbox
                const checkbox = document.querySelector(`.absence-checkbox[data-id="${id}"]`);
                if (checkbox) {
                    checkbox.checked = newStatus === 1;
                }
                this.updateSelectAllState();
                this.updateBulkButton();
            } else {
                Notification.addNotification({
                    message: response.message || 'Error updating acknowledgment',
                    type: 'error'
                });
            }
        }).fail((error) => {
            console.error('AJAX Error:', error);
            Notification.addNotification({
                message: 'Error communicating with server: ' + (error.message || 'Unknown error'),
                type: 'error'
            });
        });
    }

    /**
     * Bulk acknowledge selected items
     */
    bulkAcknowledge() {
        const selectedIds = [];
        const checkedBoxes = document.querySelectorAll('.absence-checkbox:checked');

        checkedBoxes.forEach(checkbox => {
            selectedIds.push(parseInt(checkbox.getAttribute('data-id')));
        });

        if (selectedIds.length === 0) {
            Notification.addNotification({
                message: 'Please select at least one absence request to acknowledge',
                type: 'warning'
            });
            return;
        }

        Ajax.call([{
            methodname: 'local_absence_request_bulk_acknowledge',
            args: {
                ids: selectedIds
            }
        }])[0].done((response) => {
            if (response.success) {
                // Update all icons for acknowledged items
                selectedIds.forEach(id => {
                    const icon = document.querySelector(`.local-absence-request-acknowledge[data-id="${id}"]`);
                    if (icon) {
                        this.updateAcknowledgeIcon(icon, 1);
                    }
                });

                this.updateSelectAllState();
                this.updateBulkButton();

                Notification.addNotification({
                    message: `Successfully acknowledged ${selectedIds.length} absence request(s)`,
                    type: 'success'
                });
            } else {
                Notification.addNotification({
                    message: response.message || 'Error acknowledging requests',
                    type: 'error'
                });
            }
        }).fail((error) => {
            console.error('AJAX Error:', error);
            Notification.addNotification({
                message: 'Error communicating with server: ' + (error.message || 'Unknown error'),
                type: 'error'
            });
        });
    }

    /**
     * Update the acknowledge icon appearance
     * @param {HTMLElement} element The DOM element to update
     * @param {number} acknowledged The acknowledgment status (0 or 1)
     */
    updateAcknowledgeIcon(element, acknowledged) {
        if (acknowledged === 1) {
            element.classList.remove('fa-times');
            element.classList.add('fa-check');
            element.style.color = 'green';
            element.setAttribute('title', 'Acknowledged - Click to toggle');
        } else {
            element.classList.remove('fa-check');
            element.classList.add('fa-times');
            element.style.color = 'red';
            element.setAttribute('title', 'Not acknowledged - Click to toggle');
        }
    }

    /**
     * Update the select all checkbox state based on individual checkboxes
     */
    updateSelectAllState() {
        const allCheckboxes = document.querySelectorAll('.absence-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.absence-checkbox:checked');

        if (allCheckboxes.length === 0) {
            return;
        }

        const selectAllCheckbox = document.getElementById('select_all_absences');
        if (!selectAllCheckbox) {
            return;
        }

        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCheckboxes.length === allCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }
    }

    /**
     * Update bulk acknowledge button visibility and state
     */
    updateBulkButton() {
        const checkedCheckboxes = document.querySelectorAll('.absence-checkbox:checked');
        const checkedCount = checkedCheckboxes.length;
        const container = document.getElementById('bulk-acknowledge-container');
        const countSpan = document.getElementById('selected_count');

        if (container) {
            if (checkedCount > 0) {
                // Show the container and update count
                container.style.display = '';
                if (countSpan) {
                    countSpan.textContent = checkedCount;
                }
            } else {
                // Hide the container when no items selected
                container.style.display = 'none';
            }
        }
    }
}

const absenceAcknowledge = new AbsenceAcknowledge();

export default {
    init: () => absenceAcknowledge.init()
};
