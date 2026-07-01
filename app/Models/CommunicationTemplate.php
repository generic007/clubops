<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationTemplate extends Model
{
    protected $fillable = [
        'name', 'category', 'channel', 'body',
        'merge_fields', 'tone', 'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function render(array $data = []): string
    {
        $body = $this->body;
        foreach ($data as $key => $value) {
            $body = str_replace('{' . $key . '}', $value, $body);
        }
        return $body;
    }
}
