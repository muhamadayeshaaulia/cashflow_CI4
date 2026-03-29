<?php

namespace App\Models;

use CodeIgniter\Model;

class KasKeluarModel extends Model
{
    protected $table            = 'kas_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['tanggal_pengajuan', 'divisi_peminta', 'deskripsi', 
        'nama_vendor', 'bank_vendor', 'rekening_vendor', 
        'nominal_barang', 'pajak_ppn', 'biaya_ongkir', 'total_pengajuan', 
        'status', 'bukti_pembayaran', 'nip_purchasing', 'nip_manajer', 'nip_admin'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
