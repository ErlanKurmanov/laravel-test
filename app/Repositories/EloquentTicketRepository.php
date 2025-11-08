<?php

namespace App\Repositories;

use App\Interfaces\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function update(Ticket $ticket, array $data): bool
    {
        return $ticket->update($data);
    }

    public function getPaginatedFiltered(array $filters): LengthAwarePaginator
    {
        $query = Ticket::query()->with('customer');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['email'])) {
            $query->whereHas('customer', fn($q) => $q->where('email', 'like', '%' . $filters['email'] . '%'));
        }
        if (!empty($filters['phone'])) {
            $query->whereHas('customer', fn($q) => $q->where('phone', 'like', '%' . $filters['phone'] . '%'));
        }

        return $query->latest()->paginate(20)->withQueryString();
    }
}
