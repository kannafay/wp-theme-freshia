import initHeadScripts from './main-head.js';
import themeMode from './modules/theme-mode.js';
import wpAjax from './packages/wp-ajax.js';
import wpRest from './packages/wp-rest.js';

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

// WP AJAX 请求测试
function fetchTest() {
    const btns = document.querySelectorAll('.request');

    btns.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            console.time('请求用时');
            e.preventDefault();
            const id = e.target.id;
            let req, res, data = {
                name: 'kanna',
                age: 18,
                hobby: ['reading', 'coding', 'gaming'],
                game: {
                    lol: 'League of Legends',
                    dota: 'Dota 2',
                }
            };

            const formData = new FormData(document.querySelector('form'));
            let isEmpty = true;

            for (const [key, value] of formData.entries()) {
                if (value instanceof File && value.name) {
                    isEmpty = false;
                    break;
                }
            }
            
            data = isEmpty ? data : formData;

            // ----- AJAX 请求 -----
            if (id === 'ajax') {
                req = 'WP AJAX';
                res = await wpAjax.post('ajax_test', { data });
            }

            // ----- REST API 请求 -----
            if (id === 'rest') {
                req = 'WP REST API';
                res = await wpRest.post('freshia/v1/rest_test', data);
            }

            console.log(req, '请求成功:', res);
            console.timeEnd('请求用时');
        });
    });

    // ----- REST API 自动请求（页面加载完成后） -----
    const data = new FormData();
    data.set('id', 1);
    data.set('page', 2);
    data.set('name', 'Kanna');
    wpRest.post('freshia/v1/rest_args', {
        id: 1,
        page: 2,
        name: 'Kanna'
    }).then(res => {
        console.log('WP REST API 自动请求成功:', res);
    })
}