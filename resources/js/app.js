// Vite JS entrypoint
// Add global scripts or imports here

// Example: set up axios defaults if used
import axios from 'axios';

// Performance optimizations
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.timeout = 10000; // 10 second timeout
axios.defaults.maxRedirects = 5;

// Enable HTTP compression
axios.defaults.headers.common['Accept-Encoding'] = 'gzip, deflate, br';

// Connection keep-alive
axios.defaults.headers.common['Connection'] = 'keep-alive';

// Configure CSRF token for axios and fetch requests
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
    
    // Also set up for fetch API
    window.fetch = new Proxy(window.fetch, {
        apply(target, thisArg, argumentsList) {
            const [url, options = {}] = argumentsList;
            
            // Add CSRF token to all non-GET requests
            if (!options.method || options.method.toUpperCase() !== 'GET') {
                options.headers = {
                    ...(options.headers || {}),
                    'X-CSRF-TOKEN': csrfToken.content,
                };
            }
            
            return Reflect.apply(target, thisArg, [url, options]);
        },
    });
}

// Expose to window for convenience
window.axios = axios;

