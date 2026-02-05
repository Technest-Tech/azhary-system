<?php

return [
    /*
     * Here you can configure default Browsershot settings that will be applied
     * to all PDF generation. These settings can still be overridden using the
     * withBrowsershot() method on individual PDF instances.
     */
    'browsershot' => [
        /*
         * Configure the paths to Node.js, npm, Chrome, and other binaries.
         * Leave null to use system defaults or Browsershot's auto-detection.
         */
        'node_binary' => env('LARAVEL_PDF_NODE_BINARY', (function() {
            // Try common Node.js installation paths
            $home = getenv('HOME') ?: (getenv('USERPROFILE') ?: '');
            $paths = [
                $home . '/nodejs/bin/node',
                '/usr/local/bin/node',
                '/usr/bin/node',
            ];
            foreach ($paths as $path) {
                if (file_exists($path) && is_executable($path)) {
                    return $path;
                }
            }
            return null; // fallback to system PATH
        })()),
        'npm_binary' => env('LARAVEL_PDF_NPM_BINARY', (function() {
            // Try common npm installation paths
            $home = getenv('HOME') ?: (getenv('USERPROFILE') ?: '');
            $paths = [
                $home . '/nodejs/bin/npm',
                '/usr/local/bin/npm',
                '/usr/bin/npm',
            ];
            foreach ($paths as $path) {
                if (file_exists($path) && is_executable($path)) {
                    return $path;
                }
            }
            return null; // fallback to system PATH
        })()),
        'include_path' => env('LARAVEL_PDF_INCLUDE_PATH'),
        'chrome_path' => env('LARAVEL_PDF_CHROME_PATH'),
        'node_modules_path' => env('LARAVEL_PDF_NODE_MODULES_PATH', base_path('node_modules')),
        'bin_path' => env('LARAVEL_PDF_BIN_PATH'),
        'temp_path' => env('LARAVEL_PDF_TEMP_PATH'),

        /*
         * Other Browsershot configuration options.
         */
        'write_options_to_file' => env('LARAVEL_PDF_WRITE_OPTIONS_TO_FILE', false),
        'no_sandbox' => env('LARAVEL_PDF_NO_SANDBOX', false),
    ],
];
