/**
 * WordPress AJAX 功能模块（增强版）
 * 
 * 支持：
 * - GET / POST
 * - FormData, URLSearchParams, Object 三种数据格式
 * - 自动添加 action 和 _wpnonce
 * - 超时中止（默认 30000ms）
 * - 相同请求自动取消前一个（防止重复提交）
 * 
 * 参数：
 * - action: 必须，WordPress AJAX action 名称
 * - data: 可选，发送的数据，支持 FormData, URLSearchParams 和 普通对象三种格式
 * - method: 可选，HTTP 方法，默认为 'POST'，可选 'GET'
 * - headers: 可选，自定义请求头，默认为空 Headers 对象
 * - timeout: 可选，请求超时时间，单位毫秒，默认为 30000ms
 */

const wpAjax = (() => {
    // 用于跟踪每个 action + method + dataKey 的请求控制器
    const pendingRequests = {};

    async function request(options) {
        let { action, data, method, headers, timeout } = options;

        if (!action) {
            return Promise.reject(new Error('wpAjax: action 参数不能为空'));
        }

        data = data || null;
        timeout = timeout || 30000;
        headers = headers || new Headers();

        const ajax_url = freshia.ajax_url || '/wp-admin/admin-ajax.php';
        const ajax_nonce = freshia.ajax_nonce || '';
        const dataType = Object.prototype.toString.call(data);

        // 生成请求唯一 key（同 action + method + data 会视为同一个请求）
        const dataKey = `${action}:${method}:${JSON.stringify(data)}`;

        // 若有相同请求在进行中，则取消它
        if (pendingRequests[dataKey]) {
            pendingRequests[dataKey].abort();
            // console.warn(`wpAjax: 已取消前一个相同请求 (${action})`);
        }

        // 创建新的 AbortController 并记录
        const controller = new AbortController();
        controller.abortReason = null;
        pendingRequests[dataKey] = controller;

        // 超时自动中止
        const timeoutId = setTimeout(() => {
            controller.abortReason = 'timeout';
            controller.abort();
        }, timeout);

        const clearPending = () => {
            clearTimeout(timeoutId);
            if (pendingRequests[dataKey] === controller) {
                delete pendingRequests[dataKey];
            }
        };

        // ---- GET 请求 ----
        if (method === 'GET') {
            switch (dataType) {
                case '[object URLSearchParams]':
                    data.set('action', action);
                    ajax_nonce && data.set('_wpnonce', ajax_nonce);
                    data = data.toString();
                    break;

                case '[object FormData]':
                    data.set('action', action);
                    ajax_nonce && data.set('_wpnonce', ajax_nonce);
                    data = new URLSearchParams(data).toString();
                    console.warn('GET 请求不建议使用 FormData，已转换为 URLSearchParams');
                    break;

                case '[object Object]':
                    data = ajax_nonce ? { ...data, action, _wpnonce: ajax_nonce } : { ...data, action };
                    data = new URLSearchParams(data).toString();
                    break;

                default:
                    const wpnonce = ajax_nonce ? `&_wpnonce=${encodeURIComponent(ajax_nonce)}` : '';
                    data = `action=${encodeURIComponent(action)}${wpnonce}`;
            }

            headers.set('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

            return fetch(`${ajax_url}?${data}`, {
                method: 'GET',
                headers,
                signal: controller.signal
            }).then(async response => {
                clearPending();
                if (!response.ok) {
                    const error = new Error(`HTTP error! status: ${response.status}`);
                    error.response = response;
                    throw error;
                }
                return response.json();
            }).catch(error => {
                clearPending();
                if (error.name === 'AbortError') {
                    let reason = controller.abortReason || 'manual';
                    let msg =
                        reason === 'timeout'
                            ? `Request timed out after ${timeout}ms`
                            : `Request manually aborted`;
                    const newError = new Error(msg);
                    newError.name = 'AbortError';
                    newError.reason = controller.abortReason;
                    console.warn('wpAjax Error:', newError);
                    throw newError;
                }
                console.error('wpAjax Error:', error);
                throw error;
            });

        }

        // ---- POST 请求 ----
        else {
            switch (dataType) {
                case '[object URLSearchParams]':
                    return Promise.reject(new Error('POST 请求不支持 URLSearchParams，请使用 FormData 或 Object'));

                case '[object FormData]':
                    data.set('action', action);
                    ajax_nonce && data.set('_wpnonce', ajax_nonce);
                    break;

                case '[object Object]':
                    const dataObject = ajax_nonce ? { ...data, action, _wpnonce: ajax_nonce } : { ...data, action };
                    const formData = new FormData();
                    for (const [key, value] of Object.entries(dataObject)) {
                        formData.set(key, value);
                    }
                    data = formData;
                    break;

                default:
                    data = new FormData();
                    data.set('action', action);
                    ajax_nonce && data.set('_wpnonce', ajax_nonce);
            }

            return fetch(ajax_url, {
                method: 'POST',
                body: data,
                headers,
                signal: controller.signal
            }).then(async response => {
                clearPending();
                if (!response.ok) {
                    const error = new Error(`HTTP error! status: ${response.status}`);
                    error.response = response;
                    throw error;
                }
                return response.json();
            }).catch(error => {
                clearPending();
                if (error.name === 'AbortError') {
                    let reason = controller.abortReason || 'manual';
                    let msg =
                        reason === 'timeout'
                            ? `Request timed out after ${timeout}ms`
                            : `Request manually aborted`;
                    const newError = new Error(msg);
                    newError.name = 'AbortError';
                    newError.reason = controller.abortReason;
                    console.warn('wpAjax Error:', newError);
                    throw newError;
                }
                console.error('wpAjax Error:', error);
                throw error;
            });
        }
    }

    return {
        request,
        get: (options) => request({ ...options, method: 'GET' }),
        post: (options) => request({ ...options, method: 'POST' }),
    };
})();

export default wpAjax;
