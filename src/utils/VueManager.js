/**
 * Vue 生命周期管理器（无全局注入）
 *
 * 特性：
 *  自动挂载/卸载 Vue 组件
 *  副作用清理由组件内部 onUnmounted 控制
 */

import { createApp } from 'vue'

const VM = (() => {
    let instances = []

    return {
        /**
         * 挂载 Vue 组件到指定选择器
         * @param {Object} Component - Vue 组件
         * @param {string} selector - 容器选择器
         * @param {Object} props - 组件 props
         * @param {boolean} forceUnmount - 是否强制卸载旧实例，默认 true
         */
        mount(Component, selector, props = {}, forceUnmount = true) {
            // 当无 props 参数时填入 true 表示需要卸载旧实例
            if (props === false) {
                props = {}
                forceUnmount = false
            }

            // 若该容器已有实例，判断是否卸载
            if (forceUnmount) {
                this.unmount(selector)
            } else {
                // 检查是否已有实例存在该容器
                const exist = instances.find(i => i.selector === selector)
                if (exist) return exist.app
            }

            const container = document.querySelector(selector)
            if (!container) return null

            const app = createApp(Component, props)
            const vm = app.mount(container)
            instances.push({ selector, app, vm })

            return app
        },

        /**
         * 卸载指定选择器的 Vue 实例
         * @param {string} selector
         */
        unmount(selector) {
            instances = instances.filter(item => {
                if (item.selector === selector) {
                    item.app.unmount()
                    return false
                }
                return true
            })
        },

        /**
         * 卸载所有实例
         */
        unmountAll() {
            instances.forEach(item => item.app.unmount())
            instances = []
        },
    }
})()

export default VM
