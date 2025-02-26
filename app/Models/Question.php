<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function hasVoted(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function vote(User $user)
    {
        $this->votes()->create([
            'user_id' => $user->id,
        ]);
    }

    public function unvote(User $user)
    {
        $this->votes()->where('user_id', $user->id)->delete();
    }

    public function approve()
    {
        $this->update([
            'is_approved' => true,
        ]);
    }
}