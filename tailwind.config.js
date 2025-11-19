/** @type {import('tailwindcss').Config} */

export default {
	// Configure dark mode to use data-theme attribute instead of .dark class
	darkMode: ['selector', '[data-theme="dark"]'],
	
	// tailwind.config.js - Updated Content
	content: [
		// Your existing paths:
		'./resources/**/*.blade.php',
		'./resources/**/*.js',
		'./resources/**/*.vue',
		
		// Recommended additions for Laravel:
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./storage/framework/views/*.php',
	],
	theme: {
		extend: {
			colors: {
				'background-light': '#EDF1FA',
				'primary-purple': '#6D28D9',
				'primary-purple-hover': '#5B21B6',
				'text-header': '#2B3674',
				'text-muted': '#707EAE',
				'badge-pending-text': '#E29C44',
				'badge-pending-bg': '#FAEAD0',
				'badge-verified-text': '#399552',
				'badge-verified-bg': '#CCEED6',
				'badge-rejected-text': '#CC525D',
				'badge-rejected-bg': '#FFD7DB',
			},
			fontFamily: {
				sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
			},
		},
	},
	plugins: [
		require('daisyui'),
	],
	daisyui: {
		themes: [
			{
				light: {
					...require('daisyui/src/theming/themes')['light'],
					primary: '#6D28D9',
				},
			},
		],
	},
}
