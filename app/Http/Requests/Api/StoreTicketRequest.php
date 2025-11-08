<?php

namespace App\Http\Requests\Api;

use App\Models\Customer;
use Closure; // Импортируем Closure
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => [
                'required',
                'email',
                'max:255',
                $this->checkDailyLimit(),
            ],
            'customer_phone' => ['required', 'string', 'regex:/^\+[1-9]\d{9,14}$/'],
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:65000',
            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:jpg,png,pdf,doc,docx,zip|max:10240',
        ];
    }

    /**
     * Rate limiting once a day
     */
    protected function checkDailyLimit(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) {

            $existingCustomer = Customer::where('email', $value)
                ->orWhere('phone', $this->input('customer_phone'))
                ->first();

            if ($existingCustomer) {

                $hasRecentTicket = $existingCustomer->tickets()
                    ->where('created_at', '>=', now()->subDay())
                    ->exists();

                if ($hasRecentTicket) {
                    $fail('Вы можете отправлять заявку только один раз в 24 часа.');
                }
            }
        };
    }
}
