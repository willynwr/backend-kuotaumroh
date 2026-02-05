/**
 * Session Timeout Manager
 * Auto logout setelah 30 menit tidak ada activity
 * Track user activity: mouse move, click, keyboard, scroll
 */

class SessionTimeoutManager {
    constructor(options = {}) {
        this.timeoutDuration = options.timeoutDuration || 30 * 60 * 1000; // 30 minutes default
        this.warningDuration = options.warningDuration || 2 * 60 * 1000; // 2 minutes warning
        this.checkInterval = 5000; // Check every 5 seconds
        this.storageKey = 'last_activity';
        this.timeoutTimer = null;
        this.checkTimer = null;
        this.warningShown = false;
        this.logoutUrl = options.logoutUrl || '/';
        this.enabled = true;

        // Activity events to track
        this.activityEvents = [
            'mousedown',
            'mousemove', 
            'keypress',
            'scroll',
            'touchstart',
            'click'
        ];

        this.init();
    }

    init() {
        console.log('🕐 Session timeout manager initialized (30 min timeout)');
        
        // Set initial activity
        this.updateActivity();
        
        // Listen to activity events
        this.activityEvents.forEach(event => {
            document.addEventListener(event, () => this.updateActivity(), true);
        });

        // Listen to storage changes (for multi-tab sync)
        window.addEventListener('storage', (e) => {
            if (e.key === this.storageKey) {
                this.resetWarning();
            }
        });

        // Start checking
        this.startChecking();

        // Handle page visibility
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.checkTimeout();
            }
        });
    }

    updateActivity() {
        if (!this.enabled) return;
        
        const now = Date.now();
        localStorage.setItem(this.storageKey, now.toString());
        this.resetWarning();
    }

    getLastActivity() {
        const stored = localStorage.getItem(this.storageKey);
        return stored ? parseInt(stored) : Date.now();
    }

    getIdleTime() {
        return Date.now() - this.getLastActivity();
    }

    startChecking() {
        this.checkTimer = setInterval(() => {
            this.checkTimeout();
        }, this.checkInterval);
    }

    checkTimeout() {
        if (!this.enabled) return;

        const idleTime = this.getIdleTime();
        const timeUntilLogout = this.timeoutDuration - idleTime;

        // Show warning
        if (!this.warningShown && timeUntilLogout <= this.warningDuration && timeUntilLogout > 0) {
            this.showWarning(timeUntilLogout);
        }

        // Auto logout
        if (idleTime >= this.timeoutDuration) {
            this.logout();
        }
    }

    showWarning(timeLeft) {
        this.warningShown = true;
        const minutes = Math.ceil(timeLeft / 60000);
        
        console.warn(`⚠️ Session akan berakhir dalam ${minutes} menit`);

        // Create warning toast/modal
        const warningEl = document.createElement('div');
        warningEl.id = 'session-warning';
        warningEl.className = 'fixed top-4 right-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-lg z-[9999] max-w-md';
        warningEl.innerHTML = `
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-yellow-800">Sesi Hampir Berakhir</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        Sesi Anda akan berakhir dalam <strong>${minutes} menit</strong> karena tidak ada aktivitas.
                    </p>
                    <button onclick="sessionTimeoutManager.extendSession()" class="mt-3 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
                        Perpanjang Sesi
                    </button>
                </div>
                <button onclick="sessionTimeoutManager.closeWarning()" class="text-yellow-400 hover:text-yellow-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        
        // Remove existing warning if any
        const existing = document.getElementById('session-warning');
        if (existing) existing.remove();
        
        document.body.appendChild(warningEl);
    }

    closeWarning() {
        const warningEl = document.getElementById('session-warning');
        if (warningEl) warningEl.remove();
        this.resetWarning();
    }

    extendSession() {
        this.updateActivity();
        this.closeWarning();
        console.log('✅ Session extended');
    }

    resetWarning() {
        this.warningShown = false;
        const warningEl = document.getElementById('session-warning');
        if (warningEl) warningEl.remove();
    }

    logout() {
        console.log('🔒 Auto logout - session timeout');
        
        // Clear all activity tracking
        localStorage.removeItem(this.storageKey);
        
        // Clear auth data
        localStorage.removeItem('authToken');
        localStorage.removeItem('userId');
        localStorage.removeItem('userRole');
        
        // Show logout message
        const logoutMsg = document.createElement('div');
        logoutMsg.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10000]';
        logoutMsg.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Sesi Berakhir</h3>
                <p class="text-gray-600 mb-4">Sesi Anda telah berakhir karena tidak ada aktivitas selama 30 menit.</p>
                <p class="text-sm text-gray-500">Redirecting...</p>
            </div>
        `;
        document.body.appendChild(logoutMsg);
        
        // Stop checking
        clearInterval(this.checkTimer);
        
        // Redirect after 2 seconds
        setTimeout(() => {
            window.location.href = this.logoutUrl;
        }, 2000);
    }

    disable() {
        this.enabled = false;
        clearInterval(this.checkTimer);
        this.closeWarning();
        console.log('🔕 Session timeout disabled');
    }

    enable() {
        this.enabled = true;
        this.updateActivity();
        this.startChecking();
        console.log('🔔 Session timeout enabled');
    }
}

// Initialize global instance
let sessionTimeoutManager = null;

// Auto-initialize based on current page
function initSessionTimeout() {
    // Detect role from URL or localStorage
    const path = window.location.pathname;
    let logoutUrl = '/';
    
    if (path.startsWith('/admin')) {
        logoutUrl = '/admin/login';
    } else if (path.startsWith('/agent')) {
        logoutUrl = '/agent/login';
    } else if (path.includes('/dash/')) {
        // Affiliate or Freelance
        const userId = localStorage.getItem('userId');
        if (userId && userId.startsWith('AFT')) {
            logoutUrl = '/affiliate/login';
        } else if (userId && userId.startsWith('FRL')) {
            logoutUrl = '/freelance/login';
        }
    }
    
    sessionTimeoutManager = new SessionTimeoutManager({
        timeoutDuration: 30 * 60 * 1000, // 30 minutes
        warningDuration: 2 * 60 * 1000,  // 2 minutes warning
        logoutUrl: logoutUrl
    });
    
    // Make it globally accessible
    window.sessionTimeoutManager = sessionTimeoutManager;
}

// Auto-init when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSessionTimeout);
} else {
    initSessionTimeout();
}
