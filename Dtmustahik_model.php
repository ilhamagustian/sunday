<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dtmustahik_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }

    public $kdkriteria;
    public $idkaryawan;
    public $nilai;

    public function ambildata()
    {
        return $this->db->get('tb_datamustahik')->result_array();
    }

    public function postmustahik()
    {
        $nama = htmlspecialchars($this->input->post('nama', true));
        $agama = htmlspecialchars($this->input->post('agama', true));
        $umur = htmlspecialchars($this->input->post('umur', true));
        $pekerjaan = htmlspecialchars($this->input->post('pekerjaan', true));
        $nomorhp = htmlspecialchars($this->input->post('nohp'));
        $nik = htmlspecialchars($this->input->post('nik', true));
        $alamat = htmlspecialchars($this->input->post('alamat', true));
        $status = htmlspecialchars($this->input->post('status', true));

        $data = [
            'nama' => $nama,
            'agama' => $agama,
            'umur' => $umur,
            'pekerjaan' => $pekerjaan,
            'nohp' => $nomorhp,
            'ktp' => $nik,
            'alamat' => $alamat,
            'status' => $status,
        ];
        $this->db->insert('tb_datamustahik', $data);
    }

    public function getByidmustahik($id)
    {
        return $this->db->get_where('tb_datamustahik', ['id_mustahik' => $id])->row_array();
    }

    public function editMustahik()
    {
        $id = $this->input->post('id');
        $nama = htmlspecialchars($this->input->post('nama', true));
        $agama = htmlspecialchars($this->input->post('agama', true));
        $umur = $this->input->post('umur', true);
        $pekerjaan = htmlspecialchars($this->input->post('pekerjaan', true));
        $nomorhp = $this->input->post('nohp', true);
        $nik = $this->input->post('nik', true);
        $alamat = $this->input->post('alamat', true);
        $status = $this->input->post('status', true);
        $data = [
            'nama' => $nama,
            'agama' => $agama,
            'umur' => $umur,
            'pekerjaan' => $pekerjaan,
            'nohp' => $nomorhp,
            'ktp' => $nik,
            'alamat' => $alamat,
            'status' => $status
        ];
        $this->db->where('id_mustahik', $id);
        $this->db->update('tb_datamustahik', $data);
    }

    public function HapusDataMustahik($id)
    {
        $this->db->where('id_mustahik', $id);
        $this->db->delete('tb_datamustahik');
    }

    public function foto()
    {
        $k = $this->session->userdata('email');
        $this->db->select('*');
        $this->db->from('tb_admin');
        $this->db->join('tb_userlogin', 'tb_userlogin.id_karyawan = tb_admin.id_karyawan');
        $this->db->where('tb_userlogin.email', $k);
        $query = $this->db->get();
        return $query->row_array();
    }

    //bats disini tambah data mustahik

    public function HapusDa($id)
    {
        $this->db->where('id_mustahik', $id);
        $this->db->delete('tb_datamustahik');

        $this->db->where('id_karyawan', $id);
        $this->db->delete('tb_userlogin');

        $this->db->where('id_mustahikk', $id);
        $this->db->delete('tb_nilaimustahik');

        $this->db->where('id_karyawan', $id);
        $this->db->delete('tb_nilainormal');

        $this->db->where('id_karyawan', $id);
        $this->db->delete('tb_saw');
    }

    public function ambildatakriteria()
    {
        return $this->db->get('tb_kriteria')->result_array();
    }
    public function ambildatainsentif()
    {
        return $this->db->get('tb_insentif')->result_array();
    }

    public function getByidadmin($id)
    {
        return $this->db->get_where('tb_admin', ['id_karyawan' => $id])->row_array();
    }


    public function Byidsandikaryawan($id)
    {
        return $this->db->get_where('tb_userlogin', ['id_karyawan' => $id])->row_array();
    }

    public function Hapusadmin($id)
    {
        $this->db->where('id_karyawan', $id);
        $this->db->delete('tb_admin');

        $this->db->where('id_karyawan', $id);
        $this->db->delete('tb_userlogin');
    }

    public function ambildatanilai()
    {
        return $this->db->get('tb_nilaimustahik')->result_array();
    }

    // tabel nilai karyawan normal

    //hapus tabel
    public function dropTabel()
    {
        $this->dbforge->drop_table('tb_nilainormal', true);
    }

    public function buatTabel($kriteria)
    {
        $fields = [
            'id_mustahis INT(11)',
            'nama VARCHAR(120) not null'
        ];

        foreach ($kriteria as $item => $value) {
            $fields[] = $value['kriteria'] . ' VARCHAR(120) not null';
        }

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('tb_nilainormal');
    }

    public function nilaikaryawank($item)
    {
        $status = $this->db->insert('tb_nilainormal', $item);
        return $status;
    }

    public function getAll()
    {
        $query = $this->db->get('tb_nilainormal');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $saw[] = $row;
            }
            return $saw;
        }
    }

    public function Byidnilai($id)
    {
        $this->db->where('id_mustahis', $id);
        $query = $this->db->get('tb_nilainormal');
        return $query->row_array();
    }

    //batas tabel nilai karyawan normal    

    public function ambilKariawanID($id)
    {
        $this->db->where('id_karyawan', $id);
        $query = $this->db->get('tb_datakaryawan');
        return $query->row_array();
    }

    public function adminID($id)
    {
        $this->db->where('id_karyawan', $id);
        $query = $this->db->get('tb_admin');
        return $query->row_array();
    }

    //ambil kdkriteria di tabel sub kriteria
    public function ambilkdkriteria()
    {
        $this->db->where('kdkriteria', $this->kdkriteria);
        $query = $this->db->get('tb_subkriteria');
        return $query->result();
    }

    public function inserttt()
    {
        $status = $this->db->insert($this->_getTable(),  $this->_getData());
        return $status;
    }

    public function inserd()
    {
        $status = $this->db->insert($this->_getTable(),  $this->_getData());
        return $status;
    }

    public function update()
    {
        $data = array('nilai' => $this->nilai);
        $this->db->where('id_mustahikk', $this->idkaryawan);
        $this->db->where('kdkriteria', $this->kdkriteria);
        $this->db->update($this->_getTable(), $data);
        return $this->db->affected_rows();
    }

    private function _getTable()
    {
        return 'tb_nilaimustahik';
    }

    private function _getData()
    {
        $data = array(
            'id_mustahikk' => $this->idkaryawan,
            'kdkriteria' => $this->kdkriteria,
            'nilai' => $this->nilai
        );
        return $data;
    }


    public function fotouser()
    {
        $k = $this->session->userdata('email');
        $this->db->select('*');
        $this->db->from('tb_datakaryawan');
        $this->db->join('tb_userlogin', 'tb_userlogin.id_karyawan = tb_datakaryawan.id_karyawan');
        $this->db->where('tb_userlogin.email', $k);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getnilaiKaryawan($id)
    {
        $query = $this->db->query('select kr.id_karyawan, kr.nama, k.kdkriteria, nk.nilai from tb_datakaryawan kr, tb_kriteria k, tb_nilaimustahik nk, tb_subkriteria sk where kr.id_karyawan = nk.id_mustahikk AND k.kdkriteria = nk.kdkriteria and k.kdkriteria = sk.kdkriteria and kr.id_karyawan = ' . $id . ' GROUP by nk.nilai');
        return $query->result_array();
    }
}
