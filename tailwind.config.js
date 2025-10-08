const { addIconSelectors } = require("@iconify/tailwind");

/** @type {import('tailwindcss').Config} */
module.exports = {
	darkMode: 'class',
	content: [
		'./*.php',
		'./**/*.php',
		'./assets/js/**/*.js',
		'./blocks/build/**/*.{js,php}',
		// 排除不必要的目录
		'!./**/node_modules/**',
		'!./assets/js/lib/**',
		'!./admin/**',
		'!./.git/**',
		'!./vendor/**',
	],
	theme: {
		extend: {
			colors: {
				
			}
		}
	},
	plugins: [
		addIconSelectors([
			'solar',
		]),
	],
}
