<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración optimizada para DomPDF
    |--------------------------------------------------------------------------
    |
    | Esta configuración está optimizada para generar PDFs de manera más rápida
    | sacrificando algunas características avanzadas por velocidad.
    |
    */

    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => [
        /**
         * Configuraciones de rendimiento
         */
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => storage_path('app/public'),
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_font' => 'DejaVu Sans',
        'dpi' => 96,
        'enable_php' => false,
        'enable_javascript' => false,
        'enable_remote' => false,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => false,
        
        /**
         * Configuraciones de memoria y tiempo
         */
        'log_output_file' => null,
        'enable_css_float' => false,
        'enable_css_position' => false,
        
        /**
         * Configuraciones de depuración (desactivadas para producción)
         */
        'debug_png' => false,
        'debug_keep_temp' => false,
        'debug_css' => false,
        'debug_layout' => false,
        'debug_layout_lines' => false,
        'debug_layout_blocks' => false,
        'debug_layout_inline' => false,
        'debug_layout_padding_box' => false,
        
        /**
         * Configuraciones de seguridad
         */
        'is_remote_enabled' => false,
        'is_javascript_enabled' => false,
        'is_php_enabled' => false,
        'is_font_subsetting_enabled' => false,
        
        /**
         * Configuraciones de imagen
         */
        'enable_auto_image_resize' => true,
        'max_image_width' => 800,
        'max_image_height' => 600,
    ],
];
