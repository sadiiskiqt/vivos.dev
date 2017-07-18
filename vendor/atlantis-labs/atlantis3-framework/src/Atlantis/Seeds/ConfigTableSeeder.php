<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('config')->insert($row);
    }
  }

  private function getData() {

    $date = \Carbon\Carbon::now()->toDateTimeString();

    return [
        [
            'config_key' => 'site_name',
            'config_value' => serialize('Atlantis'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'include_title',
            'config_value' => serialize(1),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'domain_name',
            'config_value' => serialize(''),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'frontend_shell_view',
            'config_value' => serialize('page/shell'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'admin_items_per_page',
            'config_value' => serialize(25),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'default_language',
            'config_value' => serialize('en'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'cache_lifetime',
            'config_value' => serialize(3600),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'show_shortcut_bar',
            'config_value' => serialize(FALSE),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'cache',
            'config_value' => serialize(FALSE),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'allowed_max_filesize',
            'config_value' => serialize(4),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'allowed_image_extensions',
            'config_value' => serialize(['gif', 'png', 'jpg', 'jpeg']),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'allowed_others_extensions',
            'config_value' => serialize(['pdf', 'txt']),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'static_images',
            'config_value' => serialize([
                'Static Header' => [
                    'fullsize' => ['width' => 1024, 'height' => 768, 'crop' => FALSE],
                    'thumbnail' => ['width' => 200, 'height' => 150, 'crop' => TRUE]
                ]
            ]),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'responsive_images',
            'config_value' => serialize([
                'Responsive Header' => [
                    'desktop' => ['width' => 1024, 'height' => 768, 'crop' => FALSE],
                    'tablet' => ['width' => 640, 'height' => 480, 'crop' => FALSE],
                    'phone' => ['width' => 320, 'height' => 240, 'crop' => FALSE],
                    'thumbnail' => ['width' => 150, 'height' => 150, 'crop' => TRUE]
                ],
                'Responsive Gallery' => [
                    'desktop' => ['width' => 1024, 'height' => 768, 'crop' => FALSE],
                    'tablet' => ['width' => 640, 'height' => 480, 'crop' => FALSE],
                    'phone' => ['width' => 320, 'height' => 240, 'crop' => FALSE],
                    'thumbnail' => ['width' => 300, 'height' => 300, 'crop' => TRUE]
                ]
            ]),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'responsive_breakpoints',
            'config_value' => serialize(['large' => 1200, 'medium' => 800]),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'default_styles',
            'config_value' => serialize(''),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'default_scripts',
            'config_value' => serialize(''),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'excluded_scripts',
            'config_value' => serialize(''),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'default_404_view',
            'config_value' => serialize('page/404'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'default_meta_keywords',
            'config_value' => serialize('default words'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'default_meta_description',
            'config_value' => serialize('My default meta info'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'modules_dir',
            'config_value' => serialize('/modules/'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'user_media_upload',
            'config_value' => serialize('resources/media/user/'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'theme_path',
            'config_value' => serialize('resources/themes/theme101'),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'amazon_s3_url',
            'config_value' => serialize(''),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'amazon_cloudfront_url',
            'config_value' => serialize(''),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'use_amazon_s3',
            'config_value' => serialize(FALSE),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'use_amazon_cdn',
            'config_value' => serialize(FALSE),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 's3_sync_dirs',
            'config_value' => serialize(array()),
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'config_key' => 'delete_local_file',
            'config_value' => serialize(FALSE),
            'created_at' => $date,
            'updated_at' => $date
        ]
    ];
  }

}
