// Debug script for user realtime orders
console.log('🔍 Debug: User realtime script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Debug: DOM loaded');
    
    // Check if Pusher is available
    if (typeof Pusher === 'undefined') {
        console.error('❌ Debug: Pusher library not loaded');
        return;
    }
    
    console.log('✅ Debug: Pusher library found');
    
    // Check user ID
    const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
    console.log('🔍 Debug: User ID:', userId);
    
    // Check order cards
    const orderCards = document.querySelectorAll('.card[data-order-id]');
    console.log('🔍 Debug: Found', orderCards.length, 'order cards');
    
    orderCards.forEach((card, index) => {
        const orderId = card.dataset.orderId;
        console.log(`🔍 Debug: Order card ${index + 1}:`, orderId);
    });
    
    // Test Pusher connection
    try {
        const pusher = new Pusher('fc8981add0d659cd3d8c', {
            cluster: 'ap1',
            encrypted: true
        });
        
        console.log('✅ Debug: Pusher object created');
        
        // Subscribe to orders channel
        const channel = pusher.subscribe('orders');
        console.log('✅ Debug: Subscribed to orders channel');
        
        // Listen for order status updates
        channel.bind('App\\Events\\OrderStatusUpdated', (data) => {
            console.log('🔄 Debug: Received order update:', data);
            
            // Check if this update is for current user
            if (userId && data.user_id == userId) {
                console.log('✅ Debug: Update is for current user');
                
                // Find the order card
                const orderCard = document.querySelector(`.card[data-order-id="${data.order_id}"]`);
                if (orderCard) {
                    console.log('✅ Debug: Found order card, updating...');
                    
                    // Update status badge
                    const statusBadge = orderCard.querySelector('.badge');
                    if (statusBadge) {
                        statusBadge.textContent = data.status_text;
                        console.log('✅ Debug: Updated status badge');
                    }
                    
                    // Show notification
                    showDebugNotification(`🔄 Đơn hàng #${data.order_id} đã được cập nhật thành "${data.status_text}"`);
                } else {
                    console.log('❌ Debug: Order card not found');
                }
            } else {
                console.log('ℹ️ Debug: Update is for different user');
            }
        });
        
        // Test connection status
        pusher.connection.bind('connected', function() {
            console.log('✅ Debug: Pusher connected');
        });
        
        pusher.connection.bind('disconnected', function() {
            console.log('❌ Debug: Pusher disconnected');
        });
        
        pusher.connection.bind('connecting', function() {
            console.log('🔄 Debug: Pusher connecting...');
        });
        
    } catch (error) {
        console.error('❌ Debug: Pusher error:', error);
    }
});

function showDebugNotification(message) {
    console.log('📢 Debug: Showing notification:', message);
    
    // Create notification container if it doesn't exist
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible fade show';
    notification.style.cssText = `
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 0.5rem;
        animation: slideInRight 0.3s ease-out;
    `;
    
    notification.innerHTML = `
        🔄 <strong>Realtime Update:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
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

// Add CSS animations (only if not already added)
if (!document.querySelector('#debug-realtime-styles')) {
    const style = document.createElement('style');
    style.id = 'debug-realtime-styles';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

console.log('🔍 Debug: User realtime debug script initialized');
