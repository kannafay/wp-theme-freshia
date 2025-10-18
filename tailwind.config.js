const { addIconSelectors } = require("@iconify/tailwind");

/** @type {import('tailwindcss').Config} */
module.exports = {
	darkMode: 'class',
	content: [
		'./assets/js/**/*.js',
		'./blocks/build/**/*.{js,php}',
		'./template-parts/**/*.php',
		'./templates/**/*.php',
		'./modules/**/*.php',
		'./*.php',
		// 排除不必要的目录
		'!./**/node_modules/**',
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
