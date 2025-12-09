// Vite JS entrypoint
// Add global scripts or imports here

// Example: set up axios defaults if used
import axios from 'axios';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

