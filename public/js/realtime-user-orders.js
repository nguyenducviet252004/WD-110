// Realtime Order Status Updates for User
class RealtimeUserOrderManager {
    constructor() {
        console.log('🚀 RealtimeUserOrderManager: Initializing...');
        this.orders = new Map();
        this.userId = null;
        this.initializePusher();
        this.initializeEventListeners();
        this.setupNotificationSystem();
        console.log('✅ RealtimeUserOrderManager: Initialized successfully');
    }

    initializePusher() {
        // Initialize Pusher (you'll need to include Pusher library)
        if (typeof Pusher !== 'undefined') {
            try {
                this.pusher = new Pusher('fc8981add0d659cd3d8c', {
                    cluster: 'ap1',
                    encrypted: true
                });

                // Subscribe to orders channel
                this.channel = this.pusher.subscribe('orders');

                // Listen for order status updates
                this.channel.bind('App\\Events\\OrderStatusUpdated', (data) => {
                    console.log('🔄 RealtimeUserOrderManager: Received update:', data);
                    this.handleOrderStatusUpdate(data);
                });

                // Add connection status listeners
                this.pusher.connection.bind('connected', () => {
                    console.log('✅ RealtimeUserOrderManager: Pusher connected');
                });

                this.pusher.connection.bind('disconnected', () => {
                    console.log('❌ RealtimeUserOrderManager: Pusher disconnected');
                });

            } catch (error) {
                console.error('❌ RealtimeUserOrderManager: Pusher error:', error);
            }
        } else {
            console.warn('⚠️ RealtimeUserOrderManager: Pusher library not loaded');
        }
    }

