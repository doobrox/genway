<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait MetaTrait
{
    private function getClassName() 
    {
        return strtolower(class_basename($this));
    }

    public function setMeta($name, $value)
    {
        if($this->meta($name) != '' && $value) {
            return $this->metas()->where('name', $name)->update(['value' => $value, 'updated_at' => now()]);
        }

        return $value ? DB::table($this->getClassName().'_metas')
            ->insert([
                $this->getClassName().'_id' => $this->id,
                'name' => $name,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]) : null;
    }

    public function setMetas($array, $prefix = '', $suffix = '')
    {
        $upsert = [];
        // array of name => value pairs
        foreach ($array as $name => $value) { 
            $upsert[] = [
                $this->getClassName().'_id' => $this->id,
                'name' => ($prefix ?? '').$name.($suffix ?? ''),
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        return DB::table($this->getClassName().'_metas')
            ->upsert($upsert, [$this->getClassName().'_id', 'name'], ['value', 'updated_at']);
    }

    public function unsetMeta($name)
    {
        return $this->metas()->where('name', $name)->count() > 0 ? $this->metas()->where('name', $name)->delete() : null;
    }

    public function meta($name)
    {
        $data = $this->metas()->where('name', $name)->first();
        return $data ? $data->value : '';
    }

    public function getMetas($prefix = null, $suffix = null, $order = 'asc')
    {
        return $this->metas()->select('name','value')->where('name', 'like', $prefix.'%'.$suffix)
            ->orderBy('name', $order)->get()->mapWithKeys(function ($item) use ($prefix, $suffix) {
                if($prefix) {
                    return [explode($prefix, $item->name)[1] => $item->value];
                } elseif($suffix) {
                    return [explode($suffix, $item->name)[0] => $item->value];
                }
                return [$item->name => $item->value];
        })->toArray();
    }
    
    public function unsetMetas()
    {
        return $this->metas()->delete();
    }

    public function metas()
    {
        return DB::table($this->getClassName().'_metas')
            ->where([
                [$this->getClassName().'_id', $this->id],
            ]);
    }
}
