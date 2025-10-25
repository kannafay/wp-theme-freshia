import js from '@eslint/js'
import globals from 'globals'
import pluginVue from 'eslint-plugin-vue'
import { defineConfig } from 'eslint/config'

export default defineConfig([
	{
		files: ['**/*.{js,mjs,cjs,vue}'],
		plugins: { js },
		extends: ['js/recommended'],
		languageOptions: { globals: globals.browser },
	},
	pluginVue.configs['flat/essential'],
	{
		rules: {
			'no-unused-vars': ['warn', { varsIgnorePattern: '^[A-Z_]' }],
			'quotes': ['warn', 'single', { avoidEscape: true }],
			'semi': ['warn', 'never'],
			'comma-dangle': ['warn', 'always-multiline'],
			'vue/multi-word-component-names': 'off',
			'vue/valid-template-root': 'off',
		},
	},
])
