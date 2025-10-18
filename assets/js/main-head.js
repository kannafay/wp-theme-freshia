/**
 * 预先执行的头部脚本
 */

import utils from './packages/utils.js';
import wpAjax from './packages/wp-ajax.js';
import wpRest from './packages/wp-rest.js';

export default function initHeadScripts() {
    Object.assign(window, {
        utils,
        wpAjax,
        wpRest,
    });
}

// 避免重复初始化
if (!window.__MAIN_HEAD_INITIALIZED__) {
    initHeadScripts();
    window.__MAIN_HEAD_INITIALIZED__ = true;
}
