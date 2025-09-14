// Realtime Order Status Updates for Admin
class RealtimeOrderManager {
    constructor() {
        this.orders = new Map();
        this.initializePusher();
        this.initializeEventListeners();
        this.setupNotificationSystem();
    }

    initializePusher() {
        // Initialize Pusher (you'll need to include Pusher library)
        if (typeof Pusher !== 'undefined') {
                    this.pusher = new Pusher('fc8981add0d659cd3d8c', {
            cluster: 'ap1',
            encrypted: true
        });

            // Subscribe to orders channel
            this.channel = this.pusher.subscribe('orders');
            
            // Listen for order status updates
            this.channel.bind('App\\Events\\OrderStatusUpdated', (data) => {
                this.handleOrderStatusUpdate(data);
            });
        }
    }

    initializeEventListeners() {
        // Add event listeners for status change forms
        document.addEventListener('DOMContentLoaded', () => {
            this.setupStatusChangeListeners();
            this.setupRealtimeUpdates();
        });
    }

    setupNotificationSystem() {
        // Create notification container
        const notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(notificationContainer);
    }

    setupStatusChangeListeners() {
        // Find all status select elements
        const statusSelects = document.querySelectorAll('select[name="status"]');
        
        statusSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                this.handleStatusChange(e);
            });
        });
    }

    setupRealtimeUpdates() {
        // Add realtime update indicators
        const orderRows = document.querySelectorAll('tr[data-order-id]');
        
        orderRows.forEach(row => {
            const orderId = row.dataset.orderId;
            this.orders.set(orderId, row);
            
            // Add realtime indicator
            const statusCell = row.querySelector('td:nth-child(6)');
            if (statusCell) {
                const indicator = document.createElement('div');
                indicator.className = 'realtime-indicator';
                indicator.innerHTML = '<i class="fas fa-circle text-success"></i> <small>Realtime</small>';
                indicator.style.display = 'none';
                statusCell.appendChild(indicator);
            }
        });
    }

    handleStatusChange(event) {
        const select = event.target;
        const form = select.closest('form');
        const orderId = form.querySelector('input[name="order_id"]').value;
        const newStatus = parseInt(select.value);
        const currentStatus = parseInt(select.dataset.currentStatus || 0);

        // Show loading state
        this.showLoadingState(select);

        // Make API call to update status
        this.updateOrderStatus(orderId, newStatus, currentStatus)
            .then(response => {
                if (response.success) {
                    this.showSuccessMessage(`✅ Cập nhật trạng thái đơn hàng #${orderId} thành công!`);
                    select.dataset.currentStatus = newStatus;
                    this.updateStatusDisplay(orderId, newStatus);
                    
                    // Show status change notification
                    this.showStatusChangeNotification(orderId, currentStatus, newStatus);
                } else {
                    this.showErrorMessage(`❌ ${response.message || 'Có lỗi xảy ra'}`);
                    select.value = currentStatus; // Reset to previous value
                }
            })
            .catch(error => {
                console.error('Error updating order status:', error);
                this.showErrorMessage('❌ Có lỗi xảy ra khi cập nhật trạng thái');
                select.value = currentStatus; // Reset to previous value
            })
            .finally(() => {
                this.hideLoadingState(select);
            });
    }

    async updateOrderStatus(orderId, newStatus, oldStatus) {
        try {
            const response = await fetch(`/api/orders/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus,
                    message: this.getStatusMessage(newStatus)
                })
            });

            return await response.json();
        } catch (error) {
            throw error;
        }
    }

    handleOrderStatusUpdate(data) {
        const orderId = data.order_id;
        const newStatus = data.new_status;
        const statusText = data.status_text;
        const updatedBy = data.updated_by;

        // Update the order row if it exists
        const orderRow = this.orders.get(orderId.toString());
        if (orderRow) {
            this.updateOrderRow(orderRow, newStatus, statusText, updatedBy);
        }

        // Show notification
        this.showRealtimeNotification(data);
    }

    updateOrderRow(row, newStatus, statusText, updatedBy) {
        // Update status select
        const statusSelect = row.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = newStatus;
            statusSelect.dataset.currentStatus = newStatus;
        }

        // Update status badge
        const statusBadge = row.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.textContent = statusText;
            statusBadge.className = `status-badge badge ${this.getStatusBadgeClass(newStatus)}`;
        }

        // Show realtime indicator
        const indicator = row.querySelector('.realtime-indicator');
        if (indicator) {
            indicator.style.display = 'inline-block';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 3000);
        }

        // Highlight the row temporarily
        row.classList.add('highlight-update');
        setTimeout(() => {
            row.classList.remove('highlight-update');
        }, 2000);
    }

    updateStatusDisplay(orderId, newStatus) {
        const orderRow = this.orders.get(orderId.toString());
        if (orderRow) {
            const statusText = this.getStatusText(newStatus);
            this.updateOrderRow(orderRow, newStatus, statusText, 'admin');
        }
    }

    getStatusText(status) {
        const statusMap = {
            0: 'Chờ xử lý',
            1: 'Đã xử lý',
            2: 'Đang vận chuyển',
            3: 'Giao hàng thành công',
            4: 'Đã hủy',
            5: 'Đã trả lại'
        };
        return statusMap[status] || 'Không xác định';
    }

    getStatusBadgeClass(status) {
        const classMap = {
            0: 'bg-warning',
            1: 'bg-info',
            2: 'bg-primary',
            3: 'bg-success',
            4: 'bg-danger',
            5: 'bg-secondary'
        };
        return classMap[status] || 'bg-secondary';
    }

    getStatusMessage(status) {
        const messageMap = {
            0: 'Đơn hàng đang chờ xử lý',
            1: 'Đơn hàng đã được xử lý và đang chuẩn bị',
            2: 'Đơn hàng đang được vận chuyển',
            3: 'Đơn hàng đã được giao thành công',
            4: 'Đơn hàng đã bị hủy',
            5: 'Đơn hàng đã được trả lại'
        };
        return messageMap[status] || '';
    }

    showLoadingState(select) {
        select.disabled = true;
        select.style.opacity = '0.6';
        
        // Add loading spinner
        const spinner = document.createElement('span');
        spinner.className = 'spinner-border spinner-border-sm ms-2';
        spinner.setAttribute('role', 'status');
        select.parentNode.appendChild(spinner);
    }

    hideLoadingState(select) {
        select.disabled = false;
        select.style.opacity = '1';
        
        // Remove loading spinner
        const spinner = select.parentNode.querySelector('.spinner-border');
        if (spinner) {
            spinner.remove();
        }
    }

    showSuccessMessage(message) {
        this.showNotification(message, 'success');
    }

    showErrorMessage(message) {
        this.showNotification(message, 'error');
    }

    showRealtimeNotification(data) {
        const message = `🔄 Đơn hàng #${data.order_id} đã được cập nhật thành "${data.status_text}"`;
        this.showNotification(message, 'info', true);
    }

    showStatusChangeNotification(orderId, oldStatus, newStatus) {
        const oldStatusText = this.getStatusText(oldStatus);
        const newStatusText = this.getStatusText(newStatus);
        const message = `📋 Đơn hàng #${orderId}: ${oldStatusText} → ${newStatusText}`;
        this.showNotification(message, 'success', false, true);
    }

    showNotification(message, type = 'info', isRealtime = false, isStatusChange = false) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        notification.style.cssText = `
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 0.5rem;
            animation: slideInRight 0.3s ease-out;
        `;
        
        let icon = '';
        if (isRealtime) {
            icon = '🔄';
        } else if (isStatusChange) {
            icon = '📋';
        } else if (type === 'success') {
            icon = '✅';
        } else if (type === 'error') {
            icon = '❌';
        } else {
            icon = 'ℹ️';
        }
        
        notification.innerHTML = `
            ${icon} <strong>${isRealtime ? 'Realtime Update:' : isStatusChange ? 'Status Change:' : 'Notification:'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Add to container
        container.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    window.realtimeOrderManager = new RealtimeOrderManager();
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
