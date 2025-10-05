/**
 * 主题模式切换 功能模块
 * 
 * 支持浅色、深色和自动三种模式
 * 自动模式下根据系统主题自动切换
 * 
 * 用法:
 * setMode(mode) - 设置主题模式
 * getMode() - 获取当前主题模式
 * getSystemMode() - 获取当前系统主题模式
 * toggle() - 切换浅色/深色模式
 */

class ThemeSwitcher {
	static MODES = {
		LIGHT: 'light',
		DARK: 'dark',
		AUTO: 'auto',
	};

	constructor(storageKey = 'theme-mode') {
		this.storageKey = storageKey;
		this.media = window.matchMedia('(prefers-color-scheme: dark)');
		this.mode = this.getSavedMode();
		this.applyTheme(this.mode);

		// 自动模式下监听系统主题变化
		this.media.addEventListener('change', e => {
			if (this.mode === ThemeSwitcher.MODES.AUTO) {
				this.applyTheme(this.mode);
			}
		});
	}

	getSavedMode() {
		return localStorage.getItem(this.storageKey) || ThemeSwitcher.MODES.AUTO;
	}

	setMode(mode) {
		if (!Object.values(ThemeSwitcher.MODES).includes(mode)) {
			console.warn(`[ThemeSwitcher] 无效模式: ${mode}`);
			return;
		}
		this.mode = mode;
		localStorage.setItem(this.storageKey, mode);
		this.applyTheme(mode);
	}

	getMode() {
		return this.mode;
	}

	getSystemMode() {
		return this.media.matches ? ThemeSwitcher.MODES.DARK : ThemeSwitcher.MODES.LIGHT;
	}

	toggle() {
		const { LIGHT, DARK } = ThemeSwitcher.MODES;
		this.setMode(this.mode === LIGHT ? DARK : LIGHT);
	}

	applyTheme(mode) {
		const root = document.documentElement;
		const { LIGHT, DARK } = ThemeSwitcher.MODES;
		let isDark;
		switch (mode) {
			case LIGHT:
				isDark = false; break;
			case DARK:
				isDark = true; break;
			default:
				isDark = this.media.matches; break;
		}
		const hasDark = root.classList.contains('dark');
		if (isDark && !hasDark) root.classList.add('dark');
		else if (!isDark && hasDark) root.classList.remove('dark');
		root.style.colorScheme = isDark ? 'dark' : 'light';
	}
}

export default ThemeSwitcher;
