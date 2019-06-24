<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class PdfFile extends Model
{
    const ATTRIBUTES = [
        'title' => 'Title',
        'description' => 'Subject',
        'key_words' => 'Keywords'
    ];
}
