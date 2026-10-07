<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Logger;
use App\Exceptions\ValidationException;
use App\Models\Mahasiswa;
use App\Services\MahasiswaService;

/**
 * Controller: mengatur alur request.
 *
 *   POST -> Service -> (berhasil) Flash success -> redirect -> GET      [PRG]
 *                   -> (ValidationException) Flash pesan validasi -> redirect ke form
 *                   -> (error teknis) Logger + Flash pesan umum -> redirect ke form
 *
 * Tidak ada SQL dan tidak ada detail error teknis yang sampai ke pengguna.
 */
class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    // GET /mahasiswa  -> daftar mahasiswa
    public function index()
    {
        $search = trim($_GET['q'] ?? '');

        $this->view('mahasiswa/index', [
            'title'  => 'Daftar Mahasiswa',
            'pager'  => $this->service->paginate($search, (int) ($_GET['page'] ?? 1)),
            'search' => $search,
        ]);
    }

    // GET /mahasiswa/create  -> form tambah
    public function create()
    {
        $this->view('mahasiswa/create', $this->formData('Tambah Mahasiswa'));
    }

    // POST /mahasiswa  -> proses tambah
    public function store()
    {
        try {
            $this->service->create($_POST);
        } catch (ValidationException $e) {
            $this->failWithOld($e->getErrors(), '/mahasiswa/create');
        } catch (\Exception $e) {
            Logger::error($e, __METHOD__);
            $this->failWithOld('Data gagal disimpan.', '/mahasiswa/create');
        }

        $this->flash('Data mahasiswa berhasil ditambahkan.');
        $this->redirect('/mahasiswa');                       // PRG: redirect setelah POST
    }

    // GET /mahasiswa/edit?id=1  -> form edit
    public function edit()
    {
        $mhs = $this->findOrFail((int) ($_GET['id'] ?? 0));
        $this->view('mahasiswa/edit', $this->formData('Edit Mahasiswa') + ['mhs' => $mhs]);
    }

    // POST /mahasiswa/update  -> proses ubah
    public function update()
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->service->update($id, $_POST);
        } catch (ValidationException $e) {
            $this->failWithOld($e->getErrors(), "/mahasiswa/edit?id={$id}");
        } catch (\Exception $e) {
            Logger::error($e, __METHOD__);
            $this->failWithOld('Data gagal disimpan.', "/mahasiswa/edit?id={$id}");
        }

        $this->flash('Data mahasiswa berhasil diubah.');
        $this->redirect('/mahasiswa');                       // PRG
    }

    // POST /mahasiswa/delete  -> proses hapus
    public function destroy()
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->service->delete($id);
        } catch (ValidationException $e) {
            $this->flash($e->getErrors(), 'danger');
            $this->redirect('/mahasiswa');
        } catch (\Exception $e) {
            Logger::error($e, __METHOD__);
            $this->flash('Data gagal dihapus.', 'danger');
            $this->redirect('/mahasiswa');
        }

        $this->flash('Data mahasiswa berhasil dihapus.');
        $this->redirect('/mahasiswa');                       // PRG
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
        $mhs = $id > 0 ? $this->service->find($id) : null;
        if ($mhs === null) {
            $this->flash('Mahasiswa tidak ditemukan.', 'danger');
            $this->redirect('/mahasiswa');
        }
        return $mhs;
    }
}
