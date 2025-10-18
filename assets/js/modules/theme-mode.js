import ColorMode from '../packages/color-mode.js';

/**
 * 主题模式
 */
export default function themeMode() {
    const switcher = new ColorMode();
    // console.log(`当前主题模式: ${switcher.getMode()}`);
    // console.log(`当前系统模式: ${switcher.getSystemMode()}`);
    
    document.querySelector('.color-mode-toggle').addEventListener('click', () => {
        switcher.toggle();
        // console.log(`切换后主题模式: ${switcher.getMode()}`);
    });

    jQuery('.color-mode-btn').on('click', function() {
        const oldMode = switcher.getMode();
        const newMode = this.dataset.mode;
        if (!this.dataset.mode || oldMode == newMode) return;
        switcher.setMode(newMode);
        // console.log(`切换后主题模式: ${switcher.getMode()}`);
    });
}