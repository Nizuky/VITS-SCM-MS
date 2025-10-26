/**
 * Session Keeper - Keeps sessions alive and CSRF tokens fresh
 * Prevents session expiration and token mismatch errors
 * Works for Student, Admin, and Super Admin dashboards
 */

(function() {
    'use strict';

    const SessionKeeper = {
        config: {
            // Refresh CSRF token every 5 minutes (more frequent)
            csrfRefreshInterval: 5 * 60 * 1000,
            // Keep session alive every 2 minutes (more frequent)
            sessionKeepAliveInterval: 2 * 60 * 1000,
            // Auto-refresh data every 30 seconds (configurable per page)
            dataRefreshInterval: 30 * 1000,
            // Enable auto refresh by default
            autoRefreshEnabled: false,
            // Callbacks for data refresh
            onDataRefresh: null,
            // Debug mode
            debug: false
        },

        timers: {
            csrf: null,
            session: null,
            data: null
        },

        /**
         * Initialize the session keeper
         */
        init: function(options = {}) {
            // Merge custom options
            Object.assign(this.config, options);
            
            this.log('Session Keeper initialized');
            
            // Immediately ping session to restore markers on page load
            this.pingSession();
            
            // Immediately refresh CSRF token
            this.refreshCsrfToken();
            
            // Start CSRF token refresh
            this.startCsrfRefresh();
            
            // Start session keep-alive
            this.startSessionKeepAlive();
            
            // Start auto data refresh if enabled
            if (this.config.autoRefreshEnabled && this.config.onDataRefresh) {
                this.startDataRefresh();
            }
            
            // Handle visibility change (pause when tab is hidden)
            this.handleVisibilityChange();
            
            // Handle page refresh/reload
            this.handlePageRefresh();
            
            // Intercept all AJAX requests to update CSRF token
            this.interceptAjaxRequests();
            
            // Update all forms with fresh token periodically
            this.updateFormsWithToken();
        },

        /**
         * Log messages if debug is enabled
         */
        log: function(message, data = null) {
            if (this.config.debug) {
                console.log('[SessionKeeper]', message, data || '');
            }
        },

        /**
         * Start CSRF token refresh
         */
        startCsrfRefresh: function() {
            const self = this;
            
            this.timers.csrf = setInterval(function() {
                self.refreshCsrfToken();
            }, this.config.csrfRefreshInterval);
            
            this.log('CSRF refresh started', `Interval: ${this.config.csrfRefreshInterval}ms`);
        },

        /**
         * Refresh CSRF token from server
         */
        refreshCsrfToken: function() {
            const self = this;
            
            fetch('/api/refresh-csrf', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    self.updateCsrfToken(data.token);
                    self.log('CSRF token refreshed', data.token.substring(0, 10) + '...');
                }
            })
            .catch(error => {
                self.log('Error refreshing CSRF token', error);
            });
        },

        /**
         * Update CSRF token in meta tag and all forms
         */
        updateCsrfToken: function(newToken) {
            // Update meta tag
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.setAttribute('content', newToken);
            }
            
            // Update all hidden CSRF input fields
            const csrfInputs = document.querySelectorAll('input[name="_token"]');
            csrfInputs.forEach(function(input) {
                input.value = newToken;
            });
            
            // Update window.axios if it exists
            if (typeof window.axios !== 'undefined') {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken;
            }
        },

        /**
         * Start session keep-alive ping
         */
        startSessionKeepAlive: function() {
            const self = this;
            
            this.timers.session = setInterval(function() {
                self.pingSession();
            }, this.config.sessionKeepAliveInterval);
            
            this.log('Session keep-alive started', `Interval: ${this.config.sessionKeepAliveInterval}ms`);
        },

        /**
         * Ping server to keep session alive
         */
        pingSession: function() {
            const self = this;
            
            fetch('/api/ping', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                self.log('Session pinged successfully', data);
                
                // If markers were restored, log it
                if (data.markers_restored) {
                    self.log('Session markers restored by ping endpoint');
                }
            })
            .catch(error => {
                self.log('Error pinging session', error);
                
                // If ping fails, try to refresh CSRF token as fallback
                self.refreshCsrfToken();
            });
        },

        /**
         * Start auto data refresh
         */
        startDataRefresh: function() {
            const self = this;
            
            this.timers.data = setInterval(function() {
                if (self.config.onDataRefresh && typeof self.config.onDataRefresh === 'function') {
                    self.log('Auto-refreshing data');
                    self.config.onDataRefresh();
                }
            }, this.config.dataRefreshInterval);
            
            this.log('Auto data refresh started', `Interval: ${this.config.dataRefreshInterval}ms`);
        },

        /**
         * Get current CSRF token
         */
        getCsrfToken: function() {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            return metaTag ? metaTag.getAttribute('content') : '';
        },

        /**
         * Handle visibility change (pause when tab is hidden)
         */
        handleVisibilityChange: function() {
            const self = this;
            
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    self.log('Tab hidden - pausing timers');
                    self.pauseTimers();
                } else {
                    self.log('Tab visible - resuming timers');
                    self.resumeTimers();
                    // Immediately refresh token and session when tab becomes visible
                    self.refreshCsrfToken();
                    self.pingSession();
                }
            });
        },

        /**
         * Handle page refresh/reload to ensure session persists
         */
        handlePageRefresh: function() {
            const self = this;
            
            // Before page unload, ping session one more time
            window.addEventListener('beforeunload', function() {
                // Use sendBeacon for reliable delivery even during page unload
                const token = self.getCsrfToken();
                const data = JSON.stringify({});
                const blob = new Blob([data], { type: 'application/json' });
                
                // Try to send a final ping
                if (navigator.sendBeacon) {
                    navigator.sendBeacon('/api/ping', blob);
                }
            });
            
            // On page load, immediately restore session
            window.addEventListener('load', function() {
                self.pingSession();
                self.refreshCsrfToken();
            });
        },

        /**
         * Pause all timers
         */
        pauseTimers: function() {
            if (this.timers.csrf) clearInterval(this.timers.csrf);
            if (this.timers.session) clearInterval(this.timers.session);
            if (this.timers.data) clearInterval(this.timers.data);
        },

        /**
         * Resume all timers
         */
        resumeTimers: function() {
            this.startCsrfRefresh();
            this.startSessionKeepAlive();
            if (this.config.autoRefreshEnabled && this.config.onDataRefresh) {
                this.startDataRefresh();
            }
        },

        /**
         * Intercept AJAX requests to always use fresh CSRF token
         */
        interceptAjaxRequests: function() {
            const self = this;
            
            // Intercept fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                options.headers = options.headers || {};
                
                // Add CSRF token to POST, PUT, DELETE, PATCH requests
                if (options.method && ['POST', 'PUT', 'DELETE', 'PATCH'].includes(options.method.toUpperCase())) {
                    if (typeof options.headers.append === 'function') {
                        options.headers.append('X-CSRF-TOKEN', self.getCsrfToken());
                    } else {
                        options.headers['X-CSRF-TOKEN'] = self.getCsrfToken();
                    }
                }
                
                return originalFetch.apply(this, [url, options]);
            };
            
            // Intercept XMLHttpRequest
            const originalOpen = XMLHttpRequest.prototype.open;
            const originalSend = XMLHttpRequest.prototype.send;
            
            XMLHttpRequest.prototype.open = function(method, url) {
                this._method = method;
                this._url = url;
                return originalOpen.apply(this, arguments);
            };
            
            XMLHttpRequest.prototype.send = function() {
                if (this._method && ['POST', 'PUT', 'DELETE', 'PATCH'].includes(this._method.toUpperCase())) {
                    this.setRequestHeader('X-CSRF-TOKEN', self.getCsrfToken());
                }
                return originalSend.apply(this, arguments);
            };
            
            this.log('AJAX interceptors installed');
        },

        /**
         * Update all forms with current token
         */
        updateFormsWithToken: function() {
            const self = this;
            
            setInterval(function() {
                const token = self.getCsrfToken();
                const csrfInputs = document.querySelectorAll('input[name="_token"]');
                csrfInputs.forEach(function(input) {
                    input.value = token;
                });
            }, 1000); // Update forms every second with current token
        },

        /**
         * Enable auto data refresh
         */
        enableAutoRefresh: function(callback, interval) {
            this.config.autoRefreshEnabled = true;
            this.config.onDataRefresh = callback;
            
            if (interval) {
                this.config.dataRefreshInterval = interval;
            }
            
            // Clear existing timer and start new one
            if (this.timers.data) {
                clearInterval(this.timers.data);
            }
            this.startDataRefresh();
        },

        /**
         * Disable auto data refresh
         */
        disableAutoRefresh: function() {
            this.config.autoRefreshEnabled = false;
            if (this.timers.data) {
                clearInterval(this.timers.data);
                this.timers.data = null;
            }
            this.log('Auto data refresh disabled');
        },

        /**
         * Manually refresh data
         */
        refreshData: function() {
            if (this.config.onDataRefresh && typeof this.config.onDataRefresh === 'function') {
                this.log('Manually refreshing data');
                this.config.onDataRefresh();
            }
        },

        /**
         * Destroy session keeper (cleanup)
         */
        destroy: function() {
            this.pauseTimers();
            this.log('Session Keeper destroyed');
        }
    };

    // Expose to window
    window.SessionKeeper = SessionKeeper;

    // Auto-initialize on DOM ready if not already initialized
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.SessionKeeper.timers.csrf) {
                window.SessionKeeper.init({
                    debug: false // Set to true for debugging
                });
            }
        });
    } else {
        if (!window.SessionKeeper.timers.csrf) {
            window.SessionKeeper.init({
                debug: false
            });
        }
    }
})();
