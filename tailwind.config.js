const { addIconSelectors } = require("@iconify/tailwind");

/** @type {import('tailwindcss').Config} */
module.exports = {
	darkMode: 'class',
	content: [
		'./*.php',
		'./**/*.php',
		'./assets/js/*.js',
		'./blocks/build/**/*.js',
		// 排除不必要的目录
		'!./node_modules',
		'!./.git',
		'!./vendor',
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
