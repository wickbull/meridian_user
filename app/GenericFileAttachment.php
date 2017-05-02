<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GenericFileAttachment extends Model {

    /**
     * @var string
     */
    public $table = 'generic_file_attachments';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * @return Relation
     */
    public function file()
    {
        return $this->belongsTo('App\GenericFile', 'generic_file_id');
    }

    /**
     * @param  string $class_name
     * @param  integer $id
     * @param  string $field_name
     * @return string
     */
    public static function makePrimaryKey($class_name, $id, $field_name)
    {
        return strtolower("{$class_name}:{$id}:{$field_name}");
    }

    /**
     * @param  string $class_name
     * @param  integer $id
     * @param  string $field_name
     * @return Collection
     */
    public static function findOrNewByField($class_name, $id, $field_name)
    {
        $key = self::makePrimaryKey($class_name, $id, $field_name);

        if ($item = self::find($key))
            return $item;

        $item = new static;
        $item->key = $key;

        return $item;
    }

}
