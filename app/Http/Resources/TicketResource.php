<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
            // Загружаем клиента, только если он уже был загружен (Eager Loading)
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            // (Для админки) Показываем файлы, если они есть
            'attachments' => $this->whenLoaded('media', function () {
                return $this->media->map(function ($media) {
                    return [
                        'name' => $media->file_name,
                        'size' => $media->size,
                        'url' => $media->getFullUrl(), // URL для скачивания
                    ];
                });
            }),
        ];
    }
}
