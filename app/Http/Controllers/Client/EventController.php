<?php
namespace App\Http\Controllers\Client;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends BaseClientController
{
    public function index()
    {
        $type = request('type'); 

        $q = Event::where('status', 'published');

        if ($type === 'upcoming') {
            $q->whereDate('event_date', '>=', now()->toDateString())->orderBy('event_date')->orderBy('event_time');
        } elseif ($type === 'past') {
            $q->whereDate('event_date', '<', now()->toDateString())->orderByDesc('event_date');
        } else {
            $q->orderByDesc('event_date');
        }

        $events = $q->paginate(12)->appends(request()->query());

        return $this->view('client.events.index', compact('events', 'type'));
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return $this->view('client.events.show', compact('event'));
    }
}
