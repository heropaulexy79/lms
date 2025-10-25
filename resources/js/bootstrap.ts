import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Set up CSRF token for axios
// const token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }
// Replace the CSRF token lookup with a typed meta element access

const token = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
if (token) {
  // HTMLMetaElement.content exists, but using getAttribute is also safe
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content') || '';
}
