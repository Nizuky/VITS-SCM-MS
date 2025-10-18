// Vite JS entrypoint
// Add global scripts or imports here

// Example: set up axios defaults if used
import axios from 'axios';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Expose to window for convenience
window.axios = axios;
