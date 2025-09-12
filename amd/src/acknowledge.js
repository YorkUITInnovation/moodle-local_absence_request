import Ajax from 'core/ajax';
import Notification from 'core/notification';

/**
 * Initialize the acknowledge functionality
 */
export const init = () => {
    // Add event listener for acknowledge clicks using event delegation
    document.addEventListener('click', (event) => {
        // Check if clicked element has the acknowledge class
        if (event.target.classList.contains('local-absence-request-acknowledge')) {
            handleAcknowledgeClick(event.target);
        }
    });
};

/**
 * Handle click on acknowledge element
 * @param {Element} element The clicked element
 */
const handleAcknowledgeClick = (element) => {
    const id = element.getAttribute('data-id');

    if (!id) {
        Notification.addNotification({
            message: 'Invalid record ID',
            type: 'error'
        });
        return;
    }

    // Make AJAX call to acknowledge webservice
    Ajax.call([{
        methodname: 'local_absence_request_acknowledge',
        args: {
            id: parseInt(id)
        }
    }])[0].then((response) => {
        if (response.success) {
            updateAcknowledgeIcon(element, response.acknowledged);
        } else {
            Notification.addNotification({
                message: 'Failed to update acknowledgment status',
                type: 'error'
            });
        }
    }).catch((error) => {
        Notification.addNotification({
            message: 'Error updating acknowledgment: ' + error.message,
            type: 'error'
        });
    });
};

/**
 * Update the acknowledge icon based on the acknowledged value
 * @param {Element} element The element to update
 * @param {number} acknowledged The acknowledged status (0 or 1)
 */
const updateAcknowledgeIcon = (element, acknowledged) => {
    // Remove existing FontAwesome classes
    element.classList.remove('fa-check', 'fa-times');

    if (acknowledged === 1) {
        // Set to green checkmark
        element.classList.add('fa-check');
        element.style.color = 'green';
        element.setAttribute('title', 'Acknowledged - Click to toggle');
    } else {
        // Set to red X
        element.classList.add('fa-times');
        element.style.color = 'red';
        element.setAttribute('title', 'Not acknowledged - Click to toggle');
    }
};
