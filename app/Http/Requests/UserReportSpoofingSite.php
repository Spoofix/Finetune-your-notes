<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserReportSpoofingSite extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'abuse_type' => ['integer', 'max:255'],
            'evidence_urls' => ['string', 'max:255'],
            'additional_notes' => ['string', 'max:255'],
            'observed_date' => ['string', 'max:255'],
            'attachments' => ['string', 'max:255'],
            'carbon_copy_request_to' => ['string', 'max:255'],
            'reported_by_user_id' => ['string', 'max:255'],
            'reported_via' => ['string', 'max:255'],
        ];
    }
}
