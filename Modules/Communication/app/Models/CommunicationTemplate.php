<?php

namespace Modules\Communication\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int         $id
 * @property int|null    $company_id
 * @property string      $name
 * @property string      $type
 * @property string|null $subject
 * @property string      $body
 * @property array|null  $variables
 * @property string      $status
 * @property string|null $slug
 */
class CommunicationTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'communication_templates';

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'subject',
        'body',
        'variables',
        'status',
        'slug',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where(function ($q) use ($companyId) {
            $q->where('company_id', $companyId)->orWhereNull('company_id');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Replace {variable} placeholders in body and subject.
     */
    public function render(array $data): array
    {
        $search  = array_map(fn ($v) => '{' . $v . '}', array_keys($data));
        $replace = array_values($data);

        return [
            'subject' => str_replace($search, $replace, $this->subject ?? ''),
            'body'    => str_replace($search, $replace, $this->body),
        ];
    }

    public static function typeLabels(): array
    {
        return [
            'email' => 'Email',
            'sms'   => 'SMS',
            'chat'  => 'Chat',
            'push'  => 'Push',
            'all'   => 'All Channels',
        ];
    }
}
