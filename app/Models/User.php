<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;




class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    public function timeins()
    {
        return $this->hasMany(TimeIn::class, 'user_id');
    }
    protected $fillable = [
        'name', 'email','phone', 'password', 'email_verification_token', 
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // app/Models/User.php

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    //wishi lisht
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Helper method to check if a product is in the user's wishlist
    public function hasInWishlist($productId)
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function hasInCart($productId)
    {
        return $this->cart()->where('product_id', $productId)->exists();
    }




    public function orders()
    {
        return $this->hasMany(Order::class);
    }


     public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }


    public function address()
    {
        return $this->hasOne(Address::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
}
