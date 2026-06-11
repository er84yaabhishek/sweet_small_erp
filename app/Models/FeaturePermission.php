<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturePermission extends Model
{
    use HasFactory;

    protected $table = 'feature_permissions';
    protected $fillable = ['feature_name', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public static function isEnabled($featureName)
    {
        $feature = self::where('feature_name', $featureName)->first();
        return $feature ? $feature->is_enabled : false;
    }
}
