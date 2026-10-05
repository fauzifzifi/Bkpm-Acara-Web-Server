<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Exceptions\RepositoryException;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use InvalidArgumentException;

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    // Dependency disuntikkan lewat constructor. Controller TIDAK membuat
    // koneksi database / Repository sendiri, sehingga mudah diuji dan diganti.
    public function __construct(MahasiswaRepository $repo, ProdiRepository $prodiRepo)
    {
        $this->repo      = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    // GET /mahasiswa?q=...&page=...
    public function index()
    {
        $search = trim($_GET['q'] ?? '');
        $page   = (int) ($_GET['page'] ?? 1);

        $this->view('mahasiswa/index', [
            'title'  => 'Daftar Mahasiswa',
            'pager'  => $this->repo->paginate($search, $page),   // data = array objek Mahasiswa
            'search' => $search,
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
        [$mhs, $errors] = $this->buildFromInput();
        if ($errors) {
            $this->failWithOld($errors, '/mahasiswa/create');
        }

        try {
            $this->repo->create($mhs);
        } catch (RepositoryException $e) {
            $this->failWithOld(
                $this->repoMessage($e, "NIM {$mhs->getNim()} sudah terdaftar.", 'Data tidak dapat disimpan.'),
                '/mahasiswa/create'
            );
        }

        $this->flash('Data mahasiswa berhasil ditambahkan.');
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

        [$mhs, $errors] = $this->buildFromInput($id);
        if ($errors) {
            $this->failWithOld($errors, "/mahasiswa/{$id}/edit");
        }

        try {
            $this->repo->update($mhs);
        } catch (RepositoryException $e) {
            $this->failWithOld(
                $this->repoMessage($e, "NIM {$mhs->getNim()} sudah dipakai mahasiswa lain.", 'Data tidak dapat disimpan.'),
                "/mahasiswa/{$id}/edit"
            );
        }

        $this->flash('Data mahasiswa berhasil diperbarui.');
        $this->redirect('/mahasiswa');
    }

    // POST /mahasiswa/{id}/delete
    public function destroy(int $id)
    {
        $this->findOrFail($id);

        try {
            $this->repo->delete($id);
        } catch (RepositoryException $e) {
            $this->flash($this->repoMessage($e, '', 'Mahasiswa tidak dapat dihapus karena masih dipakai.'), 'danger');
            $this->redirect('/mahasiswa');
        }

        $this->flash('Data mahasiswa berhasil dihapus.', 'warning');
        $this->redirect('/mahasiswa');
    }

    // ---------- helper ----------
    private function formData(string $title): array
    {
        return [
            'title'       => $title,
            'daftarProdi' => $this->prodiRepo->all(),
            'statusList'  => Mahasiswa::STATUS,
        ];
    }

    private function findOrFail(int $id): Mahasiswa
    {
        $mhs = $this->repo->find($id);
        if ($mhs === null) {
            $this->flash('Mahasiswa tidak ditemukan.', 'danger');
            $this->redirect('/mahasiswa');
        }
        return $mhs;
    }

    /**
     * Isi objek Mahasiswa dari form lewat SETTER. Validasi ada di setter;
     * setiap pesan InvalidArgumentException dikumpulkan sebagai error form.
     *
     * @return array [Mahasiswa, string[] $errors]
     */
    private function buildFromInput(?int $id = null): array
    {
        $mhs = new Mahasiswa();
        $mhs->setId($id);
        $errors = [];

        $input = [
            'setNim'      => (string) ($_POST['nim'] ?? ''),
            'setNama'     => (string) ($_POST['nama'] ?? ''),
            'setEmail'    => (string) ($_POST['email'] ?? ''),
            'setProdiId'  => (int) ($_POST['prodi_id'] ?? 0),
            'setAngkatan' => (int) ($_POST['angkatan'] ?? 0),
            'setStatus'   => (string) ($_POST['status'] ?? ''),
        ];

        foreach ($input as $setter => $value) {
            try {
                $mhs->$setter($value);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Prodi yang dipilih harus benar-benar ada
        if ($mhs->getProdiId() > 0 && $this->prodiRepo->find($mhs->getProdiId()) === null) {
            $errors[] = 'Program studi tidak ditemukan.';
        }

        return [$mhs, $errors];
    }
}
