<?php namespace Packages;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;
use Packages\GenericFileAttachment;
use App\Helpers\Month;
use App\GlobalScopes\Translation\TranslationTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model {

    use Translatable, TranslationTrait, SoftDeletes;

    /**
     *
     * @var string
     */
    public $translationModel = '\Packages\DocumentTranslation';

    /**
     *
     * @var array
     */
    public $translatedAttributes = ['title'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['publish_at'];

    /**
     *
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('publish_at', 'DESC');
    }
    /**
     *
     */
    public function getPublishDate()
    {
        if (! $this->publish_at)  {
            return null;
        }

        $publishAt = $this->publish_at;

        $mounth = Month::create($publishAt->format('m'));

        return $publishAt->format('d') . " " . $mounth->getInGenitiveCase() . " " . $publishAt->format('Y');
    }
    /**
     *
     */
    public function getPublishYear()
    {
        if (! $this->publish_at)  {
            return null;
        }

        return $this->publish_at->format('Y');
    }
    /**
     *
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     *
     * @return string
     */
    public function getGenericThumbAttribute()
    {
        return GenericFileAttachment::makePrimaryKey('Packages\Document', $this->id, 'thumb_storage_id');
    }

    /**
     *
     * @return ackages\GenericFileAttachment
     */
    public function thumb()
    {
        return $this->hasOne('Packages\GenericFileAttachment', 'key', 'generic_thumb')->with('file');
    }
    /**
     *
     * @return mixed
     */
    public function getThumb($size = false)
    {
        if ($attachment_item = $this->thumb)
            return $attachment_item->file->getThumb($size, $attachment_item->crop);

        return ImagePlaceholder::cap($size);
    }

    public function file()
    {
        return $this->hasOne('App\GenericFile', 'id', 'file_storage_id');
    }

    public function getFileUrl()
    {
        if ($this->file)
            return $this->file->url();

        return \App::abort(404);
    }

}
