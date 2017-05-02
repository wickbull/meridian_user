<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GenericDir extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     *
     */
    public function dirs()
    {
        return $this->morphMany('App\GenericDir', 'attachable');
    }

    /**
     *
     */
    public function resolvePath($path)
    {
        $dir = $this;

        $parts = explode('/', trim($path, '/'));

        if (empty($parts))
        {
            return $dir;
        }

        foreach ($parts as $dirname)
        {
            $dirname = trim($dirname);

            if ($dirname)
            {
                $dir = $dir->dirs()->firstOrCreate(['name' => $dirname]);
            }
        }

        return $dir;
    }

    /**
     *
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     *
     */
    public function getParent()
    {
        if ($this->attachable_type === '\\')
        {
            return null;
        }

        return $this->attachable;
    }

    /**
     * @return array
     */
    protected function extractBreadcrumbs()
    {
        if ( ! ends_with($this->attachable_type, '\GenericDir'))
        {
            return [$this];
        }

        $crumbs   = $this->getParent()->extractBreadcrumbs();
        $crumbs[] = $this;

        return $crumbs;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBreadcrumbs()
    {
        return new \Illuminate\Support\Collection($this->extractBreadcrumbs());
    }

    /**
     *
     */
    public function getOwner()
    {
        return $this->getBreadcrumbs()->first()->getParent();
    }

}
