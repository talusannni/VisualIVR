<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Template\Template;

class Project extends Model
{
    use HasFactory;
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Get the sheets for the project.
     */
    public function sheets()
    {
        return $this->hasMany(Sheet::class);
    }
    
    /**
     * Get the Template that owns the project.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_name',
        'description',
        'template_id',
        'status',
    ];
}
