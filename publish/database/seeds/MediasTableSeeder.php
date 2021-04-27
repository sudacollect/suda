<?php

use Illuminate\Database\Seeder;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;

class MediasTableSeeder extends Seeder
{
    public function run()
    {
        $media = Media::firstOrNew([
            'name' => 'logo',
        ]);
        if (!$media->exists) {
            $media->name     = 'logo';
            $media->user_id  = 1;
            $media->user_type  = 'operate';
            $media->type     = 'PNG';
            $media->size     = '115056';
            $media->path    = 'public/images/demo/journey.png';

            $media->save();
        }
        
        $mediatable = Mediatable::firstOrNew([
            'media_id' => 1,
        ]);
        if (!$mediatable->exists) {
            $mediatable->media_id     = 1;
            $mediatable->mediatable_id  = 1;
            $mediatable->mediatable_type  = 'Gtd\Suda\Models\Page';
            $mediatable->position     = 'hero_image';
            
            $mediatable->save();
        }
    }
}
