<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @method static findByCode($code)
 */
trait HasGeneratedCode
{
    protected static function bootHasGeneratedCode()
    {
        static::creating(function ($model): void {
            $model->refreshCode();
        });
    }

    public function scopeFindByCode(Builder $query, string $code)
    {
        return $query->where($this->codeField(), $code)
            ->firstOrFail();
    }

    public function refreshCode()
    {
        $this->{$this->codeField()} = $this->generateCode();
    }

    public function generateCode(): string
    {
        $code = Str::random(10);

        if ($this->where($this->codeField(), $code)->exists()) {
            return $this->generateCode();
        }

        return $code;
    }

    protected function codeField(): string
    {
        return 'code';
    }
}
