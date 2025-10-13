/**
 * WordPress REST API 通用封装
 * 
 * 用法：
 * wpRest.get('freshia/v1/test')
 * wpRest.post('freshia/v1/save', { name: 'Kanna' })
 * 
 * 可通过 import 使用，也可直接挂载在 window 下。
 */

const wpRest = (() => {
    const base = window.freshia?.rest_url || '/wp-json/';
    const nonce = window.freshia?.rest_nonce || '';

    async function request(endpoint, { method = 'GET', body = null, headers = {} } = {}) {
        const url = endpoint.startsWith('http')
            ? endpoint
            : `${base}${endpoint.replace(/^\/+/, '')}`;

        const finalHeaders = { ...headers };
        if (nonce) {
            finalHeaders['X-WP-Nonce'] = nonce;
        }

        let payload = body;
        if (body instanceof FormData || body instanceof URLSearchParams) {
            // 让浏览器自动设置 Content-Type
        } else if (body && typeof body === 'object') {
            finalHeaders['Content-Type'] = 'application/json';
            payload = JSON.stringify(body);
        }

        const res = await fetch(url, {
            method,
            headers: finalHeaders,
            body: method !== 'GET' ? payload : undefined,
            credentials: 'include', // 让 cookie 生效
        });

        let data;
        try {
            data = await res.json();
        } catch {
            throw new Error(`Invalid JSON response from ${url}`);
        }

        if (!res.ok) {
            throw new Error(data.message || `HTTP Error ${res.status}`);
        }

        return data;
    }

    return {
        get(endpoint, params = {}) {
            const query = new URLSearchParams(params).toString();
            return request(query ? `${endpoint}?${query}` : endpoint, { method: 'GET' });
        },
        post(endpoint, body) {
            return request(endpoint, { method: 'POST', body });
        },
        patch(endpoint, body) {
            return request(endpoint, { method: 'PATCH', body });
        },
        put(endpoint, body) {
            return request(endpoint, { method: 'PUT', body });
        },
        delete(endpoint, body) {
            return request(endpoint, { method: 'DELETE', body });
        }
    };
})();

// 如果是模块环境
export default wpRest;

// 如果是浏览器直接引入
if (typeof window !== 'undefined') {
    window.wpRest = wpRest;
}
