<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'description',
        'price',
        'active',
        'stock',
        'image',
    ];

    protected $appends = [
        "article_image",
    ];

    public function getArticleImageAttribute(){
        if(empty($this->image)){
            return asset("/img/placeholder.png");
        }
        
        return asset(Storage::url($this->image));
    }

}
