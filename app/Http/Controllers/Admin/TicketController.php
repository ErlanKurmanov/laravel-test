<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use App\Http\Resources\TicketResource;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\TicketServiceInterface;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        protected TicketRepositoryInterface $ticketRepository,
        protected TicketServiceInterface $ticketService
    ) {}


    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'email', 'phone']);

        $tickets = $this->ticketRepository->getPaginatedFiltered($filters);

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'filters' => $filters,
        ]);
    }


    public function show(Ticket $ticket)
    {
        $ticket->load('customer', 'media');


        $ticketData = new TicketResource($ticket);

        return view('admin.tickets.show', [
            'ticket' => $ticket,
            'ticketData' => $ticketData,
        ]);
    }


    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket)
    {
        $this->ticketService->updateStatus(
            $ticket,
            $request->validated('status')
        );

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Статус заявки успешно обновлен.');
    }
}
