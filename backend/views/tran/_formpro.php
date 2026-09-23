<?php
//formpro.php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use common\models\Traneventcrew;

?>

<?php
$form = ActiveForm::begin([
    'id' => 'tran-form',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
    ],
    'validateOnSubmit' => false,
]);
?>

<input type="hidden" name="module" value="<?= $module ?? 'purchase' ?>">
<input type="hidden" name="type" value="<?= $type ?? 'request' ?>">
<?= Html::hiddenInput('id', $model->tranid, ['id' => 'tran-id']) ?>

<div class="d-flex flex-column px-5 px-lg-10" id="modal_form_asset_scroll">

    <div class="card mb-5 border border-1 rounded">
        <div class="card-header border-bottom border-gray-200 min-h-auto bg-light py-4">
            <div class="d-flex align-items-center gap-3">
                <div class="w-4px h-25px rounded bg-primary"></div>
                <h4 class="mb-0 fs-7 fw-bold text-uppercase ls-1 text-gray-700">Detail Transaksi</h4>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="row tran-head">
                <div class="mb-2 col-lg-3 col-md-6 col-sm-12">
                    <?= $form->field($model, 'tranno')->textInput(['readOnly' => true]); ?>
                </div>

                <div class="mb-7 col-lg-4 col-md-6 col-sm-12">
                    <?= $form->field($model, 'contact_id')->dropDownList(
                        $model->contact_id ? [$model->contact_id => Yii::$app->function->findByField("jobcompany", "contacts", " and contact_id ='" . $model->contact_id . "' ")] : [],
                        [
                            'class' => 'form-select',
                            'required' => true,
                        ]
                    ) ?>
                </div>

                <div class="mb-2 col-lg-2 col-md-4 col-sm-12">
                    <?= $form->field($model, 'trandate')->textInput(['class' => 'form-control pickdate', 'placeholder' => $model->getAttributeLabel('trandate')]) ?>
                </div>

                <div class="mb-7 col-lg-1 col-md-4 col-sm-12">
                    <?= $form->field($model, 'term')->textInput(['type' => 'number']); ?>
                </div>

                <div class="mb-7 col-lg-2 col-md-4 col-sm-12">
                    <?= $form->field($model, 'tranduedate')->textInput(['class' => 'form-control pickdate']); ?>
                </div>
            </div>

            <div class="separator separator-dashed my-2"></div>

            <div class="row align-items-end pt-3">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <label class="fs-7 mb-3">Customer</label>
                    <div class="d-flex align-items-center gap-3 h-40px">
                        <div class="form-check form-check-custom form-check-success form-check-solid me-1">
                            <input class="form-check-input h-20px w-20px" type="radio"
                                name="<?= Html::getInputName($model, 'picradio') ?>" value="1" <?= ($model->picradio == 1) ? 'checked' : '' ?> id="picRadio1" checked />
                            <label class="form-check-label fw-bold text-gray-700 fs-7" for="picRadio1">
                                PIC 1
                            </label>
                        </div>

                        <div class="form-check form-check-custom form-check-danger form-check-solid me-1">
                            <input class="form-check-input h-20px w-20px" type="radio"
                                name="<?= Html::getInputName($model, 'picradio') ?>" value="2" <?= ($model->picradio == 2) ? 'checked' : '' ?> id="picRadio2" />
                            <label class="form-check-label fw-bold text-gray-700 fs-7" for="picRadio2">
                                PIC 2
                            </label>
                        </div>

                        <div class="form-check form-check-custom form-check-warning form-check-solid">
                            <input class="form-check-input h-20px w-20px" type="radio"
                                name="<?= Html::getInputName($model, 'picradio') ?>" value="3" <?= ($model->picradio == 3) ? 'checked' : '' ?> id="picRadio3" />
                            <label class="form-check-label fw-bold text-gray-700 fs-7" for="picRadio3">
                                PIC 3
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'piccustomer')
                        ->textInput(['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('piccustomer')]) ?>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'piccustomer_telp')->textInput([
                        'type' => 'tel',
                        'class' => 'form-control',
                        'placeholder' => '+62812-3456-7890',
                        'oninput' => "this.value = this.value.replace(/[^0-9\-\+\s]/g, '')"
                    ]) ?>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'setupdate')
                        ->textInput(['class' => 'form-control picktime', 'placeholder' => $model->getAttributeLabel('setupdate')]) ?>
                </div>

                <div class="col-lg-2 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'withdrawaldate')
                        ->textInput(['class' => 'form-control picktime', 'placeholder' => $model->getAttributeLabel('withdrawaldate')]) ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-5 border border-1 rounded">
        <div class="card-header border-bottom border-gray-200 min-h-auto bg-light py-4">
            <div class="d-flex align-items-center gap-3">
                <div class="w-4px h-25px rounded bg-warning"></div>
                <h4 class="mb-0 fs-7 fw-bold text-uppercase ls-1 text-gray-700">PIC Lapangan</h4>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'pic1')
                        ->textInput(['class' => 'form-control']) ?>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'telppic1')->textInput([
                        'type' => 'tel',
                        'class' => 'form-control',
                        'oninput' => "this.value = this.value.replace(/[^0-9\-\+\s]/g, '')"
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'pic2')
                        ->textInput(['class' => 'form-control']) ?>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'telppic2')->textInput([
                        'type' => 'tel',
                        'class' => 'form-control',
                        'oninput' => "this.value = this.value.replace(/[^0-9\-\+\s]/g, '')"
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'pic3')
                        ->textInput(['class' => 'form-control']) ?>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'telppic3')->textInput([
                        'type' => 'tel',
                        'class' => 'form-control',
                        'oninput' => "this.value = this.value.replace(/[^0-9\-\+\s]/g, '')"
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5 border border-1 rounded">
        <div class="card-header border-bottom border-gray-200 min-h-auto bg-light py-4">
            <div class="d-flex align-items-center gap-3">
                <div class="w-4px h-25px rounded bg-success"></div>
                <h4 class="mb-0 fs-7 fw-bold text-uppercase ls-1 text-gray-700">Event Information</h4>
            </div>
        </div>
        <div class="card-body pt-3">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field(
                        $model,
                        'locations'
                    )
                        ->textInput([
                            'value' => $model->locations,
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('locations'),
                            'required' => true,
                            'id' => 'transeventform-locations'
                        ]) ?>
                </div>

                <!-- <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <label class="fw-semibold fs-7"><?= Yii::$app->lang->t('tran', 'tran_coordinate') ?></label>
                    <div class="position-relative">
                        <?= $form->field($model, 'coordinate')
                            ->textInput([
                                'class' => 'form-control pe-5 coordinate-input',
                                'placeholder' => $model->getAttributeLabel('coordinate')
                            ])->label(false) ?>
                        <button type="button" class="btn btn-sm btn-icon btn-primary position-absolute end-0 top-0 mt-1 me-1"
                            id="open-map-modal" style="z-index: 10;">
                            <i class="fas fa-map-marked-alt"></i>
                        </button>
                    </div>
                </div> -->

                <div class="modal fade" id="map-modal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true"
                    data-bs-backdrop="static">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header  text-white">
                                <h5 class="modal-title" id="mapModalLabel">
                                    <i class="fas fa-map-marker-alt me-2"></i>Pilih Lokasi Event
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-search me-1"></i>Cari Lokasi
                                    </label>
                                    <div class="position-relative">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="search-location"
                                                placeholder="Ketik minimal 3 huruf untuk mencari..." autocomplete="off">
                                            <button class="btn btn-primary" type="button" id="search-btn">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                        <ul id="suggestions-list" class="list-group"></ul>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Tips: Ketik nama kota, tempat, atau alamat (contoh: "Jakarta", "Monas")
                                    </small>
                                </div>

                                <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                                    <i class="fas fa-map-pin me-2 fs-4"></i>
                                    <div class="flex-grow-1">
                                        <strong>Koordinat Terpilih:</strong><br>
                                        <span id="selected-coordinate" class="text-dark">Belum ada koordinat
                                            dipilih</span>
                                    </div>
                                </div>

                                <!-- Instruksi
                            <div class="alert alert-light border mb-3">
                                <strong><i class="fas fa-hand-pointer me-1"></i> Cara Memilih Lokasi:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><strong>Klik langsung di peta</strong> untuk menandai lokasi</li>
                                    <li><strong>Gunakan search box</strong> untuk mencari lokasi (ketik min. 3 huruf)</li>
                                    <li><strong>Marker bisa di-drag</strong> untuk menyesuaikan posisi</li>
                                    <li><strong>Lokasi GPS</strong> akan otomatis terdeteksi (jika diizinkan)</li>
                                    <li><strong>Saat edit</strong>, lokasi tersimpan akan otomatis tampil</li>
                                </ul>
                            </div> -->

                                <div id="map" style="height: 450px; width: 100%; border-radius: 8px;"></div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                                <button type="button" class="btn btn-primary" id="confirm-coordinate">
                                    <i class="fas fa-check me-1"></i>Gunakan Koordinat Ini
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field(
                        $model,
                        'linkmap'
                    )
                        ->textInput([
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('linkmap')
                        ]) ?>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field(
                        $model,
                        'eventname'
                    )
                        ->textInput([
                            'class' => 'form-control',
                            'placeholder' => $model->getAttributeLabel('eventname'),
                            'required' => true
                        ]) ?>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-7">
                    <?= $form->field($model, 'eventtype')->dropDownList(
                        [
                            '0' => 'Ful Rent',
                            '1' => 'Dry Rent',
                            '2' => 'Sub Rent',
                            '3' => 'Take Away',
                        ],
                        [
                            'class' => 'form-select',
                            'data-control' => 'select2',
                            'placeholder' => $model->getAttributeLabel('eventtype'),
                            'required' => true

                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($model->trantype == "sales/order") { ?>
        <div class="card mb-5 border border-1 rounded pic-event-card">
            <div class="card-header border-bottom border-gray-200 min-h-auto bg-light py-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="w-4px h-25px rounded bg-info"></div>
                    <h4 class="mb-0 fs-7 fw-bold text-uppercase ls-1 text-gray-700">PIC Event</h4>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-7 pic-ae">
                        <?= $form->field($model, 'aeid')->dropDownList(
                            $model->aeid ? [$model->aeid => Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->aeid}' ")] : [],
                            [
                                'class' => 'form-select select2-pic-ae',
                                'required' => true,
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-7 pic-operator">
                        <?= $form->field($model, 'operatorid')->dropDownList(
                            $model->operatorid ? [$model->operatorid => Yii::$app->function->findByField("(contact_no ||' ['||contact_name ||']')", "contacts", " and contact_id ='{$model->operatorid}' ")] : [],
                            [
                                'class' => 'form-select select2-pic-operator',
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-7 pic-storeman">
                        <?= $form->field($model, 'storemanid')->dropDownList(
                            $model->storemanid ? [$model->storemanid => Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->storemanid}' ")] : [],
                            [
                                'class' => 'form-select select2-pic-storeman',
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-7 pic-pic1">
                        <?= $form->field($model, 'pic1id')->dropDownList(
                            $model->pic1id ? [$model->pic1id => Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->pic1id}' ")] : [],
                            [
                                'class' => 'form-select select2-pic-pic1',
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-7 pic-pic2">
                        <?= $form->field($model, 'pic2id')->dropDownList(
                            $model->pic2id ? [$model->pic2id => Yii::$app->function->findByField("contact_name", "contacts", " and contact_id ='{$model->pic2id}' ")] : [],
                            [
                                'class' => 'form-select select2-pic-pic2',
                            ]
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="card card-flush mb-5">
        <div class="card-header pt-5">
            <ul class="nav nav-tabs flex-nowrap text-nowrap" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 active btn-product"
                        data-bs-toggle="tab" data-bs-target="#detail" href="#detail" role="tab" aria-controls="detail"
                        data-tranid="<?= $model->tranid ?>" aria-selected="false">Product</a>
                </li>
                <?php if ($model->trantype == "sales/order") { ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                            data-bs-toggle="tab" data-bs-target="#event" role="tab" aria-controls="event" href="#event"
                            aria-selected="true">Crew</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                            data-bs-toggle="tab" data-bs-target="#po" role="tab" id="po-tab" aria-controls="po" href="po"
                            aria-selected="true">
                            PO
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <!-- <div class="card-toolbar align-items-end">
                <button type="button" class="btn btn-sm btn-primary btn-generate">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Generate
                </button>
            </div> -->
        </div>
        <div class="card-body pt-3">
            <div class="tab-content">
                <div class="tab-pane fade p-3 show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                    <div
                        class="form-check form-switch form-check-custom form-check-success form-check-solid d-flex flex-row-reverse align-items-center gap-2">
                        <?= $form->field($model, 'priceincludetax')->hiddenInput()->label(false) ?>
                        <input class="form-check-input" type="checkbox" value="" id="kt_flexSwitchCustomDefault_1_1"
                            <?= $model->priceincludetax === 1 ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="kt_flexSwitchCustomDefault_1_1">
                            Tax Include Price
                        </label>
                    </div>
                    <div class="table-responsive" id="trandetail_repeater">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-gray-600 fw-bold fs-7 text-uppercase gs-0 text-start">
                                    <th class="min-w-180px">
                                        <?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?>
                                    </th>
                                    <th class="min-w-150px"><?= Yii::$app->lang->t('produk', 'detail_qty') ?></th>
                                    <th class="min-w-80px">Period(Day)</th>
                                    <th class="min-w-150px"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
                                    <th class="min-w-150px">Sub Total</th>
                                    <th class="min-w-80px"><?= Yii::$app->lang->t('extra', 'extra5') ?></th>
                                    <th class="min-w-100px"><?= Yii::$app->lang->t('extra', 'extra70') ?></th>
                                    <th class="min-w-150px"><?= Yii::$app->lang->t('extra', 'extra71') ?></th>
                                </tr>
                            </thead>

                            <tbody class="detail-rows" data-repeater-list="Trandetail">
                                <?php if (!empty($modeldetails)): ?>
                                    <?php foreach ($modeldetails as $index => $modeldetail): ?>
                                        <tr class="detail-item" data-repeater-item>
                                            <td class="align-top">
                                                <div class="d-flex flex-column">
                                                    <input type="hidden" name="trandetailid" class="form-control"
                                                        value="<?= $modeldetail->trandetailid ?? "" ?>" readonly>

                                                    <input type="hidden" name="tranid" class="form-control tranid"
                                                        value="<?= $model->tranid ?? "" ?>">

                                                    <select name="productid" class="form-select product-select mb-2 required"
                                                        style="width: 100%;">
                                                        <option value="<?= $modeldetail->productid ?? "" ?>" selected>
                                                            <?= $modeldetail->productid ? Yii::$app->function->findByField("productname", "products", " and productid ='" . $modeldetail->productid . "' ") : "" ?>
                                                        </option>
                                                    </select>

                                                    <div class="d-flex">
                                                        <textarea name="description" rows="3" class="form-control"
                                                            placeholder="Deskripsi"><?= $modeldetail->description ?? "" ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="fv-help-block"></div>
                                            </td>
                                            <td class="align-top">
                                                <div class="input-group">
                                                    <span class="input-group-text" style="width:70px">Qty</span>
                                                    <input type="number" name="amount" class="form-control product-quantity"
                                                        min="1" placeholder="Qty" value="<?= $modeldetail->amount ?? '0' ?>">
                                                </div>

                                                <div class="input-group product-stock-group">
                                                    <span class="input-group-text" style="width: 70px">Stock</span>
                                                    <input type="number" name="stockmilik"
                                                        class="form-control bg-light product-stock" readonly value="<?= $model->isNewRecord && $modeldetail->productid ?
                                                            $modeldetail->product->getStock(
                                                                (!empty($model->setupdate) ? date('Y-m-d', strtotime(str_replace('/', '-', $model->setupdate))) : date('Y-m-d')),
                                                                (!empty($model->withdrawaldate) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $model->withdrawaldate))) : date('Y-m-d H:i:s')),
                                                                $model->tranid
                                                            )['ready'] : $modeldetail->stockmilik ?? '0'; ?>">
                                                </div>
                                                <!-- <input type="text" class="yform-control" style="visibility: hidden;"> -->
                                                <div class="d-flex">
                                                    <span
                                                        class="text-danger info-stock <?= !$model->isNewRecord && $modeldetail->amount2 < 0 ? '' : 'd-none' ?>">Stock
                                                        Kurang <?= $modeldetail->amount2 ?? '0' ?></span>
                                                    <input type="text" name="amount2" class="form-control product-stock2"
                                                        style="visibility: hidden; width:1px;"
                                                        value="<?= $modeldetail->amount2 ?? '0' ?>">
                                                </div>
                                            </td>

                                            <td class="align-top">
                                                <input type="number" name="freqvalue" class="form-control product-freq-value"
                                                    min="1" placeholder="Freq" value="<?= $modeldetail->freqvalue ?? '0' ?>">

                                            </td>

                                            <td class="align-top">
                                                <input type="text" name="price" class="form-control money product-price price"
                                                    value="<?= $modeldetail->price ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <input type="text" name="itemsubtotaltax"
                                                    class="form-control money itemsubtotal" placeholder="Subtotal Tax" readonly
                                                    value="<?= $modeldetail->itemsubtotaltax ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <input type="number" name="itemdiscpersen"
                                                    class="form-control col-md-4 itemdiscpersen" placeholder="Disc %"
                                                    value="<?= $modeldetail->itemdiscpersen ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <select name="itemtaxid" class="form-select itemtaxid" style="min-width:100px;">
                                                    <option value="">...</option>

                                                    <option value="PPN" data-value="11" data-cut="0"
                                                        <?= (isset($modeldetail->itemtaxid) && $modeldetail->itemtaxid == 'PPN') ? 'selected' : '' ?>>PPN
                                                    </option>

                                                    <option value="PPH" data-value="2" data-cut="1"
                                                        <?= (isset($modeldetail->itemtaxid) && $modeldetail->itemtaxid == 'PPH') ? 'selected' : '' ?>>PPH
                                                    </option>
                                                </select>

                                                <input type="hidden" name="itemtax" class="form-control money itemtax"
                                                    value="<?= $modeldetail->itemtax ?? 0 ?>">
                                            </td>

                                            <td class="align-top">
                                                <input type="hidden" name="itemtotal"
                                                    class="form-control money itemtotal bg-light" readonly value="0">
                                                <input type="text" name="itemtotaltax"
                                                    class="form-control money itemtotaltax bg-light" placeholder="Total Aft Tax"
                                                    readonly value="<?= $modeldetail->itemtotaltax ?? 0 ?>">
                                            </td>
                                            <td class="align-top">
                                                <button type="button" data-repeater-delete
                                                    class="btn btn-sm btn-icon btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <button type="button" data-repeater-create class="btn btn-sm btn-primary ">
                            <i class="ki-duotone ki-plus fs-3"></i>
                            <?= Yii::$app->lang->t('add', 'add1') ?>
                        </button>
                    </div>

                    <div class="overflow-x-auto mw-100" style="min-width:0;">
                        <div class="d-flex flex-column align-items-end mt-3" style="min-width:350px;">

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 border-top pt-2 w-100">
                                <span class="fw-bold text-end" style="min-width:180px;">Sub Total (+):</span>
                                <input type="text" name="Tran[subtotal]"
                                    class="form-control bg-light text-end tfoot-subtotal money" style="width:200px;"
                                    value="<?= $model->subtotal ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                                <span class="fw-bold text-end"
                                    style="min-width:180px;"><?= Yii::$app->lang->t('extra', 'extra72') ?> (-):</span>
                                <input type="text" name="Tran[disc]"
                                    class="form-control bg-light text-end tfoot-disc money" style="width:200px;"
                                    value="<?= $model->disc ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                                <span class="fw-bold text-end"
                                    style="min-width:180px;"><?= Yii::$app->lang->t('extra', 'extra71') ?>:</span>
                                <input type="text" name="Tran[totalafterdisc]"
                                    class="form-control bg-light text-end tfoot-totalafterdisc money"
                                    style="width:200px;" value="<?= $model->totalafterdisc ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 border-top pt-2 w-100">
                                <select class="form-select form-select-sm" style="width:120px;">
                                    <option>PPN</option>
                                </select>
                                <span class="input-group-text">(+)</span>
                                <input type="text" name="Tran[ppnamount]"
                                    class="form-control bg-light tfoot-ppn text-end money" style="width:200px;"
                                    value="<?= $model->ppnamount ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                                <select class="form-select form-select-sm" style="width:120px;">
                                    <option>PPH</option>
                                </select>
                                <span class="input-group-text">(-)</span>
                                <input type="text" name="Tran[pphamount]"
                                    class="form-control bg-light tfoot-pph text-end money" style="width:200px;"
                                    value="<?= $model->pphamount ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                                <select class="form-select form-select-sm" style="width:120px;">
                                    <option>Other Discount</option>
                                </select>
                                <span class="input-group-text">(-)</span>
                                <input type="text" name="Tran[otherdiscount]"
                                    class="form-control bg-light text-end money" style="width:200px;"
                                    value="<?= $model->otherdiscount ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 w-100">
                                <select class="form-select form-select-sm" style="width:120px;">
                                    <option>Delivery Charge</option>
                                </select>
                                <span class="input-group-text">(+)</span>
                                <input type="text" name="Tran[deliverycharge]"
                                    class="form-control bg-light text-end money" style="width:200px;"
                                    value="<?= $model->deliverycharge ?? 0 ?>" readonly>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1 border-top pt-2 w-100">
                                <span class="fw-bold fs-5 text-end" style="min-width:180px;">Grand Total:</span>
                                <input type="text" name="Tran[grandtotal]"
                                    class="form-control bg-light fw-bold text-end fs-5 money" style="width:200px;"
                                    value="<?= $model->grandtotal ?? 0 ?>" readonly>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="tab-pane fade p-3" id="event" role="tabpanel" aria-labelledby="event-tab">
                    <div id="tranevent_repeater">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="text-gray-600 fw-bold fs-7 text-uppercase gs-0 text-center">
                                        <th class="min-w-120px">Event Type</th>
                                        <th class="min-w-175px">Start</th>
                                        <th class="min-w-175px">Finish</th>
                                        <th class="min-w-400px">Crew</th>
                                        <th class="min-w-50px"></th>
                                    </tr>
                                </thead>
                                <tbody class="event-rows" data-repeater-list="Tranevent">
                                    <?php if (!empty($modelevents)): ?>
                                        <?php foreach ($modelevents as $index => $modelevent): ?>
                                            <tr class="event-item" data-repeater-item>
                                                <td style="display: none;">
                                                    <input type="hidden" name="Tranevent[<?= $index ?>][traneventid]"
                                                        class="form-control" value=<?= $modelevent->traneventid ?? '' ?>>
                                                </td>
                                                <td class="min-w-150px">
                                                    <select name="eventtypeid" class="form-select event-select min-w-150px">
                                                        <option value="0" <?= ($modelevent->eventtypeid == 0) ? 'selected' : '' ?>>
                                                            Setup</option>
                                                        <option value="1" <?= ($modelevent->eventtypeid == 1) ? 'selected' : '' ?>>
                                                            Event</option>
                                                        <option value="2" <?= ($modelevent->eventtypeid == 2) ? 'selected' : '' ?>>
                                                            Bongkar</option>
                                                        <option value="3" <?= ($modelevent->eventtypeid == 3) ? 'selected' : '' ?>>
                                                            Antar</option>
                                                        <option value="4" <?= ($modelevent->eventtypeid == 4) ? 'selected' : '' ?>>
                                                            Tarik</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="startdate" class="form-control picktime"
                                                        value="<?= date('d/m/Y H:i', strtotime($modelevent->startdate ?? '')) ?>">

                                                </td>
                                                <td>
                                                    <input type="text" name="enddate" class="form-control picktime"
                                                        value="<?= date('d/m/Y H:i', strtotime($modelevent->enddate ?? '')) ?>">
                                                </td>
                                                <td>
                                                    <div class="inner-repeater">
                                                        <div class="table-responsive">
                                                            <table class="table table-row-dashed ">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <td class="min-w-150px">Position</td>
                                                                        <td class="min-w-300px">Crew</td>
                                                                        <td class="min-w-250px">Job</td>
                                                                        <td class="min-w-250px">Fee</td>
                                                                        <td class="min-w-250px">Dinas</td>
                                                                        <td class="min-w-250px">Dinas Fee</td>
                                                                        <td class="min-w-50px"></td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody data-repeater-list="Traneventcrew">
                                                                    <?php
                                                                    $modeleventcrews = $modelevent->traneventcrews ?? $modelevent->traneventcrew ?? [];

                                                                    if (empty($modeleventcrews)):
                                                                        $modeleventcrews = [new Traneventcrew];
                                                                        ?>
                                                                    <?php endif; ?>
                                                                    <?php foreach ($modeleventcrews as $index => $modeleventcrew): ?>
                                                                        <tr data-repeater-item>

                                                                            <input type="hidden" name="traneventcrewid"
                                                                                value="<?= $modeleventcrew->traneventcrewid ?>">

                                                                            <td class="min-w-150px">
                                                                                <select name="crewtypeid"
                                                                                    class="form-select crewtypeid-select">
                                                                                    <option value="pi"
                                                                                        <?= $modeleventcrew->crewtypeid == 'pi' ? 'selected' : '' ?>>PIC</option>
                                                                                    <option value="op"
                                                                                        <?= $modeleventcrew->crewtypeid == 'op' ? 'selected' : '' ?>>Operator</option>
                                                                                    <option value="sb"
                                                                                        <?= $modeleventcrew->crewtypeid == 'sb' ? 'selected' : '' ?>>Standby</option>
                                                                                    <option value="cr"
                                                                                        <?= $modeleventcrew->crewtypeid == 'cr' ? 'selected' : '' ?>>Crew</option>
                                                                                    <option value="dr"
                                                                                        <?= $modeleventcrew->crewtypeid == 'dr' ? 'selected' : '' ?>>Driver</option>
                                                                                    <option value="fe"
                                                                                        <?= $modeleventcrew->crewtypeid == 'fe' ? 'selected' : '' ?>>Freelance</option>
                                                                                </select>
                                                                            </td>

                                                                            <td class="min-w-300px">
                                                                                <select name="crewid"
                                                                                    class="form-select crew-select">
                                                                                    <?php
                                                                                    if (!$model->isNewRecord && $modeleventcrew->crewid) {
                                                                                        ?>
                                                                                        <option
                                                                                            value="<?= $modeleventcrew->crewid ?? "" ?>"
                                                                                            selected>
                                                                                            <?= $modeleventcrew->crewid ? Yii::$app->function->findByField("(contact_no ||' ['||contact_name ||']')", "contacts", " and contact_id ='" . $modeleventcrew->crewid . "' ") : "" ?>
                                                                                        </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>

                                                                            <td class="min-w-250px">
                                                                                <select name="jobid"
                                                                                    class="form-select job-select min-w-250px">

                                                                                    <?php if ($modeleventcrew->jobid): ?>
                                                                                        <option value="<?= $modeleventcrew->jobid ?>"
                                                                                            selected>
                                                                                            <?= Yii::$app->function->findByField(
                                                                                                "enumtext_id",
                                                                                                "enum",
                                                                                                "and enumid ='" . $modeleventcrew->jobid . "'"
                                                                                            ) ?>
                                                                                        </option>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>

                                                                            <td class="min-w-250px">
                                                                                <input type="text" name="fee"
                                                                                    class="form-control money fee" value="<?= $modeleventcrew->fee ?? '0'
                                                                                        ?>" readonly>
                                                                            </td>

                                                                            <td class="min-w-250px">
                                                                                <select name="dinasid"
                                                                                    class="form-select dinas-select min-w-250px">
                                                                                    <?php if ($modeleventcrew->dinasid): ?>
                                                                                        <option value="<?= $modeleventcrew->dinasid ?>"
                                                                                            selected>
                                                                                            <?= Yii::$app->function->findByField(
                                                                                                "enumtext_id",
                                                                                                "enum",
                                                                                                "and enumid ='" . $modeleventcrew->dinasid . "'"
                                                                                            ) ?>
                                                                                        </option>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>

                                                                            <td class="min-w-250px">
                                                                                <input type="text" name="dinasfee"
                                                                                    class="form-control money dinasfee" value="<?= $modeleventcrew->dinasfee ?? '0'
                                                                                        ?>">
                                                                            </td>

                                                                            <td class="w-50px mx-2">
                                                                                <button class="btn btn-sm btn-danger m-2"
                                                                                    data-repeater-delete type="button">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <button class="btn btn-sm btn-primary" data-repeater-create
                                                            type="button">
                                                            <i class="ki-duotone ki-plus fs-2"></i>
                                                            <?= Yii::$app->lang->t('home', 'add') ?>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td>
                                                    <button type="button" data-repeater-delete
                                                        class="btn btn-sm btn-icon btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button type="button" href="javascript:;" data-repeater-create
                                class="btn btn-sm btn-primary repeat-parent-btn">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                <?= Yii::$app->lang->t('home', 'add') ?>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade p-3" id="po" role="tabpanel" aria-labelledby="po-tab">
                    <div class="add-po" style="padding:6px; text-align:start; border-top:1px solid #ddd;">
                        <button type="button" class="btn btn-sm btn-primary add-po" data-url="/tran/createpo"
                            data-refid="<?= $model->tranid ?>">
                            <i class="fas fa-plus-circle me-2"></i> Add Po
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-left table-row-dashed fs-6 gy-5" id="datatable-po">
                            <thead>
                                <tr class="text-start bg-gray-100 fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="d-none"></th>
                                    <!-- <th class="text-center min-w-50px mx-1">
                                        <div class="form-check form-check-custom form-check-solid form-check-sm px-3">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th> -->
                                    <th class="text-center min-w-50px">
                                        <?= Yii::$app->lang->t('produk_table', 'produk_action') ?>
                                    </th>
                                    <th class="text-start min-w-50px">Detail</th>
                                    <th class="text-center min-w-150px">Status</th>
                                    <th class="text-start min-w-200px">
                                        <?= Yii::$app->lang->t('tran', 'tran_no') ?>
                                    </th>
                                    <th class="text-start min-w-200px">
                                        <?= Yii::$app->lang->t('front_home', 'vendor') ?>
                                    </th>
                                    <th class="text-start min-w-150px">Date</th>
                                    <th class="text-start min-w-150px">Total</th>
                                    <th class="text-start min-w-150px">Created By</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold text-start">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush mb-5">
        <div class="card-header pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-4"><i
                        class="fa-solid fa-note-sticky text-primary me-2"></i>Catatan</span>
            </h3>
        </div>
        <div class="card-body pt-3">
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'note', [
                        'errorOptions' => ['class' => 'text-danger mt-3'],
                    ])->textarea([
                                'placeholder' => $model->getAttributeLabel('note'),
                                'rows' => 3,
                                'class' => 'form-control',
                            ])->label(false); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end pt-5">
        <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
            data-bs-dismiss="modal">Close</button>
        <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        setFormTran();
        syncFreqWithTerm();

        function showBootstrapAlert(message, type) {
            let alertPlaceholder = document.getElementById("liveAlertPlaceholder");
            if (!alertPlaceholder) {
                alertPlaceholder = document.createElement("div");
                alertPlaceholder.id = "liveAlertPlaceholder";
                document.querySelector(".card-body").prepend(alertPlaceholder);
            }

            let wrapper = document.createElement("div");
            wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">
                <div class="me-3">
                    ${type === 'success' ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : ''}
                    ${type === 'danger' ? '<i class="fas fa-exclamation-circle fa-lg text-danger"></i>' : ''}
                    ${type === 'warning' ? '<i class="fas fa-exclamation-triangle fa-lg text-warning"></i>' : ''}
                    ${type === 'info' ? '<i class="fas fa-info-circle fa-lg text-info"></i>' : ''}
                </div>
                <div>${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;

            alertPlaceholder.innerHTML = "";
            alertPlaceholder.append(wrapper);

            setTimeout(function () {
                const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
                if (alert) alert.close();
            }, 5000);
        }

        var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
        var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
        var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
        $("#datatable-po").DataTable({
            scrollX: true,
            autoWidth: false,
            processing: false,
            serverSide: false,
            lengthMenu: [15, 30, 50, 75, 100],
            pageLength: 15,
            order: [],
            language: {
                info: `${translate1}`,
                infoEmpty: `${translate2}`,
                emptyTable: `
                <div style="text-align: center; padding: 20px 0;">
                   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                    <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
                </div>
            `,
                zeroRecords: `
                <div style="text-align: center; padding: 20px 0;">
                   <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                    <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
                </div>
            `,
                loadingRecords: `
                <div style="text-align: center; padding: 20px 0;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div style="margin-top: 10px;">Loading...</div>
                </div>
            `
            },
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected text-center'
            },
            ajax: {
                type: "GET",
                dataSrc: "data",
                url: "<?= Url::to(['tran/getpo']) ?>",
                data: function (d) {
                    const contact = $('select[name="contact"]').val();
                    const search = $('input[name="search"]').val();
                    const datefilter = $('input[name="datefilter"]').val();
                    const status = $('select[name="status"]').val();
                    const refid = $('button[data-refid]').attr('data-refid');

                    d.contact = contact;
                    d.search = search;
                    d.datefilter = datefilter;
                    d.status = status;
                    d.refid = refid;

                    const params = new URLSearchParams();
                    if (contact) params.set('contact', contact);
                    if (search) params.set('search', search);
                    if (datefilter) params.set('datefilter', datefilter);
                    if (status) params.set('status', status);
                    if (refid) params.set('refid', refid);

                    const newUrl = window.location.pathname + '?' + params.toString();
                    window.history.replaceState({}, '', newUrl);
                },
                complete: function () {
                    $('.table-loading-overlay').remove();
                }
            },
            columns: [{
                data: "tranid",
                visible: false
            },
            {
                render: function (data, type, row) {
                    let rowModule = '<?= $module ?>';
                    let rowType = '<?= $type ?>';

                    if (row.trantype && row.trantype.includes('/')) {
                        const parts = row.trantype.split('/');
                        rowModule = parts[0];
                        rowType = parts[1];
                    }


                    return `
                    <div class="dropdown text-center dropend">
                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fa-sharp fa-solid fa-list"></i>
                        </button>
                        <ul class="dropdown-menu px-2">
                            <li>
                                <a class="dropdown-item text-hover-primary btn-edit-po"
                                    href="javascript:void(0)" data-url="/tran/updatepo" data-tranid="${row.tranid}">
                                <i class="fas fa-edit"></i> <?= (Yii::$app->lang->t('back_home', 'chat64')) ?>
                                </a>
                            </li>
                            <li>
                                <button class="dropdown-item text-hover-danger delete-po"
                                    type="button" data-id="${row.tranid}">
                                <i class="fas fa-trash"></i> <?= (Yii::$app->lang->t('back_home', 'chat53')) ?>
                                </button>
                            </li>
                        </ul>
                    </div>`;
                }
            },
            {
                data: "tranid",
                className: "text-center w-10px",
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-light-primary btn-icon neo-orba-po" type="button" data-id="${row.tranid}">
                            <i class="fa-solid fa-caret-right"></i>
                        </button>`;
                }
            },
            {
                data: "status",
                className: "text-start",
                render: function (data, type, row) {
                    let statusInfo = {
                        0: {
                            text: "Draft",
                            color: "primary",
                            icon: "fa-file-alt"
                        },
                        1: {
                            text: "Approved",
                            color: "success",
                            icon: "fa-check-circle"
                        },
                        5: {
                            text: "Cancelled",
                            color: "warning",
                            icon: "fa-ban"
                        },
                        10: {
                            text: "Rejected",
                            color: "danger",
                            icon: "fa-times-circle"
                        }
                    };

                    let statusText = "Unknown";
                    let statusBg = "secondary";
                    let statusIcon = "fa-question-circle";
                    let statusOptions = "";

                    if (statusInfo[data]) {
                        statusText = statusInfo[data].text;
                        statusBg = statusInfo[data].color;
                        statusIcon = statusInfo[data].icon;

                        if (data == 0) {
                            statusOptions = `
                                <li>
                                    <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="1">
                                        <i class="fas fa-check-circle text-success me-2"></i> Approve
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="5">
                                        <i class="fas fa-ban text-warning me-2"></i> Cancel
                                    </a>
                                </li>
                                `;
                        } else if (data == 1) {
                            statusOptions = `
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="5">
                                        <i class="fas fa-ban text-warning me-2"></i> Cancel
                                    </a></li>
                                `;
                        } else if (data == 5) {
                            statusOptions = `
                                    <li><a class="dropdown-item items update-status d-flex align-items-center" href="#" data-id="${row.tranid}" data-status="1">
                                        <i class="fas fa-check-circle text-success me-2"></i> Approve
                                    </a></li>
                                `;
                        }
                    }

                    return `
                        <div class="dropdown dropend">
                            <button class="btn btn-${statusBg} btn-sm px-3 py-2 d-flex align-items-center justify-content-center mx-auto"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="min-width: 110px; border-radius: 6px;">
                                <i class="fas ${statusIcon} me-2"></i>
                                <span>${statusText}</span>
                                <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size: 0.8em;"></i>
                            </button>
                            <ul class="dropdown-menu py-2 shadow-sm">${statusOptions}</ul>
                        </div>`;
                }
            },
            {
                data: "tranno",
                className: "text-start",
                render: function (data, type, row) {
                    return `
                        <a class="dropdown-item text-hover-success detail-po" 
                        data-id="${row.tranid}" style="cursor: pointer; ">
                            ${data}
                        </a>`;
                }
            },
            {
                data: "contact_name",
                className: "text-start",
                defaultContent: "-",
                render: function (data, type, row) {
                    return `
                        <div class="d-flex flex-column align-items-left mx-auto my-auto w-100">
                         <small class="text text-start text-gray-800"> <i class="fa-solid fa-user me-2 text-gray-800"></i>
                        ${row.contact_name || '-'}</small>
                        <small class="text text-start text-gray-800"> <i class="fa-solid fa-building me-2 text-gray-800"></i>
                        ${row.jobcompany || '-'}</small>     
                        </div>
                        `;
                }
            },
            {
                data: "trandate",
                className: "text-start",
                render: function (data) {
                    if (!data) return '-';
                    // Convert date format if needed
                    const date = new Date(data);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                }
            },
            {
                data: "grandtotal",
                className: "text-start",
                render: function (data) {
                    return data ?
                        `<span class="text text-end">${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</span>` :
                        "-";
                }
            },
            {
                data: 'name',
                className: "text-start",
                render: function (data) {
                    return `<span class="text text-start">${data || '-'}</span>`;
                }
            }
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).css('cursor', 'pointer');

                let rowModule = '<?= $module ?>';
                let rowType = '<?= $type ?>';

                if (data.trantype && data.trantype.includes('/')) {
                    const parts = data.trantype.split('/');
                    rowModule = parts[0];
                    rowType = parts[1];
                }

                $(row).attr('data-url', `<?= Url::to(['detail']) ?>?id=${data.tranid}&module=${rowModule}&type=${rowType}`);
            },
            initComplete: function () {
                addNestedDropdown();
                const url = $(this).attr('data-url');
                // console.log(url);
                if (url) {
                    window.location.href = url;
                }
                $('#datatable-po tbody').on('click', 'tr', function (e) {
                    if (
                        $(e.target).closest('.select-checkbox').length || // Checkbox
                        $(e.target).closest('.dropdown').length || // Seluruh dropdown container
                        $(e.target).closest('button').length || // Button elements
                        $(e.target).closest('.dropdown-menu').length || // Dropdown menu
                        $(e.target).closest('a').length || // Link elements
                        $(e.target).is('button') || // Button element itself
                        $(e.target).is('input') || // Input element itself
                        $(e.target).is('a') // Link element itself
                    ) {
                        return;
                    }
                });
            }
        });

        function addNestedDropdown() {
            $(document).off('click', '.neo-orba-po').on('click', '.neo-orba-po', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var button = $(this);
                var parentId = button.data('id');
                var row = button.closest('tr');
                var expandRow = row.next('tr.expanded-row');

                if (expandRow.length && expandRow.is(':visible')) {
                    expandRow.remove();
                    button.html('<i class="fa-solid fa-caret-right"></i>');
                    return;
                }

                $('tr.expanded-row').remove();
                $('button.neo-orba-po').html('<i class="fa-solid fa-caret-right"></i>');

                if (row.hasClass('child-row')) {
                    if (expandRow.length && expandRow.is(':visible')) {
                        expandRow.remove();
                        return;
                    }
                    button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                }

                if (expandRow.length && expandRow.is(':visible')) {
                    expandRow.find('tr.expanded-row').remove();
                    expandRow.remove();
                    return;
                }

                $('tr.expanded-row').remove();

                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                var subTableHTML = `
                <tr class="expanded-row">
                    <td colspan="10">
                        <div class="m-3 row">
                            <div class="col-md-6 ps-3 border-end border-gray-300 pe-3">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 sub-datatable"
                                    id="sub-table-right-${parentId}">
                                    <thead>
                                        <tr class="text-gray-500 bg-gray-100 border-bottom border-gray-200 fs-7 text-uppercase">
                                            <th class="text-start">Product</th>
                                            <th class="text-center">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-800 fw-semibold text-center"></tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>`;

                row.after(subTableHTML);
                button.html('<i class="fa-solid fa-caret-down"></i>');

                if ($.fn.DataTable.isDataTable(`#sub-table-right-${parentId}`)) {
                    $(`#sub-table-right-${parentId}`).DataTable().destroy();
                }

                var subTableReturn = $(`#sub-table-right-${parentId}`).DataTable({
                    processing: true,
                    serverSide: true,
                    lengthMenu: [5, 10, 25, 50],
                    pageLength: 5,
                    ajax: {
                        url: `/tran/listdetail`,
                        type: 'GET',
                        data: function (d) {
                            d.id = parentId;
                            d.module = "purchase";
                            d.type = "order";
                            d.select = "order"
                        }
                    },
                    columns: [
                        {
                            data: "productname",
                            className: "text-start",
                            render: function (data) {
                                return data || '0';
                            }
                        },
                        {
                            data: "amount",
                            className: "text-center",
                            render: function (data) {
                                return `<span class="text text-center">${data || '-'}</span>`;
                            }
                        },


                    ],
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: '_all'
                    }]
                    // order: [
                    //     [2, 'asc']
                    // ]
                });
            });
        }

    });

    var currentModule = '<?= $module ?? 'purchase' ?>';
    var currentType = '<?= $type ?? 'request' ?>';

    $(document).on('shown.bs.tab', 'button[data-bs-target="#po"]', function () {
        if ($.fn.DataTable.isDataTable('#datatable-po')) {
            $('#datatable-po').DataTable().ajax.reload();
            $('#datatable-po').DataTable().columns.adjust().draw();
        }
    });

    function setFormTran() {
        if (<?php echo $isajax; ?>) {
            var form = $('#modal_form_tran').find('#tran-form');

            form.on('keypress', function (e) {
                if (e.key == 'Enter' && !$(e.target).is('textarea')) {
                    e.preventDefault();
                    return false;
                }
            });

            form.on('submit', function (e) {
                e.preventDefault();

                var contactId = $("select[name='Tran[contact_id]']").val();
                if (!contactId || contactId === '') {
                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: "Customer wajib dipilih!"
                    });
                    return false;
                }
                // console.log(form.serializeArray());return;
                $('#btnsubmit').prop('disabled', true);

                var formData = form.serialize();
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function (data) {
                        if (data['success']) {
                            Swal.fire({
                                icon: "success",
                                title: "Successful",
                                html: data['pesan']
                            });

                            $('#tran-id').val(data['id']);

                            var updateUrl = '<?= Url::to(['updatepro']) ?>?id=' + data['id'];
                            form.attr('action', updateUrl);

                            $('#btnsubmit').prop('disabled', false);

                            $('.add-po button').attr('data-refid', data['id']);
                            $('.btn-product').attr('data-tranid', data['id']);
                            $('#modal_form_po').attr('data-current-tranid', data['id']);

                            if ($.fn.DataTable.isDataTable('#datatable-po')) {
                                $('#datatable-po').DataTable().ajax.reload();
                            }

                            if ($.fn.DataTable.isDataTable('#datatable')) {
                                $('#datatable').DataTable().ajax.reload();
                            }

                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                html: data['pesan']
                            });
                            $('#btnsubmit').prop('disabled', false);
                        }
                    },
                    error: function (e) {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            html: "Something went wrong, please call your Administrator!",
                        });
                        $('#btnsubmit').prop('disabled', false);
                    }
                });
            });
        }

        selectContact($('#modal_form_tran'), "select[name='Tran[contact_id]']", 'customer', '', 'Customer');
        selectContact($('#modal_form_tran'), "select[name='Tran[pic1id]']", 'employee', 'cr', 'PIC');
        selectContact($('#modal_form_tran'), "select[name='Tran[pic2id]']", 'employee', 'cr', 'PIC');
        selectContact($('#modal_form_tran'), "select[name='Tran[operatorid]']", 'employee', 'mo', 'Manager OP');
        selectContact($('#modal_form_tran'), "select[name='Tran[storemanid]']", 'employee', 'wm', 'Warehouse Manager');
        selectContact($('#modal_form_tran'), "select[name='Tran[aeid]']", 'employee', 'ae', 'AE');

        defaultDetail = {
            'amount': '0',
            'price': '0',
            "stock": "0",
            "freqvalue": "1",
            "itemsubtotaltax": "0",
            "itemtotaltax": "0",
            "itemdiscpersen": "0"
        }

        repeat('#trandetail_repeater', defaultDetail);
        repeatNested('#tranevent_repeater', '.inner-repeater');
        setFunction();
    }

    function selectEnum(selector, enumtype, addNewText, refid = null) {
        $(selector).select2({
            placeholder: 'Select ' + addNewText,
            allowClear: true,
            cache: true,
            ajax: {
                url: "<?= Url::to(['enum/list']) ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let data = {
                        enumtype: enumtype,
                        search: params.term || '',
                        page: params.page || 1,
                        for: 'select2'
                    };

                    if (refid) {
                        if (typeof refid === 'function') {
                            data.refid = refid($(this));
                        } else {
                            data.refid = refid;
                        }
                    }

                    return data;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    var results = data.data.map(function (item) {
                        return {
                            id: item.enumid,
                            text: item.enumtext_id,
                            amount: item.amount,
                        };
                    });

                    return {
                        results: results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                }
            },
            // minimumInputLength: 0,
            width: '100%',
            dropdownParent: $(selector).closest('.modal-content').length ? $(selector).closest('.modal-content') : $(selector),
            dropdownAutoWidth: true
        });
    };

    function selectCrew(target, selection, type) {
        $(selection).select2({
            ajax: {
                url: "<?= Url::to(['contact/select']) ?>",
                type: "POST",
                dataType: "json",

                data: function (params) {
                    const $currentSelect = $(this);
                    let positionid = $currentSelect.closest('tr[data-repeater-item]').find('.crewtypeid-select').val();
                    const $eventRow = $currentSelect.closest('tr.event-item');
                    const selectedDate = $eventRow.find('input[name*="startdate"]').val();

                    let selectedMap = {};

                    $('tr[data-repeater-item].event-item').each(function () {
                        const itemDate = $(this).find('input[name*="startdate"]').val();

                        if (!itemDate) return;
                        selectedMap[itemDate] = [];

                        $(this).find('tbody[data-repeater-list="Traneventcrew"] tr[data-repeater-item]').each(function () {
                            const crewid = $(this).find('select.crew-select').val();
                            if (crewid) {
                                selectedMap[itemDate].push(crewid);
                            }
                        });
                    });

                    return {
                        contacttype: type,
                        positionid: positionid,
                        search: params.term || '',
                        q: params.term,
                        selected: JSON.stringify(selectedMap),
                        date: selectedDate,
                        page: params.page,
                        module: "<?= $module ?? 'purchase' ?>"
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 5) < data.totalcount
                        }
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateSelection: function (param) {
                if (!param.id) {
                    return "Choose";
                }
                return param.text;
            },
            templateResult: function (param) {
                if (!param.id) {
                    return "Choose";
                }
                if (param.loading) {
                    return param.text;
                }
                var span = document.createElement('span');
                var template = '';
                template += param.text;
                span.innerHTML = template;
                return span;
            },
            placeholder: "Choose ",
            allowClear: true,
            width: '100%',
            dropdownParent: $(target).closest('.modal-content').length ? $(target).closest('.modal-content') : $(target),
            dropdownAutoWidth: true,
        }).on('select2:opening.crew', function (e) {
            let positionid = $(this).closest('tr[data-repeater-item]').find('.crewtypeid-select').val();

            if (!positionid || positionid.trim() === '') {
                e.preventDefault();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih Position terlebih dahulu!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('Silakan pilih Position terlebih dahulu!');
                }
            }
        });
    }

    function selectContact(target, selection, type, positionid, label) {
        let $select = $(target).find(selection);
        let $modalContent = $select.closest('.modal-content');
        let $dropdownParent = $modalContent.length ? $modalContent : $(target);

        $select.off('change.contact').off('select2:open.contact').select2({
            ajax: {
                url: "<?= Url::to(['contact/select']) ?>",
                type: "POST",
                dataType: "json",
                data: function (params) {
                    return {
                        contacttype: type,
                        positionid: positionid,
                        q: params.term,
                        page: params.page,
                        module: "<?= $module ?? 'purchase' ?>"
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: { more: (params.page * 5) < data.totalcount }
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) { return markup; },
            templateSelection: function (param) {
                if (!param.id) return `Choose ${label}`;
                return type === 'customer' ? (param.jobcompany || param.text) : param.text;
            },
            templateResult: function (contact) {
                if (!contact.id) return `Choose ${label}`;
                if (contact.loading) return type === 'customer' ? contact.jobcompany : contact.text;
                let html = `
                <div class="d-flex align-items-start gap-2 py-1">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;font-size:13px;font-weight:600;">
                        ${type === 'customer' ? contact.jobcompany.charAt(0).toUpperCase() : contact.text.charAt(0).toUpperCase()}
                    </div>
                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                        <span class="fw-semibold text-dark text-truncate" style="font-size:13px;">
                            ${type === 'customer' ? contact.jobcompany : contact.text}
                        </span>
                    </div>
                </div>`;
                return $(html);
            },
            placeholder: `Choose ${label}`,
            allowClear: true,
            width: '100%',
            dropdownParent: $dropdownParent
        }).on('select2:open.contact', function () {
            let $dropdown = $('.select2-dropdown');
            $dropdown.find('.add-new-ctc-btn').remove();
            $dropdown.find('.add-new-prd-btn').remove();

            $dropdown.append(`
            <div class="add-new-ctc-btn" style="padding:6px; text-align:center; border-top:1px solid #ddd;">
                <button type="button" class="btn btn-sm btn-primary" data-type="${type}" data-position="${positionid}">
                    <i class="fas fa-plus-circle me-2"></i> Add New ${label}
                </button>
            </div>
        `);
        }).on('select2:select', function (e) {
            if (type === 'customer') generateContact($(this));
        });
    }

    function generateContact($selectElement) {
        let $select = ($selectElement && $selectElement.length) ? $selectElement : $("select[name='Tran[contact_id]']");
        let contactId = $select.val();
        let $form = $select.closest('form');
        let selectedPic = $form.find("input[name='Tran[picradio]']:checked").val();
        // console.log('Selected PIC:', selectedPic);
        // console.log('Contact ID:', contactId);

        if (!contactId) return;

        $.ajax({
            url: "<?= Url::to(['contact/loadref']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                contactid: contactId
            },
            success: function (response) {
                if (response.data && response.data.length > 0) {
                    let item = response.data[0];
                    let name = '';
                    let phone = '';

                    if (selectedPic == '1') {
                        name = item.contact_name;
                        phone = item.contact_phone1;
                    } else if (selectedPic == '2') {
                        name = item.contact_name2;
                        phone = item.contact_phone3;
                    } else if (selectedPic == '3') {
                        name = item.contact_name3;
                        phone = item.contact_phone4;
                    }

                    $form.find("input[name='Tran[piccustomer]']").val(name || '');
                    $form.find("input[name='Tran[piccustomer_telp]']").val(phone || '');
                }
            }
        });
    }

    function getSelectedProductIds() {
        let selectedIds = [];
        $(".product-select").each(function () {
            let val = $(this).val();
            if (val) {
                selectedIds.push(val.toString());
            }
        });
        return selectedIds;
    }

    function setFunction() {
        selectCrew($('.event-rows'), ".crew-select", 'employee', 'cr');
        selectEnum($('.job-select'), 'job', 'Job', function ($select) {
            return 'position.' + $select.closest('tr[data-repeater-item]').find('.crewtypeid-select').val();
        });
        selectEnum($('.dinas-select'), 'dinas', 'Dinas', '');
        // selectEnum($('.tax-select'), 'tax', 'Tax', '');

        $(".product-select").select2({
            ajax: {
                url: "<?= Url::to(['product/search']) ?>",
                type: "GET",
                dataType: "json",
                data: function (params) {
                    let limit = 10;
                    let page = params.page || 1;
                    return {
                        q: params.term,
                        page: page,
                        limit: limit,
                        offset: (page - 1) * limit,
                        module: "<?= $module ?? 'purchase' ?>"
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    let selectedIds = getSelectedProductIds();
                    return {
                        results: data.data.filter(function (item) {
                            return !selectedIds.includes(item.value.toString());
                        }).map(function (item) {
                            return {
                                id: item.value,
                                text: item.productname,
                                productname: item.productname,
                                productcode: item.productcode,
                                productpict: item.productpict
                            };
                        }),
                        pagination: { more: data.hasMore }
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) { return markup; },
            templateSelection: function (param) {
                return !param.id ? "Choose Product" : param.text;
            },
            templateResult: function (param) {
                if (!param.id) return param.text;
                if (param.loading) return param.text;

                let img = param.productpict ? `/uploads/produk/${param.productpict}` : `/uploads/produk/default.png`;

                return $(`
                <div class="d-flex align-items-center" style="white-space: normal;">
                    <img class="me-2" src="${img}" 
                        style="width: 40px; height: 40px; object-fit: cover; flex-shrink: 0;"
                        onerror="this.style.display='none'">
                    <div class="d-flex flex-column" style="min-width: 0;">
                        <strong style="font-size: 0.9rem; line-height: 1.2;">${param.text}</strong>
                        <span class="text-muted small">${param.productcode}</span>
                    </div>
                </div>
                `);
            },
            placeholder: "Choose Product",
            allowClear: true,
            width: '100%',
            containerCss: { "display": "block", width: "100%" },
            dropdownParent: $(".product-select").closest('.modal-content').length ? $(".product-select").closest('.modal-content') : $('#trandetail_repeater')
        }).on('select2:open.product', function () {
            let $dropdown = $('.select2-dropdown');
            $dropdown.find('.add-new-prd-btn').remove();
            $dropdown.find('.add-new-ctc-btn').remove();

            $dropdown.append(`
            <div class="add-new-prd-btn" style="padding:6px; text-align:center; border-top:1px solid #ddd;">
                <button type="button" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Add New Product
                </button>
            </div>
        `);
        }).on('change', function (e) {
            let val = $(this).val();
            let row = $(this).closest('tr');
            if (val) {
                fetchProductPrice(row, 'qty');
            } else {
                row.find('.product-stock').val(0);
            }
        }).on('select2:unselect', function (e) {
            let row = $(this).closest('tr');
            row.find('.product-stock').val(0);
        });

        initMasking();
    }

    function setTerm() {
        var termInput = $(document).find("input[name='Tran[term]']");
        var day = parseInt(termInput.val()) || 1;
        var tranDateInput = $(document).find("input[name='Tran[trandate]']").val();

        if (tranDateInput) {
            var nextday = addDays(tranDateInput, day - 1);
            $(document).find("input[name='Tran[tranduedate]']").val(nextday);
            var nextWithdrawal = nextday ? nextday + ' 00:00' : '';

            var $withdrawal = $(document).find("input[name='Tran[withdrawaldate]']");
            $withdrawal.val(nextWithdrawal).trigger('change');

            var $setupdate = $(document).find("input[name='Tran[setupdate]']");
            if (!$setupdate.val().trim()) {
                var prevDay = addDays(tranDateInput, -1);
                $setupdate.val(prevDay + ' 00:00').trigger('change');
            }
        }
    }

    function setTermDate() {
        var startVal = $(document).find("input[name='Tran[trandate]']").val();
        var endVal = $(document).find("input[name='Tran[tranduedate]']").val();

        if (!startVal || !endVal) return;

        var start = StringtoDate(startVal);
        var end = StringtoDate(endVal);

        if (isNaN(start.getTime()) || isNaN(end.getTime())) return;

        var diffTime = end - start;
        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        if (diffDays < 1) diffDays = 1;

        $(document).find("input[name='Tran[term]']").val(diffDays);
    }

    function repeat(selection, defaultValue) {
        $(selection).repeater({
            initEmpty: false,
            defaultValue: defaultValue,

            show: function () {
                $(this).slideDown();
                setFunction();

                let currentTerm = parseFloat($("input[name='Tran[term]']").val()) || 1;

                $(this).find('.product-quantity').val(0);
                $(this).find('.product-stock').val(0);

                $(this).find('.product-freq-value').val(currentTerm);

                $(this).find('.price').val('0');
                $(this).find('.itemsubtotal').val('0');
                $(this).find('.itemdiscpersen').val(0);
                $(this).find('.itemtotaltax').val('0');
            },

            hide: function (deleteElement) {
                $(this).slideUp(function () {
                    deleteElement();
                    setTotal();
                });
            }
        });
    }

    function repeatNested(selection, inner) {
        let hasExistingEvents = $(selection).find('.event-item input[name*="[traneventid]"]').filter(function () {
            return $(this).val() !== '';
        }).length > 0;

        $(selection).repeater({
            initEmpty: !hasExistingEvents, 

            repeaters: [{
                selector: inner,
                initEmpty: true, 
                show: function () {
                    $(this).slideDown();
                    setFunction();
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            }],

            show: function () {
                $(this).slideDown();
                setFunction();

                $(this).find('.picktime').val('');
                $(this).find('.event-select').val('0');
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    function setTotal() {
        var sum_gross_subtotal = 0; // Menampung subtotal kotor sebelum diskon & pajak
        var sum_itemdisc = 0;       // Total diskon item
        var sum_dpp = 0;            // Total Dasar Pengenaan Pajak (Netto sebelum pajak)
        var sum_tax_ppn = 0;
        var sum_tax_pph = 0;

        var taxInclude = $("#kt_flexSwitchCustomDefault_1_1").is(":checked");

        $('tbody.detail-rows > tr').each(function () {
            var $row = $(this);

            var itemprice = parseFloat(($row.find("input[name*='[price]']").val() || "").replace(/[^0-9.-]/g, '')) || 0;
            var itemqty = parseFloat(($row.find("input[name*='[amount]']").val() || "0")) || 0;
            var freq = parseFloat(($row.find("input[name*='[freqvalue]']").val() || "1")) || 1;
            var itemdiscpersen = parseFloat(($row.find("input[name*='[itemdiscpersen]']").val() || "0")) || 0;

            var subtotal = itemprice * itemqty * freq;
            var itemdisc = subtotal * (itemdiscpersen / 100);
            var subtotal_afterdisc = subtotal - itemdisc; // Ini nilai net per baris (bisa include/exclude pajak)

            var $taxSelect = $row.find("select[name*='[itemtaxid]']");
            var taxType = $taxSelect.val();
            var ppnRate = parseFloat($taxSelect.find("option[value='PPN']").data("value")) || 0;
            var pphRate = parseFloat($taxSelect.find("option[value='PPH']").data("value")) || 0;

            var taxPPN = 0, taxPPH = 0;
            var dpp_item = subtotal_afterdisc; // Default jika tidak ada pajak / exclude

            if (taxInclude) {
                // Jika pajak di dalam, keluarkan DPP (base price) terlebih dahulu
                if (taxType === "PPN") {
                    dpp_item = subtotal_afterdisc / (1 + (ppnRate / 100));
                    taxPPN = subtotal_afterdisc - dpp_item;
                } else if (taxType === "PPH") {
                    dpp_item = subtotal_afterdisc / (1 + (pphRate / 100));
                    taxPPH = subtotal_afterdisc - dpp_item;
                } else if (taxType === "PPN+PPH") {
                    dpp_item = subtotal_afterdisc / (1 + (ppnRate / 100));
                    taxPPN = subtotal_afterdisc - dpp_item;
                    taxPPH = dpp_item * (pphRate / 100); // PPH dihitung dari nilai DPP
                }
            } else {
                // Jika pajak di luar, DPP adalah nilai setelah diskon itu sendiri
                if (taxType === "PPN") {
                    taxPPN = dpp_item * (ppnRate / 100);
                } else if (taxType === "PPH") {
                    taxPPH = dpp_item * (pphRate / 100);
                } else if (taxType === "PPN+PPH") {
                    taxPPN = dpp_item * (ppnRate / 100);
                    taxPPH = dpp_item * (pphRate / 100);
                }
            }

            var totalAftTax = taxInclude ? (dpp_item + taxPPN - taxPPH) : (subtotal_afterdisc + taxPPN - taxPPH);

            $row.find("input[name*='[itemsubtotaltax]']").val(dpp_item.toFixed(2));
            $row.find("input[name*='[itemtax]']").val((taxPPN - taxPPH).toFixed(2));
            $row.find("input[name*='[itemtotaltax]']").val(totalAftTax.toFixed(2));

            sum_gross_subtotal += subtotal;
            sum_itemdisc += itemdisc;
            sum_dpp += dpp_item;
            sum_tax_ppn += taxPPN;
            sum_tax_pph += taxPPH;
        });

        var otherdiscount = parseFloat(($("input[name='Tran[otherdiscount]']").val() || "").replace(/[^0-9.-]/g, '')) || 0;
        var deliverycharge = parseFloat(($("input[name='Tran[deliverycharge]']").val() || "").replace(/[^0-9.-]/g, '')) || 0;

        var total_afterdisc = 0;
        var grandtotal = 0;

        if (taxInclude) {
            total_afterdisc = sum_dpp;
            grandtotal = total_afterdisc + sum_tax_ppn - sum_tax_pph - otherdiscount + deliverycharge;
        } else {
            total_afterdisc = sum_gross_subtotal - sum_itemdisc;
            grandtotal = total_afterdisc + sum_tax_ppn - sum_tax_pph - otherdiscount + deliverycharge;
        }

        $("input[name='Tran[subtotal]']").val(taxInclude ? sum_dpp.toFixed(2) : sum_gross_subtotal.toFixed(2));
        $("input[name='Tran[disc]']").val(sum_itemdisc.toFixed(2));
        $("input[name='Tran[totalafterdisc]']").val(total_afterdisc.toFixed(2));
        $("input[name='Tran[ppnamount]']").val(sum_tax_ppn.toFixed(2));
        $("input[name='Tran[pphamount]']").val(sum_tax_pph.toFixed(2));
        $("input[name='Tran[grandtotal]']").val(grandtotal.toFixed(2));
    }

    function fetchProductPrice(row, trigger) {
        let tranid = $('.tranid').val() || '';
        let productid = row.find('.product-select').val() || '';
        if (!productid) return;

        let qty = parseFloat(row.find('.product-quantity').val().replace(/[^0-9.-]/g, '')) || 1;
        let term = parseFloat($("input[name='Tran[term]']").val().replace(/[^0-9.-]/g, '')) || 1;
        let freq = term;
        let trandate = $(document).find("input[name='Tran[trandate]']").val();
        let tranduedate = $(document).find("input[name='Tran[tranduedate]']").val();
        let withdrawaldate = $(document).find("input[name='Tran[withdrawaldate]']").val();
        let setupdate = $(document).find("input[name='Tran[setupdate]']").val();

        const positionId = parseInt("<?= Yii::$app->user->identity->positionid ?>");

        $.ajax({
            url: "<?= Url::to(['product/price']) ?>",
            type: "GET",
            dataType: 'json',
            data: {
                productid: productid,
                qty: qty,
                freq: freq,
                term: term,
                trigger: trigger,
                trandate: trandate,
                tranduedate: tranduedate,
                withdrawaldate: withdrawaldate,
                setupdate: setupdate,
                tranid: tranid
            },
            success: function (response) {
                if (response.success) {
                    let stockReady = parseFloat(response.data.stock);
                    let qtyInput = parseFloat(response.data.qty);

                    row.find('.product-quantity').val(qtyInput);
                    row.find('.product-stock').val(stockReady);
                    row.find('.product-freq-value').val(response.data.freq);

                    let sisaStok = stockReady - qtyInput;
                    row.find('.product-stock2').val(sisaStok);

                    let stockInfo = row.find('.info-stock');
                    if (qtyInput > stockReady) {
                        stockInfo.text(`Stock Kurang ${Math.abs(sisaStok)}`);
                        stockInfo.removeClass('d-none');
                    } else {
                        stockInfo.text('').addClass('d-none');
                    }

                    if (positionId !== 'position.wm' && positionId !== 'position.mo') {
                        let currentPrice = parseFloat(row.find('.product-price').val().replace(/[^0-9.-]/g, '')) || 0;

                        if (currentPrice === 0) {
                            let price = response.data.price !== null && response.data.price !== undefined ? response.data.price : 0;
                            row.find('.product-price').val(price);
                        }
                    }
                    setTotal();
                }
            },
            error: function () {
                console.warn('Gagal ambil harga produk');
            }
        });
    }

    function nextDay(currdate, days) {
        var currdate = convertDate(currdate);
        var result = new Date(currdate);
        result.setDate(result.getDate() + Number(days))
        return DatetoString(result);
    }

    function syncFreqWithTerm() {
        let termVal = parseFloat($("input[name='Tran[term]']").val()) || 0;

        $('.detail-item').each(function () {
            let row = $(this);
            let freqInput = row.find('.product-freq-value');

            if (termVal > 0) {
                freqInput.val(termVal);
            }
        });

        if (typeof setTotal === 'function') {
            setTotal();
        }
    }

    <?php if ($model->trantype == "sales/order") { ?>
        function generateEvent() {
            let eventType = $(document).find("select[name='Tran[eventtype]']").val();
            if (eventType === '3' || eventType === '4') return;

            let day = $(document).find("input[name='Tran[term]']").val();
            let trandate = $(document).find("input[name='Tran[trandate]']").val();
            let end = $(document).find("input[name='Tran[tranduedate]']").val();
            let setupdate = $(document).find("input[name='Tran[setupdate]']").val();
            let withdrawaldate = $(document).find("input[name='Tran[withdrawaldate]']").val();
            let tranId = $('#tran-id').val();

            $('.event-item').remove();

            Swal.fire({
                title: 'Loading...',
                text: 'Generating Setup, Full Event, and Bongkar...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            let assignedByDate = {};

            async function processRow(i) {
                $('#tranevent_repeater .repeat-parent-btn').first().click();

                let type, currtime, endtime, repeatCount;

                if (i === 0) {
                    type = 0;
                    repeatCount = 4;
                    var setupVal = (setupdate && setupdate.trim() !== '')
                        ? setupdate.trim()
                        : addDays(trandate.split(' ')[0], -1);
                    if (setupVal.includes(' ')) {
                        currtime = setupVal;
                        endtime = setupVal.split(' ')[0] + " 23:59";
                    } else {
                        currtime = setupVal + " 00:00";
                        endtime = setupVal + " 23:59";
                    }
                } else if (i === 1) {
                    type = 1;
                    repeatCount = 3;
                    let eventStartVal = trandate ? trandate.trim() : '';
                    let eventEndVal = end ? end.trim() : '';
                    currtime = eventStartVal.includes(' ') ? eventStartVal : (eventStartVal.split(' ')[0] + " 00:00");
                    endtime = eventEndVal.includes(' ') ? eventEndVal : (eventEndVal.split(' ')[0] + " 23:59");
                } else {
                    type = 2;
                    repeatCount = 4;
                    let withdrawalVal = (withdrawaldate && withdrawaldate.trim() !== '') ? withdrawaldate.trim() : end.trim();
                    if (withdrawalVal.includes(' ')) {
                        currtime = withdrawalVal;
                        endtime = withdrawalVal.split(' ')[0] + " 23:59";
                    } else {
                        currtime = withdrawalVal.split(' ')[0] + " 00:00";
                        endtime = withdrawalVal.split(' ')[0] + " 23:59";
                    }
                }

                $(document).find("select[name='Tranevent[" + i + "][eventtypeid]']").val(type);
                $(document).find("input[name='Tranevent[" + i + "][startdate]']").val(currtime);
                $(document).find("input[name='Tranevent[" + i + "][enddate]']").val(endtime);

                let $parent = $('#tranevent_repeater .event-item').last();
                let $inner = $parent.find('.inner-repeater');
                $inner.find('[data-repeater-item]').remove();
                for (let j = 0; j < repeatCount; j++) {
                    $inner.find('[data-repeater-create]').trigger('click');
                }

                let pureDate = currtime.split(' ')[0];

                let exclude = assignedByDate[pureDate] ? assignedByDate[pureDate] : [];

                let response = await $.ajax({
                    url: "<?= Url::to(['tran/crew']) ?>",
                    type: "GET",
                    dataType: 'json',
                    data: {
                        startDate: currtime,
                        endDate: endtime,
                        exclude: exclude.join(','),
                        tranId: tranId
                    },
                });

                if (!response.success) return;

                let picked = [];
                let d = response.data;

                $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][0][crewtypeid]']").val("pi");
                let leader = $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][0][crewid]']");
                if (d.pic && d.pic.contact_id) {
                    leader.html(`<option value="${d.pic.contact_id}">${d.pic.contact_name}</option>`);
                    leader.val(d.pic.contact_id).trigger('change');
                    picked.push(d.pic.contact_id);
                }

                $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][1][crewtypeid]']").val("op");
                let op = $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][1][crewid]']");
                if (d.operator && d.operator.contact_id) {
                    op.html(`<option value="${d.operator.contact_id}">${d.operator.contact_name}</option>`);
                    op.val(d.operator.contact_id).trigger('change');
                    picked.push(d.operator.contact_id);
                }

                if (type === 0 || type === 2) {
                    $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][2][crewtypeid]']").val("cr");
                    let crew1Select = $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][2][crewid]']");
                    if (d.crew1 && d.crew1.contact_id) {
                        crew1Select.html(`<option value="${d.crew1.contact_id}">${d.crew1.contact_name}</option>`).val(d.crew1.contact_id).trigger('change');
                        picked.push(d.crew1.contact_id);
                    }

                    $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][3][crewtypeid]']").val("cr");
                    let crew2Select = $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][3][crewid]']");
                    if (d.crew2 && d.crew2.contact_id) {
                        crew2Select.html(`<option value="${d.crew2.contact_id}">${d.crew2.contact_name}</option>`).val(d.crew2.contact_id).trigger('change');
                        picked.push(d.crew2.contact_id);
                    }
                } else {
                    $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][2][crewtypeid]']").val("sb");
                    let sbSelect = $(document).find("select[name='Tranevent[" + i + "][Traneventcrew][2][crewid]']");
                    if (d.standby && d.standby.contact_id) {
                        sbSelect.html(`<option value="${d.standby.contact_id}">${d.standby.contact_name}</option>`).val(d.standby.contact_id).trigger('change');
                        picked.push(d.standby.contact_id);
                    }
                }

                assignedByDate[pureDate] = (assignedByDate[pureDate] || []).concat(picked);
            }

            (async function run() {
                try {
                    for (let i = 0; i < 3; i++) {
                        await processRow(i);
                    }
                    Swal.close();
                    Swal.fire({ title: 'Berhasil', text: 'Generate Crew berhasil', icon: 'success', timer: 1500, showConfirmButton: false });
                } catch (err) {
                    Swal.close();
                    Swal.fire('Error', 'Gagal mengambil data crew', 'error');
                }
            })();
        }

        function loadReferenceDetail(tranid) {
            $('#trandetail_repeater [data-repeater-item]').remove();
            $('.detail-item').remove();

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: "<?= Url::to(['tran/listdetail']) ?>",
                type: "GET",
                dataType: 'json',
                data: {
                    id: tranid,
                    select: 'tab'
                },
                success: function (response) {
                    Swal.close();

                    if (response.data.length > 0) {
                        let headerData = response.data[0];
                        let isIncludeTax = parseInt(headerData.priceincludetax);

                        $("input[name='Tran[priceincludetax]']").val(isIncludeTax);
                        $('#kt_flexSwitchCustomDefault_1_1').prop('checked', isIncludeTax === 1);
                    }

                    response.data.forEach(function (item, index) {
                        $('#trandetail_repeater [data-repeater-create]').first().click();

                        let $row = $('#trandetail_repeater [data-repeater-item]').last();

                        let $product = $row.find("select[name*='[productid]']");
                        $product.html(`<option value="${item.productid}" selected>${item.productname}</option>`);
                        $product.val(item.productid).trigger('change.select2');

                        $row.find("input[name*='[amount]']").val(item.amount);
                        $row.find("input[name*='[price]']").val(item.price);
                        $row.find("textarea[name*='[description]']").val(item.description);
                        $row.find("input[name*='[itemsubtotaltax]']").val(item.itemsubtotaltax);
                        $row.find("input[name*='[itemdiscpersen]']").val(item.itemdiscpersen);

                        let $taxSelect = $row.find("select[name*='[itemtaxid]']");
                        $taxSelect.val(item.itemtaxid).trigger('change');

                        fetchProductPrice($row, 'qty');
                    });
                    setTotal();
                }
            });
        }

    <?php } ?>

    $(document).on('input change', "input[name='Tran[term]']", function (e) {
        setTerm();
        syncFreqWithTerm();
    });

    $(document).on('change', "input[name='Tran[picradio]']", function () {
        let $select = $(this).closest('form').find("select[name='Tran[contact_id]']");
        generateContact($select);
    });

    $(document).on('change', "input[name='Tran[trandate]']", function (e) {
        setTerm();
    });

    $(document).on('change', "input[name='Tran[tranduedate]']", function (e) {
        setTermDate();

        var currentDueDate = $(this).val();
        if (currentDueDate) {
            $(document).find("input[name='Tran[withdrawaldate]']").val(currentDueDate + ' 00:00');
        }
    });

    $(document).on('input change', '.product-price, .itemdiscpersen, .product-freq-value, .product-quantity, .itemtaxid', function (e) {
        setTotal();
    });

    $(document).on('change', '#kt_flexSwitchCustomDefault_1_1', function () {
        setTotal();

        if (!this.checked) {
            $("input[name='Tran[priceincludetax]']").val('0');
        } else {
            $("input[name='Tran[priceincludetax]']").val('1');

        }
    });

    $(document).on('input change', '.product-quantity', function (e) {
        let $input = $(this);
        let row = $(this).closest('tr');

        clearTimeout($input.data('timer'));
        $input.data('timer', setTimeout(function () {
            if ($input.hasClass('product-quantity')) {
                fetchProductPrice(row, 'quantity');
            } else {
                setTotal();
            }
        }, 500));
    });

    $(document).on('select2:select', '.job-select', function (e) {
        const row = $(this).closest('tr[data-repeater-item]');
        row.find('.fee').val(e.params.data.amount ?? 0);
    });

    $(document).on('select2:select', '.dinas-select', function (e) {
        const row = $(this).closest('tr[data-repeater-item]');
        row.find('.dinasfee').val(e.params.data.amount ?? 0);
    });

    $(document).on('select2:clear', '.job-select', function (e) {
        const row = $(this).closest('tr[data-repeater-item]');
        row.find('.fee').val(0);
    });

    $(document).on('select2:clear', '.dinas-select', function (e) {
        const row = $(this).closest('tr[data-repeater-item]');
        row.find('.dinasfee').val(0);
    });

    function initMap() {
        console.log("initMap dipanggil oleh Metronic, tapi sudah diganti pakai Leaflet");
    }

    $('#open-map-modal').on('click', function () {
        $('#map-modal').modal('show');
        setTimeout(initMap, 500);
    });

    function initializeLeafletMap() {
        const defaultLocation = [-6.2088, 106.8456]; // Default location (Jakarta, Indonesia)
        leafletMap = L.map('map').setView(defaultLocation, 13);  // Buat map baru

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(leafletMap);

        leafletMap.on('click', function (e) {
            const {
                lat,
                lng
            } = e.latlng;

            updateMarker(lat, lng);
            $('#selected-coordinate').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
            $('#search-location').val('Mencari lokasi...');
            reverseGeocode(lat, lng);
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    leafletMap.setView([lat, lng], 15);
                    updateMarker(lat, lng);

                    $('#selected-coordinate').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                    $('#search-location').val('Mendapatkan lokasi GPS...');
                    reverseGeocode(lat, lng);
                },
                function (error) {
                    console.warn("Akses lokasi ditolak atau error:", error);
                }
            );
        }
    }

    function updateMarker(lat, lng) {
        if (marker) {
            leafletMap.removeLayer(marker);
        }

        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(leafletMap);

        marker.bindPopup(`
            <div style="min-width: 200px;">
                <strong>📍 Lokasi Terpilih</strong><br>
                <small class="text-muted">Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</small>
                <br><small><i>Marker bisa di-drag untuk adjust posisi</i></small>
            </div>
        `);

        marker.on('dragend', function (event) {
            const position = marker.getLatLng();
            const newLat = position.lat;
            const newLng = position.lng;

            $('#selected-coordinate').text(`${newLat.toFixed(6)}, ${newLng.toFixed(6)}`);

            $('#search-location').val('Mencari lokasi...');

            marker.setPopupContent(`
            <div style="min-width: 200px;">
                <strong>📍 Lokasi Terpilih</strong><br>
                <small class="text-muted">Lat: ${newLat.toFixed(6)}<br>Lng: ${newLng.toFixed(6)}</small>
                <br><small><i>Marker bisa di-drag untuk adjust posisi</i></small>
            </div>
        `).openPopup();

            reverseGeocode(newLat, newLng);
        });
    }

    function reverseGeocode(lat, lng) {
        $.ajax({
            url: 'https://nominatim.openstreetmap.org/reverse',
            method: 'GET',
            dataType: 'json',
            data: {
                lat: lat,
                lon: lng,
                format: 'json',
                'accept-language': 'id',
                'countrycodes': 'id'
            },
            success: function (data) {
                if (data && data.display_name) {
                    $('#transeventform-locations').val(data.display_name);

                    $('#search-location').val(data.display_name);
                }
            },
            error: function () {
                console.warn('Gagal mendapatkan nama lokasi');
            }
        });
    }

    function searchLocation(query) {
        if (query.length < 3) {
            $('#suggestions-list').empty().hide();
            return;
        }

        $('#suggestions-list').html('<li class="list-group-item">Mencari lokasi...</li>').show();

        $.ajax({
            url: 'https://photon.komoot.io/api/',
            method: 'GET',
            dataType: 'json',
            data: {
                q: query,
                limit: 15,
                lang: 'default',
                lat: -6.2088,
                lon: 106.8456,
                bbox: '95.0,-11.0,141.0,6.0'
            },
            success: function (data) {
                $('#suggestions-list').empty();

                if (!data.features || data.features.length === 0) {
                    $('#suggestions-list').html('<li class="list-group-item text-muted">Tidak ada hasil untuk "' + query + '"</li>').show();
                    return;
                }

                let hashadesil = false;

                data.features.forEach(function (feature) {
                    const props = feature.properties;
                    const coords = feature.geometry.coordinates; // [lng, lat]

                    if (props.countrycode && props.countrycode.toUpperCase() === 'ID') {
                        hashadesil = true;

                        const mainName = props.name || '';
                        const city = props.city || props.town || props.state || '';
                        const country = props.country || '';
                        const fullAddress = [mainName, city, country].filter(Boolean).join(', ');

                        const item = $('<li>')
                            .addClass('list-group-item list-group-item-action')
                            .css('cursor', 'pointer')
                            .html(`<strong>${mainName}</strong><br><small class="text-muted">${fullAddress}</small>`)
                            .on('click', function () {
                                const lat = parseFloat(coords[1]);
                                const lng = parseFloat(coords[0]);

                                leafletMap.setView([lat, lng], 15);
                                updateMarker(lat, lng);
                                $('#selected-coordinate').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                                $('#transeventform-locations').val(fullAddress);
                                $('#search-location').val(fullAddress);
                                $('#suggestions-list').empty().hide();
                            });

                        $('#suggestions-list').append(item);
                    }
                });

                if (!hashadesil) {
                    $('#suggestions-list').html('<li class="list-group-item text-muted">Tidak ada hasil di Indonesia untuk "' + query + '"</li>').show();
                } else {
                    $('#suggestions-list').show();
                }
            },
            error: function () {
                $('#suggestions-list').html('<li class="list-group-item text-danger">Terjadi kesalahan saat mencari</li>').show();
            }
        });
    }

    function loadExistingCoordinate() {
        const currentCoord = $('.coordinate-input').val();
        const currentLocation = $('#transeventform-locations').val();

        if (currentCoord && currentCoord.includes(',')) {
            const [lat, lng] = currentCoord.split(',').map(s => parseFloat(s.trim()));

            if (!isNaN(lat) && !isNaN(lng)) {
                leafletMap.setView([lat, lng], 15);

                updateMarker(lat, lng);
                $('#selected-coordinate').text(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                if (currentLocation) {
                    $('#search-location').val(currentLocation);
                }
                if (marker && currentLocation) {
                    marker.bindPopup(`
                    <strong>Lokasi Tersimpan</strong><br>
                    ${currentLocation}<br>
                    <small class="text-muted">${lat.toFixed(6)}, ${lng.toFixed(6)}</small>
                `).openPopup();
                }
            }
        }
    }

    $('#map-modal').on('shown.bs.modal', function () {
        if (!leafletMap) {
            initializeLeafletMap();

            setTimeout(function () {
                loadExistingCoordinate();
            }, 500);
        } else {
            setTimeout(function () {
                leafletMap.invalidateSize();

                loadExistingCoordinate();
            }, 100);
        }
    });

    $('#search-location').on('input', function () {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();

        if (query.length < 3) {
            $('#suggestions-list').empty().hide();
            return;
        }

        searchTimeout = setTimeout(function () {
            searchLocation(query);
        }, 1000);
    });

    $('#search-btn').on('click', function () {
        const query = $('#search-location').val().trim();
        if (query.length >= 3) {
            clearTimeout(searchTimeout);
            searchLocation(query);
        } else {
            alert('Ketik minimal 3 huruf untuk mencari lokasi');
        }
    });

    $('#search-location').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#search-btn').click();
        }
    });

    $('#confirm-coordinate').on('click', function () {
        const coord = $('#selected-coordinate').text();

        if (coord.includes('Belum')) {
            alert('Pilih koordinat dulu ya! Klik di peta atau cari lokasi.');
            return;
        }

        $('.coordinate-input').val(coord);
        $('#map-modal').modal('hide');
    });

    $('#map-modal').on('hidden.bs.modal', function () {
        $('#search-location').val('');
        $('#suggestions-list').empty().hide();

        clearTimeout(searchTimeout);
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#search-location, #suggestions-list').length) {
            $('#suggestions-list').empty().hide();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            $('#suggestions-list').empty().hide();
        }
    });

</script>