<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nilaispk_model extends CI_Model
{

    public function getSemua()
    {
        return $this->db->get('tb_kriteria')->result_array();
    }

    //ambil data dari tabel kariawan, tb kriteria, tb_nilai
    public function getNilaiKaryawan()
    {
        // $query = $this->db->query('select u.id_karyawan, u.nama, sub.kdkriteria, sub.value, sub.subkriteria, k.kdkriteria, k.kriteria, n.nilai from tb_subkriteria sub, tb_datakaryawan u, tb_nilaikaryawan n, tb_kriteria k where u.id_karyawan = n.id_karyawan AND k.kdkriteria = n.kdkriteria AND sub.kdkriteria = n.kdkriteria AND sub.value = n.nilai');
        $query = $this->db->query('select u.id_mustahik, u.nama, sub.kdkriteria, sub.value, sub.subkriteria, k.kdkriteria, k.kriteria, n.nilai from tb_subkriteria sub, tb_datamustahik u, tb_nilaimustahik n, tb_kriteria k where u.id_mustahik = n.id_mustahikk AND k.kdkriteria = n.kdkriteria AND sub.kdkriteria = n.kdkriteria AND sub.value = n.nilai');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $nilai[] = $row;
            }
            return $nilai;
        }
    }

    public function HapusNilaiKar($id)
    {
        $this->db->where('id_mustahikk', $id);
        $this->db->delete('tb_nilaimustahik');

        $this->db->where('id_mustahis', $id);
        $this->db->delete('tb_nilainormal');

        $this->db->where('id_mustahikkk', $id);
        $this->db->delete('tb_saw');
    }
}
