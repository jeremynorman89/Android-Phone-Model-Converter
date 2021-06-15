<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'model';

    protected $fillable = [
        'model', 'retail_branding', 'marketing_name', 'device',
    ];

    public function hasValidModelName(): bool
    {
        if (empty($this->model)) return false;

        return true;
    }

    public function getFriendlyName()
    {
        return str_contains($this->marketing_name, $this->retail_branding)
            ? $this->marketing_name
            : "{$this->retail_branding} {$this->marketing_name}";
    }
}
