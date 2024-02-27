<?php
 
namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class FormatDate implements CastsAttributes
{
	private $format = 'd/m/Y';

	public function __construct($format = null)
    {
    	if($format) {
        	$this->format = $format;
    	}
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value ? Carbon::parse($value)->format($this->format) : $value;
    }
 
    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if($this->validateDateTime($value, $this->format)) {
            return $value ? Carbon::createFromFormat($this->format, $value)->format('Y-m-d') : null;
        } elseif($this->validateDateTime($value)) {
            return $value ? Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d') : null;
        } else {
            return null;
        }
    }

    public function validateDateTime($dateStr, $format = 'Y-m-d')
    {
        // date_default_timezone_set('UTC');
        $date = \DateTime::createFromFormat($format, $dateStr);
        return $date && ($date->format($format) === $dateStr);
    }
}