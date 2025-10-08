/**
 * 预先执行的头部脚本
 */

import utils from './packages/utils.js';

export default function initHeadScripts() {
    window.utils = utils;
}

// 避免重复初始化
if (!window.__MAIN_HEAD_INITIALIZED__) {
    initHeadScripts();
    window.__MAIN_HEAD_INITIALIZED__ = true;
}
