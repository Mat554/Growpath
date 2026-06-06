import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Core CSS
                'resources/css/app.css',

                // Core JS
                'resources/js/app.js',

                // Auth pages
                'resources/js/auth/login.js',
                'resources/js/auth/register.js',
                'resources/js/auth/otp.js',
                'resources/js/auth/password.js',

                // Student pages
                'resources/js/student/dashboard.js',
                'resources/js/student/exam.js',
                'resources/js/student/kuesioner.js',
                'resources/js/student/report.js',
                'resources/js/student/profile.js',

                // Parent pages
                'resources/js/parent/dashboard.js',
                'resources/js/parent/dashboard-report.js',
                'resources/js/parent/report.js',
                'resources/js/parent/profile.js',

                // Admin pages
                'resources/js/admin/dashboard.js',
                'resources/js/admin/questions.js',
                'resources/js/admin/publisher.js',
                'resources/js/admin/monitoring.js',

                // Mobile pages
                'resources/js/mobile/student/dashboard.js',
                'resources/js/mobile/student/kuesioner.js',
                'resources/js/mobile/student/profile.js',
                'resources/js/mobile/parent/dashboard.js',
                'resources/js/mobile/parent/profile.js',
                'resources/js/mobile/admin/dashboard.js',
            ],
            refresh: true,
        }),
    ],

    // Build configuration
    build: {
        // Target modern browsers
        target: 'esnext',
        // Rollup options for code splitting
        rollupOptions: {
            output: {
                // Manual chunks for vendor splitting
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('axios')) {
                            return 'vendor-axios';
                        }
                        if (id.includes('lodash')) {
                            return 'vendor-lodash';
                        }
                        return 'vendor';
                    }
                },
            },
        },
    },
});
