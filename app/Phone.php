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

        if (is_numeric($this->model)) return false;

        return true;
    }

    public function getFriendlyNameAttribute()
    {
        if (empty($this->marketing_name)) {
            return "{$this->retail_branding} {$this->device}";
        }

        return str_contains(strtolower($this->marketing_name), strtolower($this->retail_branding))
            ? $this->marketing_name
            : "{$this->retail_branding} {$this->marketing_name}";
    }
}
