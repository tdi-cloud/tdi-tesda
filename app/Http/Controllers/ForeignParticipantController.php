<?php

namespace App\Http\Controllers;

use App\Models\ForeignParticipant;
use App\Models\ForeignProgram;
use Illuminate\Http\Request;

class ForeignParticipantController extends Controller
{
    public function index(Request $request, ForeignProgram $foreignProgram)
    {
        $query = $foreignProgram->participants();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('agency', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $participants = $query->latest()->paginate(15)->withQueryString();

        // ✅ Tamang view — hindi 'monitoring.fstp.index'
        return view('monitoring.fstp.participants', [
            'program'       => $foreignProgram,
            'participants'  => $participants,
            'statusOptions' => ForeignParticipant::statusOptions(),
        ]);
    }
 
    public function store(Request $request, ForeignProgram $foreignProgram)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'sex'        => 'required|in:male,female,other',
            'position'   => 'required|string|max:255',
            'agency'     => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:50',
            'email'      => 'nullable|email|max:255',
            'status'     => 'required|in:' . implode(',', array_keys(ForeignParticipant::statusOptions())),
        ]);
 
        $data['foreign_program_id'] = $foreignProgram->id;
        ForeignParticipant::create($data);
 
        return redirect()->route('foreign-programs.participants.index', $foreignProgram)
                         ->with('success', 'Participant added.');
    }
 
    public function update(Request $request, ForeignProgram $foreignProgram, ForeignParticipant $participant)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'sex'        => 'required|in:male,female,other',
            'position'   => 'required|string|max:255',
            'agency'     => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:50',
            'email'      => 'nullable|email|max:255',
            'status'     => 'required|in:' . implode(',', array_keys(ForeignParticipant::statusOptions())),
        ]);
 
        $participant->update($data);
 
        return redirect()->route('foreign-programs.participants.index', $foreignProgram)
                         ->with('success', 'Participant updated.');
    }
 
    public function destroy(ForeignProgram $foreignProgram, ForeignParticipant $participant)
    {
        $participant->delete();
 
        return redirect()->route('foreign-programs.participants.index', $foreignProgram)
                         ->with('success', 'Participant removed.');
    }
 
    public function show(ForeignProgram $foreignProgram, ForeignParticipant $participant)
    {
        return response()->json($participant);
    }
}
