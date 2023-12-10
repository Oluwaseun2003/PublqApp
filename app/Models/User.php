<?php

namespace App\Models;

use App\Models\Curriculum\CourseEnrolment;
use App\Models\Curriculum\CourseReview;
use App\Models\Curriculum\QuizScore;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'image',
    'username',
    'email',
    'email_verified_at',
    'password',
    'contact_number',
    'address',
    'city',
    'state',
    'country',
    'status',
    'verification_token',
    'edit_profile_status'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function courseEnrol()
  {
    return $this->hasMany(CourseEnrolment::class, 'user_id', 'id');
  }

  public function review()
  {
    return $this->hasMany(CourseReview::class, 'user_id', 'id');
  }

  public function quizScore()
  {
    return $this->hasMany(QuizScore::class, 'user_id', 'id');
  }
}
