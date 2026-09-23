<?php 
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<style>
    #datatable th,
	#datatable td {
		white-space: nowrap;
		min-width: 150px;
		/* Atur minimum lebar */
	}

	/* Atur agar kolom pertama tidak ikut min-width */
	#datatable th:first-child,
	#datatable td:first-child {
		min-width: auto !important;
		width: 70px !important;
		/* Sesuaikan lebar */
	}
</style>
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-2">
                <form method="get" action="index" id="search" class="w-100">
                    <div class="position-relative">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="#a1a5b7">
								<path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
							</svg>
                        </span>
                        <input
                            data-kt-docs-table-filter="search"
							type="text"
							name="search"
							class="form-control form-control-solid w-250px ps-10"
                            placeholder="<?= Yii::$app->lang->t('home', '#') ?>">

                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
                            <i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="left-start">
				<i class="ki-duotone ki-filter fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter
			</button>

            <div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true" data-kt-menu-id="filter-menu">
                <div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
				</div>

                <div class="separator border-gray-200"></div>

                <div class="px-7 py-5" data-kt-user-table-filter="form">
                    <form id="filterForm" method="get" action="index">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <label class="fw-semibold fs-6 mb-2" for="categoryFilter">Kategori</label>
                                <select id="categorySelect2" class="form-select category" data-control="select2" name="category">
                                    <option selected><?php $model->category['text'] ?? ''; ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group text-end mt-5">
                            <button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i class="fa-sharp fa-solid famodal-filter"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <button type="button" class="btn btn-primary" id="btn-add-coas" data-bs-toggle="" data-bs-target="#modal_form_coas">
                <i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('home', '#') ?>
            </button>
        </div>
    </div>

    <div class="card-body pt-5">
    <div id="liveAlertPlaceholder"></div>
        <div class="btn-group mb-3" id="mass-action-buttons" style="display: none;">
            <button type="button" class="btn btn-danger" id="btn-delete-mass">
                <i class="fas fa-trash me-2"></i>Hapus
            </button>
        </div>

        <table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
			<thead>
				<tr class="text-start bg-gray-100  fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="d-none"></th>
					<th class="text-center">
						<input type="checkbox" id="select-all">
					</th>
					<th class="text-center min-w-50px">Action</th>
					<th class="text-center min-w-50px">Kode</th>
					<th class="text-center min-w-200px">Nama</th>
					<th class="text-center min-w-100px">Kategori</th>
                    <!-- <th class="text-center min-w-50px">Saldo</th> -->
				</tr>
			</thead>
			<tbody class="text-gray-600 fw-semibold text-center">
				<!-- Table content will be loaded dynamically -->
			</tbody>
		</table>
    </div>
