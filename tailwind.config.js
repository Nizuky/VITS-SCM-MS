/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./resources/**/*.blade.php',
		'./resources/**/*.js',
		'./resources/**/*.vue',
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
