<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;
    public const QUAN_LY = "Quản lý kí túc xá";
    public const TU_QUAN = "Tự quản kí túc xá";

    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'information_id',
    ];

    protected $hidden = [
        'password'
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'teacher_id', 'id');
    }

    public function information(): BelongsTo
    {
        return $this->belongsTo(Information::class, 'information_id', 'id');
    }

    public function mistakes(): HasMany
    {
        return $this->hasMany(Mistake::class, 'teacher_id', 'id');
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'teacher_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'teacher_id', 'id');
    }

    protected function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function verify($password): bool
    {
        return password_verify($password, $this->password);
    }
}



