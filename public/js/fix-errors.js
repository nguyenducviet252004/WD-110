// Fix for various JavaScript errors

// 1. Fix for Chart.js canvas context error
document.addEventListener('DOMContentLoaded', function() {
    // Wait for DOM to be ready before initializing charts
    setTimeout(function() {
        initializeCharts();
    }, 100);
});

function initializeCharts() {
    const chartElements = document.querySelectorAll('canvas[data-chart]');
    
    chartElements.forEach(function(canvas) {
        if (canvas && typeof Chart !== 'undefined') {
            try {
                const ctx = canvas.getContext('2d');
                if (ctx) {
                    // Chart is ready to be initialized
                    console.log('Chart canvas ready:', canvas);
                }
            } catch (error) {
                console.warn('Chart initialization error:', error);
                // Show fallback content
                showChartFallback(canvas);
            }
        }
    });
}

function showChartFallback(canvas) {
    const container = canvas.parentElement;
    if (container) {
        container.innerHTML = `
            <div class="error-fallback">
                <i class="mdi mdi-chart-line"></i>
                <p>Chart loading...</p>
                <small>If chart doesn't appear, please refresh the page</small>
            </div>
        `;
    }
}

// 2. Fix for jQuery cookie error
if (typeof $ !== 'undefined' && !$.cookie) {
    // Simple cookie fallback
    $.cookie = function(name, value, options) {
        if (typeof value === 'undefined') {
            // Get cookie
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        } else {
            // Set cookie
            options = options || {};
            let expires = '';
            if (options.expires && (typeof options.expires === 'number' || options.expires.toUTCString)) {
                let date;
                if (typeof options.expires === 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString();
            }
            const path = options.path ? '; path=' + options.path : '';
            const domain = options.domain ? '; domain=' + options.domain : '';
            const secure = options.secure ? '; secure' : '';
            document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
        }
    };
}

// 3. Fix for Pusher connection errors
function initializePusherWithFallback() {
    if (typeof Pusher !== 'undefined') {
        try {
            // Check if Pusher credentials are properly configured
            const pusherKey = 'fc8981add0d659cd3d8c';
            const pusherCluster = 'ap1';
            
            if (pusherKey && pusherKey !== 'your-pusher-key' && pusherCluster && pusherCluster !== 'your-cluster') {
                const pusher = new Pusher(pusherKey, {
                    cluster: pusherCluster,
                    encrypted: true
                });
                
                // Add connection status indicator
                addPusherStatusIndicator(pusher);
                
                return pusher;
            } else {
                console.warn('Pusher credentials not configured. Using fallback mode.');
                showPusherFallback();
                return null;
            }
        } catch (error) {
            console.error('Pusher initialization error:', error);
            showPusherFallback();
            return null;
        }
    } else {
        console.warn('Pusher library not loaded. Using fallback mode.');
        showPusherFallback();
        return null;
    }
}

function addPusherStatusIndicator(pusher) {
    // Create status indicator
    const statusDiv = document.createElement('div');
    statusDiv.className = 'pusher-status connecting';
    statusDiv.innerHTML = '<i class="mdi mdi-wifi"></i> Connecting...';
    document.body.appendChild(statusDiv);
    
    // Update status based on connection state
    pusher.connection.bind('connected', function() {
        statusDiv.className = 'pusher-status connected';
        statusDiv.innerHTML = '<i class="mdi mdi-wifi"></i> Connected';
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 3000);
    });
    
    pusher.connection.bind('disconnected', function() {
        statusDiv.className = 'pusher-status disconnected';
        statusDiv.innerHTML = '<i class="mdi mdi-wifi-off"></i> Disconnected';
        statusDiv.style.display = 'block';
    });
    
    pusher.connection.bind('connecting', function() {
        statusDiv.className = 'pusher-status connecting';
        statusDiv.innerHTML = '<i class="mdi mdi-wifi"></i> Connecting...';
        statusDiv.style.display = 'block';
    });
}

function showPusherFallback() {
    // Show fallback notification
    const fallbackDiv = document.createElement('div');
    fallbackDiv.className = 'alert alert-warning alert-dismissible fade show';
    fallbackDiv.style.cssText = 'position: fixed; top: 20px; left: 20px; z-index: 9999; max-width: 300px;';
    fallbackDiv.innerHTML = `
        <i class="mdi mdi-wifi-off"></i>
        <strong>Realtime Updates:</strong> Currently in offline mode. 
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(fallbackDiv);
    
    // Auto remove after 10 seconds
    setTimeout(() => {
        if (fallbackDiv.parentNode) {
            fallbackDiv.remove();
        }
    }, 10000);
}

// 4. Fix for missing storage images
function fixStorageImages() {
    const images = document.querySelectorAll('img[src*="/storage/"]');
    
    images.forEach(function(img) {
        img.addEventListener('error', function() {
            this.src = '/assets/images/placeholder.jpg';
            this.alt = 'Image not available';
            this.className += ' storage-image';
        });
    });
}

// 5. Fix for missing fonts
function fixMissingFonts() {
    // Add font loading indicators
    document.fonts.ready.then(function() {
        document.body.classList.add('fonts-loaded');
    });
    
    // Fallback for Material Design Icons
    if (!document.querySelector('link[href*="materialdesignicons"]')) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css';
        document.head.appendChild(link);
    }
}

// 6. Error handling for AJAX requests
function setupAjaxErrorHandling() {
    if (typeof $ !== 'undefined') {
        $(document).ajaxError(function(event, xhr, settings, error) {
            console.error('AJAX Error:', error);
            
            // Show user-friendly error message
            if (xhr.status === 404) {
                showNotification('❌ Resource not found. Please check the URL.', 'error');
            } else if (xhr.status === 500) {
                showNotification('❌ Server error. Please try again later.', 'error');
            } else if (xhr.status === 0) {
                showNotification('❌ Network error. Please check your connection.', 'error');
            }
        });
    }
}

// 7. Global notification function
function showNotification(message, type = 'info') {
    const container = document.getElementById('notification-container') || createNotificationContainer();
    
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
    if (type === 'success') icon = '✅';
    else if (type === 'error') icon = '❌';
    else icon = 'ℹ️';
    
    notification.innerHTML = `
        ${icon} <strong>${type === 'error' ? 'Error:' : 'Info:'}</strong> ${message}
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

function createNotificationContainer() {
    const container = document.createElement('div');
    container.id = 'notification-container';
    container.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    `;
    document.body.appendChild(container);
    return container;
}

// Initialize all fixes when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing error fixes...');
    
    // Initialize fixes
    initializeCharts();
    fixStorageImages();
    fixMissingFonts();
    setupAjaxErrorHandling();
    
    // Initialize Pusher with fallback
    const pusher = initializePusherWithFallback();
    
    // Add CSS animations
    const style = document.createElement('style');
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
    
    console.log('Error fixes initialized successfully!');
});

// Export functions for global use
window.ErrorFixes = {
    showNotification,
    initializePusherWithFallback,
    fixStorageImages
};
