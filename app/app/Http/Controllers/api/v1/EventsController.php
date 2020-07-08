<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\v1\BaseController;
use App\Models\Event;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = Event::orderBy('start_at', 'desc');
        if ($request->query('description')) {
            $events = $events->where('description', 'LIKE', $request->query('description'));
        }

        if ($request->query('responsable')) {
            $events = $events->where('responsable_id', '=', $request->query('responsable'));
        }
        return $this->sendResponse($events->get(), 'success', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $event = new Event();
            $event->description = $request->description;
            $event->start_at = $request->start_at;
            $event->end_at = $request->end_at;
            $event->observation = $request->observation;
            $event->owner_id = Auth::id();
            $event->responsable_id = $request->responsable;
            $event->save();

            return $this->sendResponse($event, 'success');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $events = Event::where('responsable_id', '=', $user->id)->orderBy('start_at', 'desc')->get();

        return $this->sendResponse($events, 'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Event $event)
    {
        try {
            $event->update($request->all());
            return $this->sendResponse($event, 'success');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
    }
}
