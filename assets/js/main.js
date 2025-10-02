import test from './modules/test.js';

// 初始化 Pjax 进行无刷新页面加载
if (option.pjax) {
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const { Pjax } = window['pjax-api'];
            new Pjax({
                areas: ['main'],
                link: ':is(a)[href]:not([target], [data-fancybox])',
                form: ':is(form)[method="get"]',
            });

        } catch (error) {
            console.error('Failed to load Pjax:', error);
        }
    });

    document.addEventListener('pjax:ready', () => {
        initHeadScripts();
        initMainScripts();
    });
};

document.addEventListener('DOMContentLoaded', initMainScripts);
function initMainScripts() {
    // 在这里初始化页面脚本
    test();
}
