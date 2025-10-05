/**
 * 预先执行的头部脚本
 */

export default function initHeadScripts() {
    
}
if (!window.__MAIN_HEAD_INITIALIZED__) {
    initHeadScripts();
    window.__MAIN_HEAD_INITIALIZED__ = true;
}