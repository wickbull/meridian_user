<?php

namespace Packages;

use Illuminate\Database\Eloquent\Model;

class GalleryAttachableItem extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gallery_attachable_items';


    /**
     *
     * @return App\GenericFile
     */
    public function photo()
    {
        return $this->hasOne('App\GenericFile', 'id', 'generic_file_id');
    }

    /**
     *
     * @return mixed
     */
    public function getPhoto($size = false)
    {
        if ($this->photo)
            return $this->photo->getThumb($size, $this->crop);

        return static_asset('src/img/img-cap.png');
    }
}
