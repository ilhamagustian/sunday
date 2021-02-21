<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">Metode s.a.w Pemberian Zakat</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">
                <div class="breadcrumbs-top float-md-right">
                    <div class="breadcrumb-wrapper mr-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Halaman Penilaian Mustahik
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body pb-0 mb-3">
                            <h4 class="card-title">TAMBAH PENILAIAN MUSTAHIK</h4>
                            <!-- Bordered table start -->
                            <?= $this->session->flashdata('message'); ?>

                            <form action="<?= base_url('nilainormal/tambahpenilaian'); ?>" method="POST">
                            <div class="form-group">
                                        <h5>Nama</h5>
                                        <fieldset class="form-group">
                                            <input type="text" class="form-control" name="nama" id="nama" placeholder="nama lengkap.">
                                            <?= form_error('nama', '<small class="text-danger">', '</small>'); ?>
                                        </fieldset>
                                    </div>
                                <div class="form-group">
                                    <?php foreach ($dataspk as $data) : ?>

                                        <!-- <input type="hidden" name="kdkriteria" value="<?= $data['kd']; ?>"> -->

                                        <h5 class="mt-2"><?= $data['nama']; ?></h5>
                                        <fieldset class="form-group">
                                            <select class="custom-select" name="nilai[<?= $data['kd']; ?>]" id="nilai">
                                                <?php foreach ($data['data'] as $item) : ?>
                                                    <option value="<?= $item->value; ?>"><?= $item->subkriteria; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </fieldset>
                                    <?php endforeach; ?>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-info text-white">Simpan</button>
                                    <a class="btn btn-secondary" href="<?= base_url('nilainormal'); ?>" type="button">Kembali</a>
                                </div>
                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</div>