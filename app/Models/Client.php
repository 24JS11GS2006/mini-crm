<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name','document_number','email','phone']; // campos asignables

    // Un cliente tiene muchos tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}