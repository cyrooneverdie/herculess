<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="card">
    <div class="card-header">
        <h2 class="card-title fw-bold">Cek Stock</h2>
    </div>
    <div class="card-body">
        <div class="w-100">
            <?php
            $form = ActiveForm::begin([
                'id' => 'form-stock',
                'action' => Url::to(['product/cekstockproduct']),
                'method' => 'GET',
                'validateOnSubmit' => true,
            ]);

            $this->title = Yii::$app->lang->t('extrasidebar', 'extrasidebar129');
            ?>
            <div class="row">
                <div class="mb-3 col-md-4">
                    <label class="fw-semibold fs-6 mb-2">Inventory List</label>
                    <?= Html::dropDownList('categoryid', null, [], [
                        'id' => 'category',
                        'class' => 'form-select',
                        'data-control' => 'select2',
                        'prompt' => 'Inventory List'
                    ]) ?>
                </div>
                <div class="mb-3 col-md-8">
                    <label class="fw-semibold fs-6 mb-2">Inventory</label>
                    <input class="form-control d-flex align-items-center" name="products" value=""
                        placeholder="Pilih Product" id="kt_tagify_products" />
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-4">
                    <label class="fw-semibold fs-6 mb-2" for="dateFilter">Rentang Tanggal</label>

                    <input class="form-control" name="date" placeholder="<?= Yii::$app->lang->t('extra', 'extra58') ?>"
                        id="kt_daterangepicker_1" />
                </div>
            </div>

            <div class="row">
                <div class="mt-8 col-md-8 text-start">
                    <?= Html::submitButton('Cek Stock', ['class' => 'btn btn-sm btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <hr>

        <div class="w-100 mt-6">
            <div id="stock-body"></div>
        </div>
    </div>

</div>

<div class="modal fade" id="modal_form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check Stock <span id="modal-title-date"></span> <span id="modal-productname"
                        class="text-success"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content" class="nopadding">
                    <!-- Transaction form content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        setForm();
        $("#kt_daterangepicker_1").daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        function initEnumSelect2(selector, enumtype, addNewText) {
            $(selector).select2({
                placeholder: 'Select ' + addNewText,
                allowClear: true,
                ajax: {
                    url: "<?= Url::to(['enum/list']) ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            enumtype: enumtype,
                            search: params.term || '',
                            page: params.page || 1,
                            for: 'select2'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(item => ({ id: item.enumid, text: item.enumtext_id })),
                            pagination: { more: data.pagination.more }
                        };
                    }
                },
                width: '100%'
            });
        }

        initEnumSelect2('#category', 'category', 'Category');

        var inputElm = document.querySelector('#kt_tagify_products');

        function tagTemplate(tagData) {
            return `
            <tag title="${tagData.value}"
                contenteditable="false"
                spellcheck="false"
                tabIndex="-1"
                class="${this.settings.classNames.tag}"
                ${this.getAttributes(tagData)}>

                <x class="tagify__tag__removeBtn"></x>
                <div class="d-flex align-items-center">
                    <span class="tagify__tag-text">${tagData.productname}</span>
                </div>
            </tag>
        `;
        }

        function suggestionItemTemplate(tagData) {
            let img = tagData.productpict ?
                `/uploads/produk/${tagData.productpict}` :
                `/uploads/produk/default.png`;

            return `
            <div ${this.getAttributes(tagData)}
                class="tagify__dropdown__item d-flex align-items-center"
                tabindex="0">

                <img class="w-50px me-2"
                            src="${img}"
                            onerror="this.style.display='none'">

                <div class="d-flex flex-column">
                    <strong>${tagData.productname}</strong>
                    <span class="text-muted">${tagData.productcode}</span>
                </div>
            </div>
        `;
        }

        var tagify = new Tagify(inputElm, {
            tagTextProp: 'productname',
            enforceWhitelist: true,
            skipInvalid: true,
            dropdown: {
                closeOnSelect: false,
                enabled: 0,
                classname: 'users-list',
                searchKeys: ['productname', 'productcode'],
                maxItems: Infinity
            },
            templates: {
                tag: tagTemplate,
                dropdownItem: suggestionItemTemplate
            },
            whitelist: []
        });

        let tagifyPage = 1;
        let tagifyLimit = 10;
        let tagifyKeyword = '';
        let tagifyLoading = false;
        let tagifyHasMore = true;

        tagify.on('input focus', function (e) {
            let value = e.detail.value || '';
            tagifyPage = 1;
            fetchTagifyData(value, false);
        });

        tagify.on('input', function (e) {
            tagifyKeyword = e.detail.value.trim();

            if (tagifyKeyword.length < 2) return;

            tagifyPage = 1;
            tagifyHasMore = true;

            tagify.settings.whitelist = [];
            tagify.DOM.dropdown.content.innerHTML = '';

            fetchTagifyData(false);
        });

        tagify.on('dropdown:scroll', function () {
            if (tagifyLoading || !tagifyHasMore) return;

            tagifyPage++;

            fetchTagifyData(true); // append
        });

        function fetchTagifyData(append = false) {
            if (tagifyLoading) return;
            tagifyLoading = true;
            tagify.loading(true);

            let selectedCategory = $('#category').val();
            let offset = (tagifyPage - 1) * tagifyLimit;
            let inputVal = $('#kt_tagify_products').val();
            let parsed = JSON.parse(inputVal || '[]');
            let notin = parsed.map(item => item.value);

            $.ajax({
                url: "<?= Yii::$app->urlManager->createUrl(['product/searchstock']) ?>",
                type: "GET",
                dataType: "json",
                data: {
                    q: tagifyKeyword,
                    categoryid: selectedCategory,
                    limit: tagifyLimit,
                    offset: offset,
                    notin: notin ?? [],
                },
                success: function (res) {
                    let rows = res.data || [];

                    if (rows.length < tagifyLimit) {
                        tagifyHasMore = false;
                    }

                    if (append) {
                        tagify.settings.whitelist = tagify.settings.whitelist.concat(rows);
                    } else {
                        tagify.settings.whitelist = rows;
                    }

                    if (tagify.DOM.scope && document.body.contains(tagify.DOM.scope)) {
                        tagify.dropdown.refilter();
                        setTimeout(() => tagify.dropdown.show(tagifyKeyword), 50);
                    }

                    tagifyLoading = false;
                    tagify.loading(false);
                },

                error: function () {
                    tagify.loading(false);
                    tagifyLoading = false;
                }
            });
        }

        // $('#category').on('change', function () {
        //     tagify.removeAllTags();
        //     tagify.settings.whitelist = [];
        // });

        function setForm() {
            $('#form-stock').on('submit', function (e) {
                e.preventDefault();

                $('#btnsubmit').prop('disabled', true);
                let inputVal = $('#kt_tagify_products').val();
                let parsed = JSON.parse(inputVal || '[]');
                let productsid = parsed.map(item => item.value);
                let date = $('#kt_daterangepicker_1').val();

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: {
                        products: productsid,
                        date: date
                    },
                    success: function (res) {
                        $('#btnsubmit').prop('disabled', false);
                        const data = res && res.data ? res.data : [];

                        if (!data.length) {
                            $('#stock-body').html('<div class="col-12 text-center py-5">Data tidak ditemukan</div>');
                            return;
                        }

                        let finalHtml = '<div class="row g-4 flex-nowrap overflow-auto pb-4" style="max-width: 100%;">';

                        data.forEach(product => {
                            const dates = Object.keys(product.stock || {});

                            const adaBarangKeluar = dates.some(d => (product.stock[d].out || 0) > 0);
                            let tanggalKembali = product.returnDate;
                            if (!tanggalKembali) {
                                const tanggalDitemukan = dates.find(d => product.stock[d].returnDate);
                                if (tanggalDitemukan) {
                                    tanggalKembali = product.stock[tanggalDitemukan].returnDate;
                                }
                            }
                            let returnInfo = '';
                            if (adaBarangKeluar && tanggalKembali) {
                                returnInfo = `<div class="text-muted fs-8 mt-6">Estimasi Kembali: ${tanggalKembali}</div>`;
                            }

                            finalHtml += `
                            <div class="col-12 col-md-6 col-xl-4 flex-shrink-0" style="min-width: 320px;">
                                <div class="card card-bordered shadow-sm h-100">
                                    <div class="card-header bg-light py-3">
                                        <h4 class="card-title fw-bold text-uppercase fs-6 mb-0 text-truncate">
                                            ${product.productname}
                                        </h4>
                                        ${returnInfo}
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless align-middle text-center mb-0" style="font-size: 0.85rem;">
                                                <thead>
                                                    <tr class="border-bottom">
                                                        <th class="text-start fw-bold pe-2" style="min-width: 110px;">Metrics</th>
                                                        ${dates.map(d => {
                                const label = new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                                return `<th class="text-center px-1" style="min-width: 45px;">${label}</th>`;
                            }).join('')}
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td class="text-start fw-semibold text-gray-700 py-1">Stok</td>
                                                        ${dates.map(d => {
                                let readyVal = product.stock[d].ready || 0;
                                return `<td class="fw-bold text-success py-1">${readyVal}</td>`;
                            }).join('')}
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start fw-semibold text-gray-700 py-1">Barang tersedia</td>
                                                        ${dates.map(d => {
                                let tersediaVal = product.stock[d].barangTersedia || 0;
                                return `<td class="fw-bold text-dark py-1">${tersediaVal}</td>`;
                            }).join('')}
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start fw-semibold text-gray-700 py-1">Booked</td>
                                                        ${dates.map(d => {
                                let leadVal = product.stock[d].lead || 0;
                                return `
                                                            <td class="py-1">
                                                                <a href="javascript:void(0)" 
                                                                class="badge bg-primary text-white fw-bold px-2 py-1 text-decoration-none"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modal_form" 
                                                                data-product="${product.productid}" 
                                                                data-name="${product.productname}" 
                                                                data-type="lead" 
                                                                data-date="${d}">
                                                                ${leadVal}
                                                                </a>
                                                            </td>`;
                            }).join('')}
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start fw-semibold text-gray-700 py-1">Confirmed</td>
                                                        ${dates.map(d => {
                                let val = product.stock[d].deal || 0;
                                return `
                                                            <td class="py-1">
                                                                <a href="javascript:void(0)" class="badge bg-success text-white fw-bold px-2 py-1 text-decoration-none"
                                                                data-bs-toggle="modal" data-bs-target="#modal_form" 
                                                                data-product="${product.productid}" data-name="${product.productname}" 
                                                                data-type="deal"data-date="${d}">
                                                                ${val}
                                                                </a>
                                                            </td>`;
                            }).join('')}
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start fw-semibold text-gray-700 py-1">Stock out</td>
                                                        ${dates.map(d => {
                                let outinVal = product.stock[d].outin || 0;
                                return `
                                                            <td class="fw-bold text-info py-1">
                                                                ${outinVal}
                                                            </td>`;
                            }).join('')}
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });

                        finalHtml += '</div>';

                        $('#stock-head').html('');
                        $('#stock-body').html(finalHtml);
                    },
                    error: function (e) {
                        $('#btnsubmit').prop('disabled', false);
                    }
                });
            });
        }

        $('#modal_form').on('show.bs.modal', function (event) {
            var dateRange = $('#kt_daterangepicker_1').val();
            var button = $(event.relatedTarget);
            $(this).find('#modal-title-date').text(dateRange);

            var productId = button.data('product');
            var productName = button.data('name') || 'Product';
            $(this).find('#modal-productname').text(productName);
            var type = button.data('type');

            $.ajax({
                url: "<?= Url::to(['product/info']) ?>",
                type: "GET",
                data: {
                    id: productId,
                    date: dateRange,
                    type: type
                },
                success: function (data) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    $('#modal-content').html(doc.body.innerHTML);
                    $('#modal_form').modal('show');
                },
                error: function () {
                    $('#modal-content').html('<p>Error loading form.</p>');
                }
            });
        });
    });
</script>