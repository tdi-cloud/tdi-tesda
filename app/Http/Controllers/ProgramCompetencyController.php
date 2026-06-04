<?php

namespace App\Http\Controllers;

use App\Models\ProgramCompetency;
use Illuminate\Http\Request;

class ProgramCompetencyController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate(['competency' => 'required|string']);

        $comp = ProgramCompetency::create([
            'program_id' => $id,
            'domain'     => $request->domain,
            'competency' => $request->competency,
        ]);

        return response()->json($comp);
    }

    public function destroy($id)
    {
        $comp = ProgramCompetency::findOrFail($id);
        $comp->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    public function storeBatch(Request $request, $id)
    {
        $request->validate([
            'competencies'              => 'required|array|min:1',
            'competencies.*.domain'     => 'required|string',
            'competencies.*.competency' => 'required|string',
        ]);

        $created = [];
        foreach ($request->competencies as $item) {
            $created[] = ProgramCompetency::create([
                'program_id' => $id,
                'domain'     => $item['domain'],
                'competency' => $item['competency'],
            ]);
        }

        return response()->json($created);
    }

    
    }
