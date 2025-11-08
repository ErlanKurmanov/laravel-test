<?php

namespace App\Interfaces;

use App\Models\Ticket;

interface TicketServiceInterface
{
    public function createFromWidget(array $data): Ticket;
    public function updateStatus(Ticket $ticket, string $newStatus): Ticket;
}