</div>
<div class="modal fade" id="modal_form_coas" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen-md-down modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Akun</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="modal-content" class="nopadding">
					<!-- Product form content will be loaded here -->
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$.fn.dataTable.ext.errMode = "none"; 
    
	function toggleMassActionButtons() {
		if ($('.select-checkbox:checked').length > 0) {
			$('#mass-action-buttons').show();
		} else {
			$('#mass-action-buttons').hide();
		}
	}

	$("#select-all").on("click", function() {
        $("tbody .select-checkbox").prop("checked", this.checked);
        toggleMassActionButtons();
    });

    $("#datatable tbody").on("change", ".select-checkbox", function() {
        $("#select-all").prop(
            "checked",
            $(".select-checkbox").length === $(".select-checkbox:checked").length
        );
        toggleMassActionButtons();
    });

	function performMassAction(action, statusCode) {
		console.log($("tbody .select-checkbox:checked"));
		let selectedIds = $(".select-checkbox:checked")
			.map(function() {
				return $(this).val(); 
			})
			.get();

			console.log(selectedIds);
		if (selectedIds.length === 0) {
			Swal.fire({
				title: "Tidak Ada Data Terpilih!",
                text: "Pilih minimal satu data untuk diproses.",
                icon: "warning",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
			});
			return;
		}

		let actionText, actionColor, actionIcon, confirmText;

		if (action === 'delete') {
			actionText = "hapus";
			actionColor = "#d33";
			actionIcon = "warning";
			confirmText = "Ya, hapus!";
		}

		Swal.fire({
			title: `Yakin ingin ${actionText}?`,
            text: `Anda akan ${actionText} ${selectedIds.length} data.`,
            icon: actionIcon,
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: "#6e7d88",
            confirmButtonText: confirmText,
            cancelButtonText: "Batal"
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/coas/massaction']) ?>',
                    type: "POST",
                    data: {
                        ids: selectedIds,
                        action: action,
                        status: statusCode,
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                    },
					headers: {
                        "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                    },
					success: function(response) {

						$('#datatable').DataTable().ajax.reload();

						$("#mass-action-buttons").hide();
                        $("#select-all").prop("checked", false);

						let successMessage, successText, successIcon;

						if (action === 'delete') {
							successTitle = "Terhapus!";
							successText = "Data berhasil dihapus.";
							successIcon = "success";
						}

						Swal.fire({
							title: successTitle,
                            text: successText,
                            icon: successIcon,
                            timer: 2000,
                            showConfirmButton: false
						});
					},
					error: function(xhr) {
						console.error("Error:", xhr.responseText);
					
						Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat memproses data.",
                            icon: "error",
                            confirmButtonColor: "#d33",	
                            confirmButtonText: "OK"
                        });
					}
				});
			}
		});
	}

	$('#btn-delete-mass').on('click', function() {
        performMassAction('delete', 10);
    });

	$("#filterForm").submit(function(e) {
        e.preventDefault(); // Jangan biarkan form reload halaman
        $("#datatable").DataTable().ajax.reload();
        // Gunakan Metronic API untuk menutup menu
        let filterMenu = document.querySelector("[data-kt-menu-id='filter-menu']");
        if (filterMenu) {
            KTMenu.getInstance(filterMenu).hide();
        }
    });

    $(document).ready(function() {
        $("#categorySelect2").select2({
			ajax: {
				url: "<?= \yii\helpers\Url::to(['coas/categorylist']) ?>",
				type: "GET",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						q: params.term, 
					};
				},
				processResults: function(data) {
					return {
						results: data.results
					};
				},
				cache: true
			},
			placeholder: "Pilih Kategori",
			allowClear: true
		});

        $("#datatable").DataTable({
            scrollX: true,
			autoWidth: false,
			processing: false,
			serverSide: false,
			lengthMenu: [5, 15, 25, 50],
			pageLength: 5,
            order: [],
            language: {
				emptyTable: `
                    <div style="text-align: center; padding: 20px 0;">
                    <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                        <div style="font-weight: bold; font-size: 16px; margin-top : 8px;"> No data available in table</div>
                    </div>
                `,
                        zeroRecords: `
                    <div style="text-align: center; padding: 20px 0;">
                    <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                        <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">No data available in table</div>
                    </div>
                `,
                        loadingRecords: `
                    <div style="text-align: center; padding: 20px 0;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div style="margin-top: 10px;">Memuat data...</div>
                    </div>
                `
			},
            initComplete: function() {
				// This helps avoid the flash of "No data" before content loads
				$('.dataTables_empty').closest('.dataTables_scroll').addClass('table-initialized');
			},
            ajax: {
				type: "GET",
				dataSrc: "data",
				url: "/coas/list",
				data: function(d) {
					console.log(d);
					d.category = $('select[name="category"]').val();
					d.search = $('input[name="search"]').val();
				},
				complete: function() {
					// Remove the loading overlay when data load completes
					$('.table-loading-overlay').remove();
				}
			},
            columns: [
				{
					data: 'id',
					visible: false,
				},
                {
					data: null,
					className: "text-center",
					orderable: false,
					render: function(data, type, row) {
						return `<input type="checkbox" class="select-checkbox" value="${row.id}">`;
					}
				},
                {
                data: null,
                render: function(data, type, row) 
                {
                    console.log(row);
                    return `
                        <div class="dropdown text-center dropend">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fa-sharp fa-solid fa-list"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-hover-success btn-light" id="edit-coas" data-bs-toggle="modal" data-bs-target="#modal_form_coas" data-id="${row.id}"style="cursor: pointer;"><i class="fas fa-edit"></i> Edit</a></li>
                                <li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-coas" data-id="${row.id}"><i class="fas fa-trash" style="cursor: pointer;"></i> Hapus</button></li>
                            </ul>
                        </div>`;
				}
                },
                {
                    data: "no",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: "coa_name",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: "coa_category_name",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                // {
                //     data: "#",
                //     render: function(data, type, row) {
                //         return data;
                //     }
                // },
            ],
        });

        $("#search").submit(function(e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

        $("#filterForm").submit(function(e) {
			e.preventDefault(); // Jangan biarkan form reload halaman
			$("#datatable").DataTable().ajax.reload();
		});

        
        $(document).on('click', '#edit-coas', function() {
			var id = $(this).data('id')
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['update']) ?>?id=' + id,
				success: function(data) {
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');
					$('#modal-content').html(doc.body.innerHTML);
					$('#modal_form_coas').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

        $('#btn-add-coas').on('click', function() {
			$.ajax({
				url: '<?= \yii\helpers\Url::to(['create']) ?>',
				success: function(data) {
					// Gunakan DOMParser untuk memparsing HTML
					let parser = new DOMParser();
					let doc = parser.parseFromString(data, 'text/html');

					// Masukkan konten yang telah dibersihkan ke modal tanpa mengubah struktur form
					$('#modal-content').html(doc.body.innerHTML);
					console.log("Select2: ", typeof $.fn.select2);
					$('#modal_form_coas').modal('show');
				},
				error: function() {
					$('#modal-content').html('<p>Error loading form.</p>');
				}
			});
		});

        $(document).on('click', 'delete-coas', function() {
            var coa_id = $(this).data('id');
            console.log('Menghapus ID:', coa_id);

            if (!coa_id) {
                Swal.fire({
					title: "Error!",
					text: "ID tidak ditemukan! Periksa tombol yang diklik.",
					icon: "error",
					confirmButtonColor: "#d33",
					confirmButtonText: "OK"
				});
				return;   
            }

            Swal.fire({
				title: "Yakin ingin hapus?",
				text: "Data yang dihapus tidak bisa dikembalikan!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: "Ya, hapus!",
				cancelButtonText: "Batal"
			}).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= Url::to(['/coas/delete']) ?>',
						type: 'post',
						data: {
							id: produkId,
							_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
						},
						headers: {
							"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
						},
                        success: function(response) {
							console.log('Response:', response);

							$('#datatable').DataTable().ajax.reload();

							Swal.fire({
								title: "Terhapus!",
								text: "Data berhasil dihapus.",
								icon: "success",
								timer: 2000,
								showConfirmButton: false
							});
						},
                        error: function(xhr) {
							console.error('Error:', xhr.responseText);

							Swal.fire({
								title: "Gagal!",
								text: "Terjadi kesalahan saat menghapus data.",
								icon: "error",
								confirmButtonColor: "#d33",
								confirmButtonText: "OK"
							});
						}
                    });
                }
            });
        });
    });
</script>
<style>
	#mass-action-buttons .btn {
		margin-right: 5px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0.5rem 1rem;
		border-radius: 0.475rem;
		transition: all 0.2s ease;
    }

    #mass-action-buttons .btn-danger {
        background-color: #f1416c;
        border-color: #f1416c;
    }

    #mass-action-buttons .btn-success {
        background-color: #50cd89;
        border-color: #50cd89;
    }

    #mass-action-buttons .btn-warning {
        background-color: #ffac1b;
        border-color: #ffac1b;
    }

    #mass-action-buttons .btn:hover {
        filter: brightness(90%);
    }
</style>