    initializeEventListeners() {
        // Run immediately if DOM is already loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupRealtimeUpdates();
                this.setupRefreshButton();
            });
        } else {
            // DOM is already loaded, run immediately
            this.setupRealtimeUpdates();
            this.setupRefreshButton();
        }
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

    setupRealtimeUpdates() {
        // Get user ID from multiple sources with retry
        this.userId = this.getUserIdWithRetry();
        console.log('🔍 RealtimeUserOrderManager: User ID from meta:', document.querySelector('meta[name="user-id"]')?.getAttribute('content'));
        console.log('🔍 RealtimeUserOrderManager: User ID from global:', window.CURRENT_USER_ID);
        console.log('🔍 RealtimeUserOrderManager: Final User ID:', this.userId);

        // Add realtime update indicators to order cards
        const orderCards = document.querySelectorAll('.card[data-order-id]');
        console.log('🔍 RealtimeUserOrderManager: Found', orderCards.length, 'order cards');

        orderCards.forEach((card, index) => {
            const orderId = card.dataset.orderId;
            this.orders.set(orderId, card);
            console.log(`🔍 RealtimeUserOrderManager: Order card ${index + 1}:`, orderId);

            // Add realtime indicator
            const cardHeader = card.querySelector('.card-header');
            if (cardHeader) {
                const indicator = document.createElement('div');
                indicator.className = 'realtime-indicator';
                indicator.innerHTML = '<i class="fas fa-circle text-success"></i> <small>Live</small>';
                indicator.style.cssText = 'position: absolute; top: 10px; right: 10px; display: none;';
                cardHeader.style.position = 'relative';
                cardHeader.appendChild(indicator);
            }
        });
    }

    setupRefreshButton() {
        // Add refresh button to page
        const container = document.querySelector('.container');
        if (container) {
            const refreshBtn = document.createElement('button');
            refreshBtn.className = 'btn btn-outline-primary btn-sm mb-3';
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Làm mới';
            refreshBtn.onclick = () => this.refreshOrders();

            container.insertBefore(refreshBtn, container.firstChild);
        }
    }

    handleOrderStatusUpdate(data) {
        console.log('🔄 RealtimeUserOrderManager: Handling update for user', this.userId, 'data user', data.user_id);

        // Only handle updates for current user's orders
        if (this.userId && data.user_id == this.userId) {
            console.log('✅ RealtimeUserOrderManager: Update is for current user');

            const orderId = data.order_id;
            const newStatus = data.new_status;
            const statusText = data.status_text;
            const updatedBy = data.updated_by;

            // Update the order card if it exists
            const orderCard = this.orders.get(orderId.toString());
            if (orderCard) {
                console.log('✅ RealtimeUserOrderManager: Found order card, updating...');
                this.updateOrderCard(orderCard, newStatus, statusText, updatedBy);
            } else {
                console.log('❌ RealtimeUserOrderManager: Order card not found for ID:', orderId);
            }

            // Show notification
            this.showRealtimeNotification(data);
        } else {
            console.log('ℹ️ RealtimeUserOrderManager: Update is for different user or no user ID');
        }
    }

    updateOrderCard(card, newStatus, statusText, updatedBy) {
        // Update status badge
        const statusBadge = card.querySelector('.badge');
        if (statusBadge) {
            statusBadge.textContent = statusText;
            statusBadge.className = `badge ${this.getStatusBadgeClass(newStatus)}`;
        }

        // Update status text
        const statusTextElement = card.querySelector('.text-muted');
        if (statusTextElement) {
            statusTextElement.textContent = statusText;
        }

        // Show realtime indicator
        const indicator = card.querySelector('.realtime-indicator');
        if (indicator) {
            indicator.style.display = 'inline-block';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 3000);
        }

        // Highlight the card temporarily
        card.classList.add('highlight-update');
        setTimeout(() => {
            card.classList.remove('highlight-update');
        }, 2000);

        // Find the cancel button wrapper and update its content based on the new status.
        const cancelButtonWrapper = card.querySelector('.cancel-button-wrapper');
        if (cancelButtonWrapper) {
            if (newStatus === 1) { // Status: Processed
                console.log('✅ RealtimeUserOrderManager: Disabling cancel button.');
                cancelButtonWrapper.innerHTML = `
                    <button class="btn btn-outline-secondary btn-sm me-2" disabled data-bs-toggle="tooltip"
                        title="Không thể hủy khi đơn hàng đã được xử lý">Hủy Đơn Hàng</button>
                `;
            } else if (newStatus >= 2) { // Status: Shipping, Completed, Cancelled, etc.
                console.log('✅ RealtimeUserOrderManager: Removing cancel button.');
                cancelButtonWrapper.innerHTML = ''; // Remove the button completely
            }
        }
    }

    async cancelOrder(orderId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            try {
                const response = await fetch(`/order/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    this.showSuccessMessage('✅ Hủy đơn hàng thành công!');
                    this.refreshOrders();
                } else {
                    this.showErrorMessage(`❌ ${result.message || 'Có lỗi xảy ra khi hủy đơn hàng'}`);
                }
            } catch (error) {
                console.error('Error canceling order:', error);
                this.showErrorMessage('❌ Có lỗi xảy ra khi hủy đơn hàng');
            }
        }
    }

    async confirmDelivery(orderId) {
        if (confirm('Xác nhận bạn đã nhận được hàng?')) {
            try {
                const response = await fetch(`/orders/${orderId}/done`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    this.showSuccessMessage('✅ Xác nhận nhận hàng thành công!');
                    this.refreshOrders();
                } else {
                    this.showErrorMessage(`❌ ${result.message || 'Có lỗi xảy ra khi xác nhận nhận hàng'}`);
                }
            } catch (error) {
                console.error('Error confirming delivery:', error);
                this.showErrorMessage('❌ Có lỗi xảy ra khi xác nhận nhận hàng');
            }
        }
    }

    openReviewModal(orderId) {
        // Implement review modal functionality
        this.showInfoMessage('⭐ Chức năng đánh giá sẽ được mở trong modal');
    }

    async refreshOrders() {
        try {
            const response = await fetch(window.location.href, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Error refreshing orders:', error);
            this.showErrorMessage('❌ Có lỗi xảy ra khi làm mới trang');
        }
    }

    getUserIdWithRetry() {
        // Try multiple sources for user ID
        let userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content') ||
                    window.CURRENT_USER_ID ||
                    this.getUserIdFromOrders();

        // If still null, try again after a short delay
        if (!userId) {
            console.log('🔍 RealtimeUserOrderManager: User ID not found, retrying...');
            setTimeout(() => {
                userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content') ||
                        window.CURRENT_USER_ID ||
                        this.getUserIdFromOrders();
                if (userId) {
                    console.log('🔍 RealtimeUserOrderManager: User ID found on retry:', userId);
                    this.userId = userId;
                }
            }, 100);
        }

        return userId;
    }

    getUserIdFromOrders() {
        // Try to get user ID from order cards
        const orderCards = document.querySelectorAll('.card[data-order-id]');
        if (orderCards.length > 0) {
            // Get user ID from first order card
            const firstCard = orderCards[0];
            const userId = firstCard.dataset.userId;
            if (userId) {
                console.log('🔍 RealtimeUserOrderManager: Found user ID from order card:', userId);
                return userId;
            }
        }
        return null;
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

    showSuccessMessage(message) {
        this.showNotification(message, 'success');
    }

    showErrorMessage(message) {
        this.showNotification(message, 'error');
    }

    showInfoMessage(message) {
        this.showNotification(message, 'info');
    }

    showRealtimeNotification(data) {
        const message = `🔄 Đơn hàng #${data.order_id} của bạn đã được cập nhật thành "${data.status_text}"`;
        this.showNotification(message, 'info', true);
    }

    showNotification(message, type = 'info', isRealtime = false) {
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
        } else if (type === 'success') {
            icon = '✅';
        } else if (type === 'error') {
            icon = '❌';
        } else {
            icon = 'ℹ️';
        }

        notification.innerHTML = `
            ${icon} <strong>${isRealtime ? 'Cập nhật trạng thái:' : 'Thông báo:'}</strong> ${message}
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
    window.realtimeUserOrderManager = new RealtimeUserOrderManager();
});

// Add CSS animations (only if not already added)
if (!document.querySelector('#realtime-user-styles')) {
    const style = document.createElement('style');
    style.id = 'realtime-user-styles';
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
}
