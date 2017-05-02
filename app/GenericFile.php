<?php namespace App;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\Model;

use Ideil\LaravelGenericFile\Traits\EloquentTrait;
use Ideil\LaravelGenericFile\Traits\TokenTrait;

use Ideil\GenericFile\Resources\File;

use Input;

class GenericFile extends Model {

	use EloquentTrait, TokenTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'original_name',
		'original_ext',
		'size',
		'mime',
		'label',
		'creator_id',
		'descr',
		'name',
		'ext',
		'hash'
	];

	/**
	 * @var null|array
	 */
	protected $imagesize = null;

	/**
	 * Constructor method.
	 *
	 * @param array|string|UploadedFile $attributes
	 */
	public function __construct($attributes = array())
	{
		if ($attributes instanceof UploadedFile)
		{
			parent::__construct([]);

			$this->upload($attributes);
		}

		elseif (is_string($attributes))
		{
			parent::__construct([]);

			$this->upload(Input::file($attributes));
		}

		else
		{
			parent::__construct($attributes);
		}
	}

	/**
	 * Return file assign map
	 *
	 * @return array
	 */
	public function getFileAssignMap()
	{
		return [
			'contentHash' => 'hash',
			'ext' => 'ext',
		];
	}

	/**
	 * @return integer
	 */
	public function getFileUsageCount(Madel $model)
	{
		return $this->whereHash($model->hash)->whereExt($model->ext)->count();
	}

	/**
	 * @return string
	 */
	public function getOriginalNameWithoutExt()
	{
		return preg_replace('~\.\w*$~', '', $this->original_name);
	}

	/**
	 * @return null|array
	 */
	public function getImageSize()
	{
		if ( ! $this->isImage())
		{
			return [];
		}

		if (is_null($this->imagesize))
		{
			$this->imagesize = file_exists($this->path()) ?
				array_slice(getimagesize($this->path()), 0, 2) : [];
		}

		return $this->imagesize ?: [];
	}

	/**
	 * @return null|integer
	 */
	public function getImageWidth()
	{
		if ($size = $this->getImageSize())
		{
			return $size[0];
		}

		return null;
	}

	/**
	 * @return null|integer
	 */
	public function getImageHeight()
	{
		if ($size = $this->getImageSize())
		{
			return $size[1];
		}

		return null;
	}

	/**
	 * @return boolean
	 */
	public function isImage()
	{
		return starts_with($this->mime, 'image/');
	}



	public function getCheckSum(array $data, $length = 6)
	{
		$data_hash = md5(implode(':', $data) . ':' . env('APP_KEY'));
		$data_hash = base_convert($data_hash, 16, 35);
		$data_hash = str_pad(substr($data_hash, 0, $length), $length, '0');
		return $data_hash;
	}


	/**
	 *
	 * @return string
	 */
	public function getRawCrop()
	{
		return !empty($this->attrs['crop']) ?  $this->attrs['crop'] : 'nocrop';
	}

	/**
	 *
	 * @param  string $size
	 * @return string
	 */
	public function getThumb($size = null, $crop = null)
	{
		return $this->thumb($size, $crop);
	}


	/**
	 * @return string
	 */
	public function thumb($resize = null, $crop = null, $watermark = false)
	{
		if ($this->isImage())
		{
			$path = '/{contentHash|subpath}/{contentHash}.{ext}';

			if ($watermark)
			{
				$path = '/watermark'.$path;
			}

			if ($crop)
			{
				$path = '/'.$crop.$path;
			}

			if ($resize)
			{
				$path = '/'.$resize.$path;
			}

			$resolved_path = self::$generic_file->makeUrlToUploadedFile($this, $path, [], '');

			$checksum = $this->token6FromStr($resolved_path);

			$base_url = rtrim(self::$generic_file->getConfig('http.domain', ''), '/');

			return $base_url.'/content/thumbs/'.$checksum.$resolved_path;
		}

		return $this->url();
	}

	/**
	 * Before upload event, cancel file store if false returned
	 *
	 * @return boolean
	 */
	public function beforeUpload(File $file)
	{
		$this->creator_id = 3;

		$this->original_name = $file->getClientOriginalName();

		$this->size = $file->getSize();

		$this->mime = $file->getMimeType();

		return true;
	}

	/**
	 * @return BelongsTo
	 */
	public function dir()
	{
		return $this->belongsTo('App\GenericDir', 'generic_dir_id');
	}
}
