<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nilainormal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        aksi_login();
        $this->load->model('Dtmustahik_model');
        $this->load->model('Univ_model');
        $this->load->model('Nilaispk_model');
        $this->load->model('Kriteria_model');
    }

    public function index()
    {
        $kari = $this->Dtmustahik_model->ambildatanilai();
        if ($kari == null) {
            redirect('nilainormal/noData');
        }

        $this->Dtmustahik_model->dropTabel();

        $kriteria = $this->Kriteria_model->ambildata();
        $this->Dtmustahik_model->buatTabel($kriteria);
        $kariawan = $this->Dtmustahik_model->ambildata();
        $data['penilaian'] = $this->nilaikariawan($kariawan);
        // var_dump($data['penilaian']); die;

        $data['title'] = 'Nilai Mustahik';
        $data['foto'] = $this->Dtmustahik_model->foto();
        $data['user'] = $this->db->get_where('tb_userlogin', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('template/headermsaw', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('nilainormal/index', $data);
        $this->load->view('template/footermsaw');
    }

    public function penilaianspk()
    {
        $data['title'] = 'Nilai Mustahik';
        $data['foto'] = $this->Dtmustahik_model->foto();
        $data['dataspk'] = $this->_getspkkriterias();
        $data['karyawan'] = $this->db->get('tb_datamustahik')->result_array();
        $data['user'] = $this->db->get_where('tb_userlogin', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('template/headermsaw', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('nilainormal/tambahpenilaanspk', $data);
        $this->load->view('template/footermsaw');
    }

    public function tambahpenilaian()
    {
        $this->_ruleskaryawan();
        if ($this->form_validation->run() == true) {
            $this->penilaianspk();
        } else {
            $idkaryawan = $this->input->post('nama', true);
            $nilai = $this->input->post('nilai', true);
            $sukses = false;

            foreach ($nilai as $item => $value) {

                $this->Dtmustahik_model->idkaryawan = $idkaryawan;
                $this->Dtmustahik_model->kdkriteria = $item;
                $this->Dtmustahik_model->nilai = $value;
                if ($this->Dtmustahik_model->inserd()) {
                    $sukses = true;
                }
            }

            if ($sukses == true) {
                $this->session->set_flashdata('message', 'Berhasil menambah data :)');
                redirect('nilainormal');
            } else {
                echo 'Gagal';
            }
        }
    }

    private function _getspkkriterias()
    {
        $dataspk = [];
        $kriteria = $this->Nilaispk_model->getSemua();
        foreach ($kriteria as $item) {

            $this->Dtmustahik_model->kdkriteria = $item['kdkriteria'];

            $dataspk[$item['kdkriteria']] = [
                'nama' => $item['kriteria'],
                'kd' => $item['kdkriteria'],
                'data' => $this->Dtmustahik_model->ambilkdkriteria()
            ];
        }
        return $dataspk;
    }

    private function nilaikariawan($kariawan)
    {
        $nilai = $this->Nilaispk_model->getNilaiKaryawan();
        $dataInput = [];
        $no = 0;

        foreach ($kariawan as $item => $itemkariawan) {
            foreach ($nilai as $index => $itemnilai) {
                if ($itemkariawan['id_mustahik'] == $itemnilai->id_mustahik) {
                    $dataInput[$no]['id_mustahis'] = $itemkariawan['id_mustahik'];
                    $dataInput[$no]['nama'] = $itemkariawan['nama'];
                    $dataInput[$no][$itemnilai->kriteria] = $itemnilai->subkriteria;
                }
            }
            $no++;
        }

        foreach ($dataInput as $data => $item) {
            $this->Dtmustahik_model->nilaikaryawank($item);
        }

        return $this->Dtmustahik_model->getAll();
    }

    public function ubahnilaikar($id)
    {
        $data['title'] = 'Nilai Mustahik';
        $data['foto'] = $this->Dtmustahik_model->foto();
        $data['user'] = $this->db->get_where('tb_userlogin', ['email' => $this->session->userdata('email')])->row_array();
        $data['kariawan'] = $this->Dtmustahik_model->Byidnilai($id);
        $data['dataspk'] = $this->_getspkkriteria();
        $this->load->view('template/headermsaw', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('nilainormal/editnilai', $data);
        $this->load->view('template/footermsaw');
    }

    public function ubahnilaiaksikar()
    {
        $this->_nilaikaryawan();
        if ($this->form_validation->run() == true) {
            $this->ubahnilaikar($this->input->post('idmustahis', true));
        } else {
            $id = $this->input->post('idmustahis', true);
            $nilai = $this->input->post('nilai', true);
            $sukses = false;

            foreach ($nilai as $item => $value) {
                $this->Dtmustahik_model->idkaryawan = $id;
                $this->Dtmustahik_model->kdkriteria = $item;
                $this->Dtmustahik_model->nilai = $value;
                if ($this->Dtmustahik_model->update()) {
                    $sukses = true;
                }
            }

            if ($sukses == true) {
                $this->session->set_flashdata('message', 'Berhasil menambah data :)');
                redirect('nilainormal');
            }
        }
    }

    public function hapusnilaikar($id)
    {
        $this->Nilaispk_model->HapusNilaiKar($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Hapus nilai karyawan!</div>');
        redirect('nilainormal');
    }

    private function _getspkkriteria()
    {
        $dataspk = [];
        $kriteria = $this->Univ_model->getSemua();
        foreach ($kriteria as $item) {

            $this->Dtmustahik_model->kdkriteria = $item['kdkriteria'];

            $dataspk[$item['kdkriteria']] = [
                'nama' => $item['kriteria'],
                'kd' => $item['kdkriteria'],
                'data' => $this->Dtmustahik_model->ambilkdkriteria()
            ];
        }
        return $dataspk;
    }

    public function noData()
    {
        $data['title'] = 'Nilai Mustahik';
        $data['foto'] = $this->Dtmustahik_model->foto();
        $data['user'] = $this->db->get_where('tb_userlogin', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('template/headermsaw', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar', $data);
        $this->load->view('nilainormal/nodate', $data);
        $this->load->view('template/footermsaw');
    }

    public function _ruleskaryawan()
    {
        $this->form_validation->set_rules('nama', 'NAMA', 'trim|required');
        $this->form_validation->set_rules('kdkriteria', 'KDKRITERIA', 'trim|required');
        $this->form_validation->set_rules('nilai', 'NILAI', 'trim|required');
    }

    public function _nilaikaryawan()
    {
        $this->form_validation->set_rules('idmustahis', 'IDKARYAWAN', 'trim|required');
        $this->form_validation->set_rules('nama', 'NAMA', 'trim|required');
        $this->form_validation->set_rules('nilai', 'NILAI', 'trim|required');
    }
}
