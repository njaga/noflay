<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'file_path',
        'document_type',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the contract that owns the document.
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }


    /**
     * Get the user who uploaded the document.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope a query to only include documents of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Get the document's file URL.
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get the document's file extension.
     *
     * @return string
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

}
