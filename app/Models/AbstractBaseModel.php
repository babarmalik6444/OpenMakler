<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbstractBaseModel extends Model
{
    public static function boot(): void
    {
        parent::boot();

        self::creating(function(self $model){
            $model->onCreating();
        });
        self::created(function(self $model){
            $model->onCreated();
        });
        self::deleting(function(self $model){
            $model->onDeleting();
        });
        self::deleted(function(self $model){
            $model->onDeleted();
        });
        self::updating(function(self $model){
            $model->onUpdating();
        });
        self::updated(function(self $model){
            $model->onUpdated();
        });
        self::saving(function(self $model){
            $model->onSaving();
        });
        self::saved(function(self $model){
            $model->onSaved();
        });
    }


    protected function onCreating() {}


    protected function onCreated() {}


    protected function onDeleting() {}


    protected function onDeleted() {}


    protected function onUpdating() {}


    protected function onUpdated() {}


    protected function onSaving() {}


    protected function onSaved() {}
}
