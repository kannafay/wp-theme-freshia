import initHeadScripts from './main-head.js';
import themeMode from './modules/theme-mode.js';
import wpAjax from './packages/wp-ajax.js';

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
    fetchTest();
}

// AJAX 请求测试
function fetchTest() {
    const btn = document.getElementById('fetch-test')
    if (!btn) return;
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const text = e.target.textContent;
        const res = wpAjax.get({
            action: 'get_action',
        })
        res.then(data => {
            console.log('请求成功:', data);
            e.target.textContent = text + ' ' + data.data.message;
        });
    });

}