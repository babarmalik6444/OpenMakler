const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        // App
        './resources/**/*.blade.php',
        // Filament
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // Filament colors
                danger: colors.red,
                primary: colors.emerald,
                success: colors.green,
                warning: colors.yellow,

                // Custom
                'oxford-blue': {
                    '50': '#f5f5f6',
                    '100': '#eaecee',
                    '200': '#cbcfd3',
                    '300': '#acb3b9',
                    '400': '#6d7985',
                    '500': '#2F4050',
                    '600': '#2a3a48',
                    '700': '#23303c',
                    '800': '#1c2630',
                    '900': '#171f27'
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
