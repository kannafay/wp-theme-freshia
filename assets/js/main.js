import initHeadScripts from './main-head.js';
import themeMode from './modules/theme-mode.js';
import fetchTest from './modules/fetch-test.js';
import payTest from './modules/pay-test.js';

// 初始化 Pjax 进行无刷新页面加载
if (freshia.options.pjax) {
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const { Pjax } = window['pjax-api'];
            new Pjax({
                areas: ['main'],
                link: ':is(a)[href]:not([target], [data-fancybox])',
                form: ':is(form)[method="get"]',
            });

        } catch (e) {
            console.error('Failed to load Pjax:', e);
        }
    });

    document.addEventListener('pjax:ready', () => {
        initHeadScripts();
        initMainScripts();
    });
};

document.addEventListener('DOMContentLoaded', initMainScripts);
function initMainScripts() {
    themeMode();
    // fetchTest();
    payTest();
}
