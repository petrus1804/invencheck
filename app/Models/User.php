<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi & Helper untuk InvenCheck
     */

    public function transactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'pic_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function roleModel(): BelongsTo
{
    return $this->belongsTo(Role::class, 'role_id');
}

    public function hasPermission(string $slug): bool
    {
        if (! $this->role_id) {
            return false;
        }

        return $this->roleModel->permissions()->where('slug', $slug)->exists();
    }

    public function defaultRouteName(): string
    {
        if ($this->hasPermission('view_dashboard')) {
            return 'dashboard';
        }

        if ($this->hasPermission('request_items')) {
            return 'ambil-barang';
        }

        return 'profile.edit';
    }
}