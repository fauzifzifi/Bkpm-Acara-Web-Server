<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Mahasiswa;
use App\Services\MahasiswaService;

/**
 * Controller hanya mengatur ALUR: terima request -> panggil Service ->
 * simpan Flash Message -> redirect / tampilkan view.
 * Tidak ada validasi dan tidak ada query database di sini.
 */
class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    // GET /mahasiswa?q=...&page=...
    public function index()
    {
        $this->view('mahasiswa/index', [
            'title'  => 'Daftar Mahasiswa',
            'pager'  => $this->service->paginate(trim($_GET['q'] ?? ''), (int) ($_GET['page'] ?? 1)),
            'search' => trim($_GET['q'] ?? ''),
        ]);
    }

    // GET /mahasiswa/create
    public function create()
    {
        $this->view('mahasiswa/create', $this->formData('Tambah Mahasiswa'));
    }

    // POST /mahasiswa
    public function store()
    {
        $result = $this->service->create($_POST);

        if (!$result['success']) {
            $this->failWithOld(array_values($result['errors']), '/mahasiswa/create');
        }

        $this->flash('Data berhasil ditambahkan');
        $this->redirect('/mahasiswa');
    }

    // GET /mahasiswa/{id}/edit
    public function edit(int $id)
    {
        $mhs = $this->findOrFail($id);
        $this->view('mahasiswa/edit', $this->formData('Edit Mahasiswa') + ['mhs' => $mhs]);
    }

    // POST /mahasiswa/{id}/update
    public function update(int $id)
    {
        $this->findOrFail($id);
        $result = $this->service->update($id, $_POST);

        if (!$result['success']) {
            $this->failWithOld(array_values($result['errors']), "/mahasiswa/{$id}/edit");
        }

        $this->flash('Data berhasil diubah');
        $this->redirect('/mahasiswa');
    }

    // POST /mahasiswa/{id}/delete
    public function destroy(int $id)
    {
        $this->findOrFail($id);
        $result = $this->service->delete($id);

        if (!$result['success']) {
            $this->flash(array_values($result['errors']), 'danger');
            $this->redirect('/mahasiswa');
        }

        $this->flash('Data berhasil dihapus', 'warning');
        $this->redirect('/mahasiswa');
    }

    // ---------- helper alur ----------
    private function formData(string $title): array
    {
        return [
            'title'       => $title,
            'daftarProdi' => $this->service->prodiOptions(),
            'statusList'  => $this->service->statusOptions(),
        ];
    }

    private function findOrFail(int $id): Mahasiswa
    {
        $mhs = $this->service->find($id);
        if ($mhs === null) {
            $this->flash('Mahasiswa tidak ditemukan', 'danger');
            $this->redirect('/mahasiswa');
        }
        return $mhs;
    }
}
