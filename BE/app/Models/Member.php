<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'ho_ten',
        'sdt',
        'ngay_sinh',
        'dia_chi',
        'email',
        'ngay_tao',
    ];

    public function membershipCards()
    {
        return $this->hasMany(MembershipCard::class, 'member_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'member_id');
    }

    public function sessions()
    {
        return $this->hasMany(MemberSession::class, 'member_id');
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class, 'member_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'member_id');
    }
}
