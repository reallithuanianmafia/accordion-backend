<?php

namespace App\Http\Controllers;

use App\Models\Accordion;
use Illuminate\Http\Request;

class AccordionController extends Controller
{
    public function index()
    {
        $accordions = Accordion::whereNull('parent_id')
            ->with('children') // Eager load children
            ->get();
        return response()->json($accordions);
    }

    public function all()
    {
        $accordions = Accordion::all();
        return response()->json($accordions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable',
        ]);

        $accordion = Accordion::create($validated);

        if ($validated['parent_id']) {
            $parentAccordion = Accordion::find($validated['parent_id']);
            $parentAccordion->children()->save($accordion);
        }

        return response()->json($accordion, 201);
    }

    public function show($id)
    {
        $accordion = Accordion::with('children.children')->findOrFail($id);
        return response()->json($accordion);
    }

    public function update(Request $request, $id)
    {
        $accordion = Accordion::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:accordions,id',
        ]);

        $accordion->update($validated);

        if (isset($validated['parent_id'])) {
            $parentAccordion = Accordion::find($validated['parent_id']);
            $parentAccordion->children()->save($accordion);
        }

        return response()->json($accordion);
    }

    public function destroy($id)
    {
        Accordion::destroy($id);
        return response()->json(null, 204);
    }
}
