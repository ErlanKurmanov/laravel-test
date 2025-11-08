<?php

namespace App\Interfaces;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    public function create(array $data): Ticket;
    public function update(Ticket $ticket, array $data): bool;
    public function getPaginatedFiltered(array $filters): LengthAwarePaginator;
}
