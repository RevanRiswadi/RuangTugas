<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::query()
                ->withCount('tasks')
                ->orderByRaw("CASE WHEN status = 'Aktif' THEN 0 ELSE 1 END")
                ->orderBy('deadline')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Project::query()->create($this->validatedData($request));

        return to_route('projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validatedData($request));

        return to_route('projects.index')->with('success', 'Perubahan proyek berhasil disimpan.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return to_route('projects.index')->with('success', 'Proyek dan tugas terkait berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_proyek' => ['required', 'string', 'max:160'],
            'mata_pelajaran' => ['required', 'string', 'max:120'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'deadline' => ['required', 'date'],
            'status' => ['required', 'in:Aktif,Selesai'],
        ], [
            'nama_proyek.required' => 'Nama proyek wajib diisi.',
            'nama_proyek.max' => 'Nama proyek tidak boleh lebih dari 160 karakter.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib diisi.',
            'mata_pelajaran.max' => 'Mata pelajaran tidak boleh lebih dari 120 karakter.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 5.000 karakter.',
            'deadline.required' => 'Deadline proyek wajib diisi.',
            'deadline.date' => 'Deadline proyek harus berupa tanggal yang valid.',
            'status.required' => 'Status proyek wajib dipilih.',
            'status.in' => 'Pilih status proyek yang tersedia.',
        ], [
            'nama_proyek' => 'nama proyek',
            'mata_pelajaran' => 'mata pelajaran',
            'deadline' => 'deadline',
            'deskripsi' => 'deskripsi',
        ]);
    }
}
