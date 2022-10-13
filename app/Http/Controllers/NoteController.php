<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Stringable;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // Fetch notes in order of when they were last updated - latest updated returned first
         $notes = Note::where('user_id', Auth::id())->latest('updated_at')->paginate(5);
         //   dd($notes);
            return view('notes.index')->with('notes', $notes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        Note::create([
            // Ensure you have the use statement for
            // Illuminate\Support\Str at the top of this file.
            'uuid' => Str::uuid(),
            'user_id' => Auth::id(),
            'title' => $request->title,
            'text' => $request->text
        ]);

        return to_route('notes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Note  $note
     * @return \Illuminate\Http\Response
     */
    // Using Route Model Binding - I pass in the complete $note. therefore I don't need to get it from the database.
    // if the ID or UUID was only passed in, then access to the database would be requred to get the complete object.
    public function show(Note $note)
    {
         // $note = Note::where('uuid',$uuid)->where('user_id',Auth::id())->firstOrFail();

        // Only the user that owns the Note can access it.
         if($note->user_id != Auth::id()) {
            return abort(403);
        }
         return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        if($note->user_id != Auth::id()) {
            return abort(403);
        }

        return view('notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        // First ensure the user is authenticated to access this note - i.e. they own the Note
        // if(!$note->user->is(Auth::user())) {
        //     return abort(403);
        // }

        if($note->user_id != Auth::id()) {
            return abort(403);
        }

        // experiment with other validation - especially when it comes to your CA.
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        $note->update([
            'title' => $request->title,
            'text' => $request->text
        ]);

        return to_route('notes.show', $note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if($note->user_id!= (Auth::id())){
            abort(403);
        }

        $note->delete();

        return to_route('notes.index');
    }
}
