@php($editing = isset($task))
<form action="{{ $editing ? route('tasks.update', $task) : route('tasks.store') }}" method="POST" class="surface-card mt-5 space-y-4 p-4 sm:p-5">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div>
        <label for="project_id" class="mb-1.5 block text-sm font-semibold text-ink-900">Proyek</label>
        <select id="project_id" name="project_id" required class="field-control @error('project_id') border-clay-700 @enderror" aria-describedby="@error('project_id') project-id-error @enderror">
            <option value="">Pilih proyek</option>
            @foreach ($projects as $projectOption)
                <option value="{{ $projectOption->id }}" @selected((string) old('project_id', $task->project_id ?? '') === (string) $projectOption->id)>
                    {{ $projectOption->nama_proyek }} · {{ $projectOption->mata_pelajaran }}
                </option>
            @endforeach
        </select>
        @error('project_id')<p id="project-id-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="judul_tugas" class="mb-1.5 block text-sm font-semibold text-ink-900">Judul tugas</label>
        <input id="judul_tugas" name="judul_tugas" value="{{ old('judul_tugas', $task->judul_tugas ?? '') }}" maxlength="200" required class="field-control @error('judul_tugas') border-clay-700 @enderror" aria-describedby="@error('judul_tugas') judul-tugas-error @enderror">
        @error('judul_tugas')<p id="judul-tugas-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="anggota_tim" class="mb-1.5 block text-sm font-semibold text-ink-900">
            Anggota tim <span class="font-normal text-slate-600">(opsional)</span>
        </label>
        <textarea id="anggota_tim" name="anggota_tim" rows="3" maxlength="2000" placeholder="Pisahkan nama anggota dengan koma" class="field-control min-h-20 resize-y @error('anggota_tim') border-clay-700 @enderror" aria-describedby="@error('anggota_tim') anggota-tim-error @enderror">{{ old('anggota_tim', $task->anggota_tim ?? '') }}</textarea>
        @error('anggota_tim')<p id="anggota-tim-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div class="form-grid grid gap-4 sm:grid-cols-2">
        <div>
            <label for="deadline" class="mb-1.5 block text-sm font-semibold text-ink-900">Deadline</label>
            <input id="deadline" name="deadline" type="date" value="{{ old('deadline', isset($task) ? $task->deadline->format('Y-m-d') : '') }}" required class="field-control @error('deadline') border-clay-700 @enderror" aria-describedby="@error('deadline') deadline-error @enderror">
            @error('deadline')<p id="deadline-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="prioritas" class="mb-1.5 block text-sm font-semibold text-ink-900">Prioritas</label>
            <select id="prioritas" name="prioritas" required class="field-control @error('prioritas') border-clay-700 @enderror" aria-describedby="@error('prioritas') prioritas-error @enderror">
                @foreach (['Rendah', 'Sedang', 'Tinggi'] as $priority)
                    <option value="{{ $priority }}" @selected(old('prioritas', $task->prioritas ?? 'Sedang') === $priority)>{{ $priority }}</option>
                @endforeach
            </select>
            @error('prioritas')<p id="prioritas-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="status" class="mb-1.5 block text-sm font-semibold text-ink-900">Status</label>
        <select id="status" name="status" required class="field-control @error('status') border-clay-700 @enderror" aria-describedby="@error('status') status-error @enderror">
            @foreach (['Belum Dimulai', 'Dalam Proses', 'Selesai'] as $status)
                <option value="{{ $status }}" @selected(old('status', $task->status ?? 'Belum Dimulai') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status')<p id="status-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div class="flex flex-wrap gap-2 border-t border-[var(--border)] pt-4">
        <button type="submit" class="button button-primary" data-loading-text="Menyimpan...">{{ $editing ? 'Simpan perubahan' : 'Simpan tugas' }}</button>
        <a href="{{ route('tasks.index') }}" class="button button-secondary">Batal</a>
    </div>
</form>
