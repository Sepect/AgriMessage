<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::orderBy('updated_at', 'desc')->get();
        return view('template.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Template::create($validated);

        return redirect()->route('template.index')->with('success', 'Template berhasil disimpan');
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('template.index')->with('success', 'Template berhasil diperbarui');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('template.index')->with('success', 'Template berhasil dihapus');
    }
}
