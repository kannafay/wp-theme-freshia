import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
gsap.registerPlugin(ScrollTrigger)

class GsapScrollReveal {
    /**
     * @param {Object[]} configs 动画配置项
     * @param {string} configs[].selector 选择器
     * @param {Object} [configs[].from] 动画起始状态
     * @param {Object} [configs[].to] 动画结束状态
     * @param {number} [configs[].duration] 动画时长，单位秒
     * @param {string} [configs[].ease] 动画缓动函数
     * @param {Object} [options] 全局配置项
     * @param {number} [options.delay=0] 全局基础延迟步进
     */
    constructor(configs, options = {}) {
        // 清理旧的 ScrollTrigger 实例
        ScrollTrigger.getAll().forEach(st => st.kill())

        // 收集所有元素与配置
        let allElements = []

        configs.forEach(cfg => {
            const { selector, from, to, duration, ease } = cfg
            const elements = gsap.utils.toArray(selector)
            elements.forEach(el => allElements.push({ el, from, to, duration, ease }))

        })

        // 按 DOM 顺序排序
        allElements.sort((a, b) => {
            return a.el.compareDocumentPosition(b.el) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1
        })

        // 找到第一个可见元素索引
        const firstVisibleIndex = allElements.findIndex(item => ScrollTrigger.isInViewport(item.el))
        const startIndex = firstVisibleIndex >= 0 ? firstVisibleIndex : 0
        document.querySelector('.debug').textContent = `(${allElements.length}/${startIndex})`


        allElements = allElements.slice(startIndex)

        // 所有元素默认透明
        gsap.set(allElements.map(i => i.el), { opacity: 0 })

        // 批量 ScrollTrigger
        ScrollTrigger.batch(allElements.map(i => i.el), {
            start: 'top bottom',
            once: true,
            onEnter: batch => {
                batch.forEach((el, i) => {
                    const delay = i * (options.delay || 0)
                    const item = allElements.find(e => e.el === el)

                    // 默认动画
                    const defaultFrom = { opacity: 0 }
                    const defaultTo = { opacity: 1, duration: 1, ease: 'power4.inOut', delay }

                    // 合并用户配置
                    const finalFrom = { ...defaultFrom, ...(item.from || {}) }
                    const finalTo = {
                        ...defaultTo,
                        ...(item.to || {}),
                        ...(item.duration ? { duration: item.duration } : {}),
                        ...(item.ease ? { ease: item.ease } : {}),
                        delay,
                    }

                    gsap.fromTo(el, finalFrom, finalTo)
                })
            },
        })
    }
}

export default GsapScrollReveal