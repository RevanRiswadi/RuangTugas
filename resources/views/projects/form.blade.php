@php($editing = isset($project))
<form action="{{ $editing ? route('projects.update', $project) : route('projects.store') }}" method="POST" class="surface-card mt-5 space-y-4 p-4 sm:p-5">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div>
        <label for="nama_proyek" class="mb-1.5 block text-sm font-semibold text-ink-900">Nama proyek</label>
        <input id="nama_proyek" name="nama_proyek" value="{{ old('nama_proyek', $project->nama_proyek ?? '') }}" maxlength="160" required class="field-control @error('nama_proyek') border-clay-700 @enderror" aria-describedby="@error('nama_proyek') nama-proyek-error @enderror">
        @error('nama_proyek')<p id="nama-proyek-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="mata_pelajaran" class="mb-1.5 block text-sm font-semibold text-ink-900">Mata pelajaran</label>
        <input id="mata_pelajaran" name="mata_pelajaran" value="{{ old('mata_pelajaran', $project->mata_pelajaran ?? '') }}" maxlength="120" required class="field-control @error('mata_pelajaran') border-clay-700 @enderror" aria-describedby="@error('mata_pelajaran') mata-pelajaran-error @enderror">
        @error('mata_pelajaran')<p id="mata-pelajaran-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-ink-900">
            Deskripsi <span class="font-normal text-slate-600">(opsional)</span>
        </label>
        <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="5000" class="field-control min-h-28 resize-y @error('deskripsi') border-clay-700 @enderror" aria-describedby="@error('deskripsi') deskripsi-error @enderror">{{ old('deskripsi', $project->deskripsi ?? '') }}</textarea>
        @error('deskripsi')<p id="deskripsi-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
    </div>

    <div class="form-grid grid gap-4 sm:grid-cols-2">
        <div>
            <label for="deadline" class="mb-1.5 block text-sm font-semibold text-ink-900">Deadline</label>
            <input id="deadline" name="deadline" type="date" value="{{ old('deadline', isset($project) ? $project->deadline->format('Y-m-d') : '') }}" required class="field-control @error('deadline') border-clay-700 @enderror" aria-describedby="@error('deadline') deadline-error @enderror">
            @error('deadline')<p id="deadline-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="status" class="mb-1.5 block text-sm font-semibold text-ink-900">Status</label>
            <select id="status" name="status" required class="field-control @error('status') border-clay-700 @enderror" aria-describedby="@error('status') status-error @enderror">
                @foreach (['Aktif', 'Selesai'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $project->status ?? 'Aktif') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('status')<p id="status-error" role="alert" class="mt-1 text-xs font-medium text-clay-700">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex flex-wrap gap-2 border-t border-[var(--border)] pt-4">
        <button type="submit" class="button button-primary" data-loading-text="Menyimpan...">{{ $editing ? 'Simpan perubahan' : 'Simpan proyek' }}</button>
        <a href="{{ route('projects.index') }}" class="button button-secondary">Batal</a>
    </div>
</form>
