<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogDeploy extends Model
{
    use HasFactory;

    protected $table = 'log_deploy';
    protected $fillable = ['repo_name', 'message'];
}
