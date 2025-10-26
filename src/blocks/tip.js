const SELECTOR = '.wp-block-freshia-tip'
const READY_FLAG = 'isReady'

const initTipBlock = (root = document) => {
    const scope = (root && typeof root.querySelectorAll === 'function') ? root : document
    scope.querySelectorAll(SELECTOR).forEach((tip) => {
        if (tip.dataset[READY_FLAG] === 'true') return
        tip.dataset[READY_FLAG] = 'true'

        tip.addEventListener('click', () => {
            console.log(tip.textContent)
        })
    })
}

export default initTipBlock
