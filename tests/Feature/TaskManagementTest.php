<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_project_progress_and_upcoming_tasks_in_deadline_order(): void
    {
        $project = Project::query()->create([
            'nama_proyek' => 'Praktikum Basis Data',
            'mata_pelajaran' => 'Basis Data',
            'deadline' => today()->addDays(20),
            'status' => 'Aktif',
        ]);

        $laterTask = $project->tasks()->create([
            'judul_tugas' => 'Laporan akhir',
            'deadline' => today()->addDays(8),
            'prioritas' => 'Rendah',
            'status' => 'Belum Dimulai',
        ]);
        $soonerTask = $project->tasks()->create([
            'judul_tugas' => 'Diagram relasi',
            'deadline' => today()->addDays(2),
            'prioritas' => 'Tinggi',
            'status' => 'Dalam Proses',
        ]);
        $project->tasks()->create([
            'judul_tugas' => 'Tugas yang selesai',
            'deadline' => today()->addDay(),
            'prioritas' => 'Sedang',
            'status' => 'Selesai',
        ]);
        Project::query()->create([
            'nama_proyek' => 'Praktikum tanpa tugas',
            'mata_pelajaran' => 'Pemrograman Web',
            'deadline' => today()->addDays(25),
            'status' => 'Aktif',
        ]);
        Project::query()->create([
            'nama_proyek' => 'Proyek selesai',
            'mata_pelajaran' => 'Jaringan Komputer',
            'deadline' => today()->subDay(),
            'status' => 'Selesai',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('projectCount', 3)
            ->assertSee('Praktikum Basis Data')
            ->assertSee('1/3 tugas selesai')
            ->assertSee('aria-valuenow="33"', false)
            ->assertSee('Praktikum tanpa tugas')
            ->assertSee('Belum ada tugas')
            ->assertSee('aria-valuenow="0"', false)
            ->assertDontSee('Proyek selesai')
            ->assertSeeInOrder([$soonerTask->judul_tugas, $laterTask->judul_tugas])
            ->assertDontSee('Tugas yang selesai');
    }

    public function test_project_can_be_created_updated_and_deleted_with_its_tasks(): void
    {
        $projectData = [
            'nama_proyek' => 'Aplikasi Inventaris',
            'mata_pelajaran' => 'Pemrograman Web',
            'deskripsi' => 'Proyek praktikum',
            'deadline' => today()->addDays(14)->format('Y-m-d'),
            'status' => 'Aktif',
        ];

        $this->post(route('projects.store'), $projectData)
            ->assertRedirect(route('projects.index'));

        $project = Project::query()->firstOrFail();
        $task = $project->tasks()->create([
            'judul_tugas' => 'Rancang basis data',
            'deadline' => today()->addDays(5),
            'prioritas' => 'Sedang',
            'status' => 'Belum Dimulai',
        ]);

        $this->put(route('projects.update', $project), [...$projectData, 'nama_proyek' => 'Inventaris Lab', 'status' => 'Selesai'])
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'nama_proyek' => 'Inventaris Lab', 'status' => 'Selesai']);

        $this->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_can_be_created_filtered_updated_and_deleted(): void
    {
        $basisData = Project::query()->create([
            'nama_proyek' => 'Sistem Perpustakaan',
            'mata_pelajaran' => 'Basis Data',
            'deadline' => today()->addDays(25),
            'status' => 'Aktif',
        ]);
        $webProject = Project::query()->create([
            'nama_proyek' => 'Situs Praktikum',
            'mata_pelajaran' => 'Pemrograman Web',
            'deadline' => today()->addDays(25),
            'status' => 'Aktif',
        ]);
        $taskData = [
            'project_id' => $basisData->id,
            'judul_tugas' => 'Buat skema tabel',
            'anggota_tim' => 'Anggota A, Anggota B',
            'deadline' => today()->addDays(4)->format('Y-m-d'),
            'prioritas' => 'Tinggi',
            'status' => 'Belum Dimulai',
        ];

        $this->post(route('tasks.store'), $taskData)
            ->assertRedirect(route('tasks.index'));

        $task = Task::query()->firstOrFail();
        $webProject->tasks()->create([
            'judul_tugas' => 'Buat halaman utama',
            'deadline' => today()->addDays(3),
            'prioritas' => 'Rendah',
            'status' => 'Belum Dimulai',
        ]);

        $this->get(route('tasks.index', ['mata_pelajaran' => 'Basis Data', 'prioritas' => 'Tinggi']))
            ->assertOk()
            ->assertSee('Buat skema tabel')
            ->assertDontSee('Buat halaman utama');

        $this->patch(route('tasks.status', $task), ['status' => 'Dalam Proses'])
            ->assertRedirect();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'Dalam Proses']);

        $this->put(route('tasks.update', $task), [...$taskData, 'judul_tugas' => 'Skema data revisi', 'prioritas' => 'Sedang'])
            ->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'judul_tugas' => 'Skema data revisi', 'prioritas' => 'Sedang']);

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_project_and_task_forms_show_validation_errors(): void
    {
        $this->from(route('projects.create'))
            ->post(route('projects.store'), [])
            ->assertRedirect(route('projects.create'))
            ->assertSessionHasErrors(['nama_proyek', 'mata_pelajaran', 'deadline', 'status']);

        $project = Project::query()->create([
            'nama_proyek' => 'Jaringan Komputer',
            'mata_pelajaran' => 'Jaringan',
            'deadline' => today()->addDays(10),
            'status' => 'Aktif',
        ]);

        $this->from(route('tasks.create'))
            ->post(route('tasks.store'), ['project_id' => $project->id])
            ->assertRedirect(route('tasks.create'))
            ->assertSessionHasErrors(['judul_tugas', 'deadline', 'prioritas', 'status']);
    }

    public function test_unknown_pages_explain_the_error_and_offer_a_recovery_link(): void
    {
        $this->get('/alamat-tidak-tersedia')
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan')
            ->assertSee(route('dashboard'));
    }
}
