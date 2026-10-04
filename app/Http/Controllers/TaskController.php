<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filters = array_filter($request->validate([
            'mata_pelajaran' => ['nullable', 'string', 'max:120'],
            'prioritas' => ['nullable', Rule::in(['Rendah', 'Sedang', 'Tinggi'])],
        ], [
            'prioritas.in' => 'Pilih prioritas yang tersedia.',
        ]));

        $tasks = Task::query()
            ->with('project')
            ->when($filters['mata_pelajaran'] ?? null, function ($query, string $subject): void {
                $query->whereHas('project', fn ($projects) => $projects->where('mata_pelajaran', $subject));
            })
            ->when($filters['prioritas'] ?? null, fn ($query, string $priority) => $query->where('prioritas', $priority))
            ->orderBy('deadline')
            ->paginate(12)
            ->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'subjects' => Project::query()->select('mata_pelajaran')->distinct()->orderBy('mata_pelajaran')->pluck('mata_pelajaran'),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('tasks.create', [
            'projects' => Project::query()->orderBy('nama_proyek')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Task::query()->create($this->validatedData($request));

        return to_route('tasks.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
            'projects' => Project::query()->orderBy('nama_proyek')->get(),
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validatedData($request));

        return to_route('tasks.index')->with('success', 'Perubahan tugas berhasil disimpan.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Belum Dimulai', 'Dalam Proses', 'Selesai'])],
        ], [
            'status.in' => 'Pilih status tugas yang tersedia.',
        ]);

        $task->update($validated);

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return to_route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'judul_tugas' => ['required', 'string', 'max:200'],
            'anggota_tim' => ['nullable', 'string', 'max:2000'],
            'deadline' => ['required', 'date'],
            'prioritas' => ['required', Rule::in(['Rendah', 'Sedang', 'Tinggi'])],
            'status' => ['required', Rule::in(['Belum Dimulai', 'Dalam Proses', 'Selesai'])],
        ], [
            'project_id.required' => 'Pilih proyek untuk tugas ini.',
            'project_id.integer' => 'Proyek yang dipilih tidak valid.',
            'project_id.exists' => 'Proyek yang dipilih tidak ditemukan.',
            'judul_tugas.required' => 'Judul tugas wajib diisi.',
            'judul_tugas.max' => 'Judul tugas tidak boleh lebih dari 200 karakter.',
            'anggota_tim.max' => 'Data anggota tim tidak boleh lebih dari 2.000 karakter.',
            'deadline.required' => 'Deadline tugas wajib diisi.',
            'deadline.date' => 'Deadline tugas harus berupa tanggal yang valid.',
            'prioritas.required' => 'Prioritas tugas wajib dipilih.',
            'prioritas.in' => 'Pilih prioritas yang tersedia.',
            'status.required' => 'Status tugas wajib dipilih.',
            'status.in' => 'Pilih status tugas yang tersedia.',
        ], [
            'project_id' => 'proyek',
            'judul_tugas' => 'judul tugas',
            'anggota_tim' => 'anggota tim',
            'deadline' => 'deadline',
            'prioritas' => 'prioritas',
            'status' => 'status',
        ]);
    }
}
