import VM from '@/utils/VueManager'
import initBlocks from '@/blocks'
import Footer from '@/components/Footer.vue'

export default function init() {
    // wp blocks
    const root = document.querySelector('[data-swup-container]') || document
    initBlocks(root)

    // vue conponents
    VM.mount(Footer, '#color-mode')
}
