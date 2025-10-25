import '@/style.css'
import Alpine from 'alpinejs'
import Swup from 'swup'
import SwupProgressPlugin from '@swup/progress-plugin'
import SwupPreloadPlugin from '@swup/preload-plugin'
import SwupHeadPlugin from '@swup/head-plugin'
import SwupScriptsPlugin from '@swup/scripts-plugin'
import init from '@/init.js'
import gsap from 'gsap'
// import scrollReveal from '@/modules/scrollReveal.js'

// Alpine.js 初始化
window.Alpine = Alpine
Alpine.start()

// SwupProgressPlugin
const swupProgressPlugin = new SwupProgressPlugin({
    className: 'swup-progress-bar',
    transition: 300,
    delay: 300,
})
// SwupPreloadPlugin
const swupPreloadPlugin = new SwupPreloadPlugin()
// SwupHeadPlugin
const swupHeadPlugin = new SwupHeadPlugin({
    persistTags: [
        'style[data-vite-dev-id]',
        'link[rel="stylesheet"][data-vite-dev-id]',
    ],
})
// SwupScriptsPlugin
const swupScriptsPlugin = new SwupScriptsPlugin({
    head: false,
    body: true,
})
// Swup 实例
const swup = new Swup({
    animationSelector: false,
    linkSelector: 'a[href]:not([target="_blank"])',
    linkToSelf: 'navigate',
    hooks: {
        'link:click': () => {
            // console.log('link:click')
        },
        'visit:start': () => {
            // console.log('visit:start')
        },
        'content:replace': () => {
            // console.log('content:replace')
            init()
        },
        'page:view': () => {
            // console.log('page:view')
        },
    },
    plugins: [
        swupProgressPlugin,
        swupPreloadPlugin,
        swupHeadPlugin,
        swupScriptsPlugin,
    ],
})

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    init()
    // scrollReveal()
})

// 出场动画
swup.hooks.replace('animation:out:await', async () => {
    await gsap.to('main', {
        opacity: 0,
        duration: 0.2,
    })
})

// 入场动画
swup.hooks.replace('animation:in:await', async () => {
    // scrollReveal()
    await gsap.from('main', {
        opacity: 0,
        duration: 0.2,
    })
})
