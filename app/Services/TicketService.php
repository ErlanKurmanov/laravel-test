<?php

namespace App\Services;

use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\TicketServiceInterface;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService implements TicketServiceInterface
{
    public function __construct(
        protected TicketRepositoryInterface $ticketRepository,
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    public function createFromWidget(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {

            $customer = $this->customerRepository->findOrCreate($data);

            $ticket = $this->ticketRepository->create([
                'customer_id' => $customer->id,
                'subject' => $data['subject'],
                'body' => $data['body'],
                'status' => 'new',
            ]);

            if (!empty($data['files'])) {
                foreach ($data['files'] as $file) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }

            return $ticket;
        });
    }


    public function updateStatus(Ticket $ticket, string $newStatus): Ticket
    {
        $updateData = ['status' => $newStatus];

        if (
            in_array($newStatus, ['in_progress', 'processed']) &&
            is_null($ticket->manager_response_at)
        ) {
            $updateData['manager_response_at'] = now();
        }

        $this->ticketRepository->update($ticket, $updateData);

        return $ticket->fresh();
    }
}
