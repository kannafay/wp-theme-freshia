/**
 * WordPress AJAX 功能模块（增强版）
 * 
 * 支持：
 * - GET / POST
 * - FormData, URLSearchParams, Object（可使用 JSON 请求类型） 三种数据格式
 * - 自动添加 action 和 _wpnonce
 * - 超时中止（默认 30000ms）
 * - 相同请求自动取消前一个（防止重复提交）
 * 
 * 参数：
 * - action: 必须，WordPress AJAX action 名称
 * - data: 可选，发送的数据，支持 FormData, URLSearchParams 和 Object 三种格式
 * - method: 可选，HTTP 方法，默认为 'POST'，可选 'GET'
 * - headers: 可选，自定义请求头（支持 JSON 请求类型），默认为空 Headers 对象
 * - timeout: 可选，请求超时时间，单位毫秒，默认为 30000ms
 */

const wpAjax = (() => {
    // 用于跟踪每个 action + method + dataKey 的请求控制器
    const pendingRequests = {};

    async function request(options) {
        // console.log(options);

        let { action, method, data, headers, timeout, isJSON } = options;

        if (!action) {
            return Promise.reject(new Error('wpAjax: action 参数不能为空'));
        }

        if (!['GET', 'POST'].includes(method.toUpperCase())) {
            return Promise.reject(new Error('wpAjax: 只支持 GET 和 POST 请求'));
        }

        if (method === 'GET' && isJSON) {
            return Promise.reject(new Error('wpAjax: GET 请求不支持 JSON 格式的 data'));
        }

        data = data || null;
        timeout = timeout || 30000;
        headers = new Headers(headers || {});

        const ajax_url = freshia.ajax_url || '/wp-admin/admin-ajax.php';
        const nonce = freshia.ajax_nonce || '';
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

        let url, config;

        // ---- GET 请求 ----
        if (method === 'GET') {
            switch (dataType) {
                case '[object URLSearchParams]':
                    data.set('action', action);
                    nonce && data.set('_wpnonce', nonce);
                    break;

                case '[object FormData]':
                    return Promise.reject(new Error('wpAjax: GET 请求不支持 FormData 类型的 data'));

                case '[object Object]':
                    data = new URLSearchParams(data);
                    data.set('action', action);
                    nonce && data.set('_wpnonce', nonce);
                    break;
                default:
                    const wpnonce = nonce ? `&_wpnonce=${encodeURIComponent(nonce)}` : '';
                    data = `action=${encodeURIComponent(action)}${wpnonce}`;
            }

            url = `${ajax_url}?${data.toString()}`;
            config = {
                method: 'GET',
                headers,
                signal: controller.signal,
            };

            // ---- POST 请求 ----    
        } else if (method === 'POST') {
            switch (dataType) {
                case '[object URLSearchParams]':
                    return Promise.reject(new Error('wpAjax: POST 请求不支持 URLSearchParams 类型的 data'));

                case '[object FormData]':
                    if (isJSON) {
                        return Promise.reject(new Error('wpAjax: POST 请求不支持同时使用 JSON 格式的 headers 和 FormData 类型的 data'));
                    }
                    data.set('action', action);
                    nonce && data.set('_wpnonce', nonce);
                    break;

                case '[object Object]':
                    if (isJSON) {
                        data = JSON.stringify(data);
                    } else {
                        const dataObject = nonce ? { ...data, action, _wpnonce: nonce } : { ...data, action };

                        // const formData = new FormData();
                        // for (const [key, value] of Object.entries(dataObject)) {
                        //     formData.append(key, value);
                        // }
                        // data = formData;

                        data = objectToFormData(dataObject);
                    }
                    break;

                default:
                    if (isJSON) {
                        return Promise.reject(new Error('wpAjax: POST 请求不支持非 Object 类型的 data'));
                    }
                    data = new FormData();
                    data.set('action', action);
                    nonce && data.set('_wpnonce', nonce);
            }

            url = ajax_url;
            if (isJSON) {
                url = `${url}?action=${encodeURIComponent(action)}&_wpnonce=${encodeURIComponent(nonce)}`;
                headers.set('Content-Type', 'application/json; charset=UTF-8');
            }
            config = {
                method: 'POST',
                body: data,
                headers,
                signal: controller.signal,
            };
        } else {
            return Promise.reject(new Error('wpAjax: 只支持 GET 和 POST 请求'));
        }

        return fetch(url, config).then(async response => {
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

    // 辅助函数：将普通对象转换为 FormData（支持嵌套对象和数组）
    function objectToFormData(obj, formData = new FormData(), parentKey = '') {
        for (const key in obj) {
            if (obj.hasOwnProperty(key)) {
                const value = obj[key];
                const formKey = parentKey ? `${parentKey}[${key}]` : key; // 处理嵌套字段名
                // 处理 null/undefined（跳过）
                if (value == null) continue;
                // 处理数组/对象（递归）
                if (typeof value === 'object' && !(value instanceof File)) {
                    objectToFormData(value, formData, formKey);
                }
                // 处理文件（直接添加）
                else if (value instanceof File) {
                    formData.append(formKey, value);
                }
                // 处理其他类型（转字符串）
                else {
                    formData.append(formKey, String(value));
                }
            }
        }
        return formData;
    }

    return {
        request,
        get: (action, options, isJSON) => request({ action, ...options, isJSON, method: 'GET' }),
        post: (action, options, isJSON) => request({ action, ...options, isJSON, method: 'POST' }),
    };
})();

export default wpAjax;