<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'metakeyword',
        'content',
        'metadescription',
        'type',
        'type_course',
        'products_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    /**
     * Get the course type options
     */
    public static function getCourseTypeOptions()
    {
        return [
            'fi' => 'Fitness Instructor',
            'pt' => 'Personal Trainer', 
            'fa' => 'Fitness Assistant'
        ];
    }

    /**
     * Get the course type label
     */
    public function getCourseTypeLabelAttribute()
    {
        $options = self::getCourseTypeOptions();
        return $options[$this->type_course] ?? $this->type_course;
    }
}
