<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
?>

<div class="app-toolbar py-3 py-lg-6 px-0 mx-0 ms-n8">
	<div class="app-container container-fluid d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= Yii::$app->lang->t('back_home', 'chat27') ?></h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<!-- <li class="breadcrumb-item text-muted">
					<a href="<?= Url::to(['site/index']) ?>" class="text-muted text-hover-primary"><?= Yii::$app->lang->t('back_home', 'chat2') ?></a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('back_home', 'chat27') ?></li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<span class="breadcrumb-item text-muted"><?= Yii::$app->lang->t('back_home', 'chat26') ?></span>
				</li> -->
			</ul>
			<!-- ?php //echo Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) 
			?>
			?= Alert::widget() ?> -->
		</div>
	</div>
</div>
<div class="card mt-5">
	<div class="card-header border-0 pt-6">
		<div class="card-title">
			<div class="d-flex align-items-center position-relative my-1">
				<div class="card-title d-flex justify-content-end">
					<!--begin::Search-->
					<form method="get" style="width: 100%;" id="search">
						<div class="position-relative">
							<!-- Search icon properly positioned inside the form -->
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
								placeholder="<?= Yii::$app->lang->t('back_home', 'chat51') ?>" 
								autocomplete="off"/>

							<!-- Optional: Clear search button -->
							<span class="position-absolute top-50 end-0 translate-middle-y me-3 d-none" id="clear-search">
								<i class="ki-duotone ki-cross fs-2 text-gray-500 cursor-pointer" style="opacity: 0.5;"></i>
							</span>
						</div>
						<!-- <input
							type="text"
							name="search"
							value="?php
									if (isset($_GET['search'])) {
										echo Yii::$app->request->get('User')['search'] ??  $_GET['search'];
									} else {
										echo "";
									}
									?>"
							class="form-control form-control-solid w-250px ps-13"
							autocomplete="off"
							placeholder="?= Yii::$app->lang->t('back_home', 'chat51') ?>" /> -->
					</form>
					<!-- <i class="fa-solid fa-magnifying-glass position-absolute me-5"></i> -->
					<!--end::Search-->
				</div>
			</div>
		</div>
		<div class="card-toolbar">

			<!--begin::Filter-->
			<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="left-start" data-kt-menu-id="filter-menu">
				<i class="ki-duotone ki-filter fs-2">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter</button>
			    <!--begin::Add User-->

			<!-- Add User button -->
			<!-- <button type="button" id="btn-add-user" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_form_user">
    			Add User
			</button> -->
			

			<a href="<?= \yii\helpers\Url::to(['users/create']) ?>"
              id="btn-add-users"
              class="btn btn-primary btn-add-users"
              data-url="<?= \yii\helpers\Url::to(['users/create']) ?>">
               <i class="ki-duotone ki-plus fs-2"></i><?= Yii::$app->lang->t('add', 'add1') ?>
           </a>

			<!--begin::Menu 1-->
			<div class="menu menu-sub menu-sub-dropdown w-sm-500px w-md-600px" data-kt-menu="true" data-kt-menu-id="filter-menu">
				<!--begin::Header-->
				<div class="px-7 py-5">
					<div class="fs-5 text-gray-900 fw-bold"><?= Yii::$app->lang->t('extra', 'extra9') ?></div>
					<div class="col-md-12 col-lg-12 mt-5">
						<label class="fw-semibold fs-6 mb-2" for="tipeFilter"><?= Yii::$app->lang->t('extra', 'extra10') ?></label>
						<select id="select2" class="form-select role" data-control="select2" name="role" data-placeholder="<?= Yii::$app->lang->t('extra', 'extra8') ?>" data-allow-clear="true">
							<option></option>
							<option value="1">Owner</option>
							<option value="2">Staff</option>
							<option value="3">Admin</option>
							<option value="4">Account Executive (AE)</option>
							<option value="5">Manager Operational</option>
							<option value="6">Kepala Gudang</option>
							<option value="7">HRD</option>
							<option value="8">Finance</option>

						</select>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Separator-->
				<div class="separator border-gray-200"></div>
				<!--end::Separator-->
				<!--begin::Content-->
				<div class="px-7 py-5" data-kt-user-table-filter="form">
					<form id="filterForm" method="get" action="index">
						<!--begin::Input group-->
						<div class="row">
							<div class="col-md-4">

							</div>
						</div>
						<div class="form-group text-end mt-5">
							<button type="submit" id="filterButton" class="btn btn-lg btn-primary"><i class="fa-sharp fa-solid fa-filter"></i></button>
						</div>
						<!--end::Input group-->
					</form>
				</div>
				<!--end::Content-->
			</div>
		</div>
	</div>
	<div class="card-body">

		<table class="table align-middle table-row-dashed fs-6 gy-5" id="datatable">
			<thead>
				<tr class="text-start bg-gray-100  fs-6 text-gray-500 fw-bold fs-7 text-uppercase gs-0">
		
					<th class="text-center min-w-50px"><?= Yii::$app->lang->t('cashbackend', 'cashbackend1') ?></th>
					<th><?= Yii::$app->lang->t('back_home', 'chat27') ?></th>
					<th><?= Yii::$app->lang->t('extra', 'extra6') ?></th>
					<th><?= Yii::$app->lang->t('contact', 'contactjoin1') ?></th></th>
					<!-- <th class="min-w-125px">Created At</th> -->
					<th><?= Yii::$app->lang->t('back_home', 'chat28') ?></th>
					<th>Status</th>
					<!-- <th class="text-center min-w-80px">Status</th> -->
				</tr>
			</thead>
			<tbody>
				<!-- Table content will be loaded dynamically -->
			</tbody>
		</table>
	</div>
</div>

<!-- Modal User -->
<div class="modal fade" id="modal_form_user" tabindex="-1" aria-labelledby="modal-title-user" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
 <h5 class="modal-title" id="modal-title-user">
    <?= Yii::$app->lang->t('back_home', 'notif33') ?>
</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-content-user">
        <p class="text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>


<!--asd-->


<script>
	$(document).on('click', '#btn-add-users', function (e) {
    e.preventDefault();

		let url = $(this).data('url');

		$.ajax({
			url: url,
			type: 'GET',
			success: function (data) {
				let parser = new DOMParser();
				let doc = parser.parseFromString(data, 'text/html');
				$('#modal-content-user').html(doc.body.innerHTML);
				$('#modal-title-user').html("<?= Yii::$app->lang->t('back_home', 'user') ?>");
				$('#modal_form_user').modal('show');
			},
			error: function (xhr) {
				$('#modal-content-user').html('<p class="text-danger">Error loading form.</p>');
			}
		});
	});


	// Update fungsi hapusmassal untuk menangani kedua tombol
	
	let companyId = '<?= Yii::$app->session->get("companyid") ?>'; // Ambil dari session
	var translate = <?= json_encode(Yii::$app->lang->t('extra', 'extra11')) ?>;
	var translate1 = <?= json_encode(Yii::$app->lang->t('extra', 'extra12')) ?>;
	var translate2 = <?= json_encode(Yii::$app->lang->t('extra', 'extra13')) ?>;
	var translate3 = <?= json_encode(Yii::$app->lang->t('extra', 'extra76')) ?>;
	var translate4 = <?= json_encode(Yii::$app->lang->t('extra', 'extra74')) ?>;
	var translate5 = <?= json_encode(Yii::$app->lang->t('back_home_users', 'aksi1')) ?>;
	var translate6 = <?= json_encode(Yii::$app->lang->t('back_home_users', 'aksi2')) ?>;
	var translate7 = <?= json_encode(Yii::$app->lang->t('back_home_users', 'aksi3')) ?>;





	$("#datatable").DataTable({
		scrollX: true,
		autoWidth: false,
		processing: false,
		serverSide: false, // Jika ingin server-side, ubah ke true
		lengthMenu: [15, 30, 50, 75, 100],
		pageLength: 15, // Set default ke 5
		order: [], // Tidak ada kolom yang diurutkan saat load pertama

		language: {
			info: `${translate1}`,
			infoEmpty: `${translate2}`,
			emptyTable: companyId ? `
            <div style="text-align: center; padding: 20px 0;">
               <img width='250px' src='https://cdni.iconscout.com/illustration/premium/thumb/employee-is-unable-to-find-sensitive-data-illustration-download-in-svg-png-gif-file-formats--no-found-misplaced-files-business-pack-illustrations-8062128.png'/>
                <div style="font-weight: bold; font-size: 16px; margin-top : 8px;">${translate}</div>
            </div>
        ` : `
            <div style="text-align: center; padding: 20px 0;">
                <div style="font-weight: bold; font-size: 16px; margin-top : 8px;"> Please Make A Company First</div>
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
                <div style="margin-top: 10px;"><?= Yii::$app->lang->t('extra', 'extra94') ?></div>
            </div>
        `
		},
		select: {
			style: 'multi',
			selector: 'td:first-child input[type="checkbox"]',
			className: 'row-selected'
		},
		ajax: {
			type: "GET",
			dataSrc: "data", // Pastikan ini sesuai dengan response JSON
			url: "/users/list",
			data: function(d) {
				d.search = $('input[name="search"]').val();
				d.role = $('select[name="role"]').val();
			}
		}, // URL ke Yii2 API
		columns: [
			// {
			// 	data: null,
			// 	className: "text-center w-40px",
			// 	orderable: false,
		
			// },
			{
    			data: null,
   	 			render: function(data, type, row) {
        		const userid = row.userid || ''; // Fallback to empty string if undefined
        		const updateUrl = userid 
            	? '<?= \yii\helpers\Url::to(['users/update', 'id' => '__USERID__']) ?>'.replace('__USERID__', userid)
            	: '#'; // Use '#' if userid is missing
        		return `
           			 <div class="dropdown text-center dropend">
                		<button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                  		  <i class="fa-sharp fa-solid fa-list"></i>
               				 </button>
               				 <ul class="dropdown-menu px-2">
								<li>
									<button type="button"
										class="dropdown-item text-hover-success btn-update-user"
										data-id="${userid}" ${!userid ? 'disabled' : ''}>
										<i class="fas fa-edit" style="cursor: pointer;"></i> ${translate5}
									</button>
								</li>

                    		<li><button href="javascript:void(0);" class="dropdown-item text-hover-danger delete-contact" data-id="${userid}"><i class="fas fa-trash" style="cursor: pointer;"></i> ${translate6}</button></li>
							<li>
							<button href="javascript:void(0);" 
									class="dropdown-item text-hover-warning reset-password" 
									data-id="${userid}">
								<i class="fas fa-key" style="cursor: pointer;"></i> ${translate7}
							</button>
							</li>


                			</ul>
           	 				</div>`;
    			}
				},
			{
				data: null,
				render: function(data, type, row) {
					return `
					<div class="d-flex flex-row align-items-center px-0 py-0">
						<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
						<div class="symbol-label">
							<img src="/assets/media/svg/avatars/blank.svg" alt="Default Avatar" class="w-100" />
						</div>
						</div>

						<div class="d-flex flex-column justify-content-center">
							<a href="#" class="text-gray-800 text-hover-primary mb-0">${row.name}</a>
							<input type="hidden" class="user-email" value="${row.email}" />
						</div>
					</div>
					`;
				}



			},
			{
				data: "role",
			},
			{
				data: "contact_name",
				render: function(data, type, row) {
					return data ? `<span class="text-gray-800">${data}</span>` : ``;
				}
			},
			{
				data: "created_at",
				className: "text-center",
				// render: function(data, type, row) {
				// 	let types = [];

				// 	if (row.contact_isvendor == 1) {
				// 		types.push(`<span class="badge bg-primary fw-bold">Vendor</span>`);
				// 	}
				// 	if (row.contact_iscustomer == 1) {
				// 		types.push(`<span class="badge bg-success fw-bold">Customer</span>`);
				// 	}

				// 	return types.length > 0 ? types.join(" ") : "";
				// }
			},
			{
				data: 'status'
			}
		],
	});
	// Select All checkbox di thead
	// $("#select-all").on("click", function() {
	// 	const isChecked = this.checked;

	// 	$("tbody .select-checkbox").each(function() {
	// 		if (isChecked) {
	// 			$(this)
	// 				.prop("checked", true)
	// 				.addClass("checked-anim");

	// 			// Biar efek tetap smooth, delay penghapusan class sedikit lebih lama
	// 			setTimeout(() => {
	// 				$(this).removeClass("checked-anim");
	// 			}, 400);
	// 		} else {
	// 			$(this).prop("checked", false);
	// 		}
	// 	});

	// 	hapusmassal();
	// });

	$("#search").submit(function(e) {
		e.preventDefault(); // Jangan biarkan form reload halaman
		$("#datatable").DataTable().ajax.reload();
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

	// // Update checkbox Select All jika ada perubahan di checkbox per baris
	// $("#datatable tbody").on("change", ".select-checkbox", function() {
	// 	$("#select-all").prop(
	// 		"checked",
	// 		$(".select-checkbox").length === $(".select-checkbox:checked").length
	// 	);
	// 	hapusmassal();
	// });

	var deletemessage1 = "<?= Yii::$app->lang->t('extra', 'extra44') ?>";
	var deletemessage2 = "<?= Yii::$app->lang->t('extra', 'extra45') ?>";
	var deletemessage3 = "<?= Yii::$app->lang->t('extra', 'extra46') ?>";
	var deletemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'extra46.1') ?>";
	var deletemessage4 = "<?= Yii::$app->lang->t('back_home', 'chat34') ?>";
	var deletemessage5 = "<?= Yii::$app->lang->t('button', 'extra79') ?>";

	var notif1 = "<?= Yii::$app->lang->t('back_home', 'notif1') ?>";
	var notif2 = "<?= Yii::$app->lang->t('back_home', 'notif2') ?>";
	var notif3 = "<?= Yii::$app->lang->t('back_home', 'notif3') ?>";
	var notif4 = "<?= Yii::$app->lang->t('back_home', 'notif4') ?>";
	var notif5 = "<?= Yii::$app->lang->t('back_home', 'notif5') ?>";
	var notif6 = "<?= Yii::$app->lang->t('back_home', 'notif6') ?>";
	var notif7 = "<?= Yii::$app->lang->t('back_home', 'notif7') ?>";
	var notif8 = "<?= Yii::$app->lang->t('back_home', 'notif8') ?>";
	var notif9 = "<?= Yii::$app->lang->t('back_home', 'notif9') ?>";
	var notif10 = "<?= Yii::$app->lang->t('back_home', 'notif10') ?>";
	var notif11 = "<?= Yii::$app->lang->t('back_home', 'notif11') ?>";
	var notif12 = "<?= Yii::$app->lang->t('back_home', 'notif12') ?>";
	var notif13 = "<?= Yii::$app->lang->t('back_home', 'notif13') ?>";
	var notif14 = "<?= Yii::$app->lang->t('back_home', 'notif14') ?>";
	var notif15 = "<?= Yii::$app->lang->t('back_home', 'notif15') ?>";
	var notif16 = "<?= Yii::$app->lang->t('back_home', 'notif16') ?>";
	var notif17 = "<?= Yii::$app->lang->t('back_home', 'notif17') ?>";
	var notif18 = "<?= Yii::$app->lang->t('back_home', 'notif18') ?>";
	var notif19 = "<?= Yii::$app->lang->t('back_home', 'notif19') ?>";
	var notif20 = "<?= Yii::$app->lang->t('back_home', 'notif20') ?>";
	var notif21 = "<?= Yii::$app->lang->t('back_home', 'notif21') ?>";
	

	


	var activatemessage2 = "<?= Yii::$app->lang->t('extra', 'activate_confirm_single') ?>";
	var activatemessage3 = "<?= Yii::$app->lang->t('extra', 'extra75') ?>";
	var activatemessage3koma1 = "<?= Yii::$app->lang->t('extra', 'activate_confirm_massal_count') ?>";

	

	// Handler untuk aktivasi single user
	$(document).on('click', '.activate-contact', function() {
		var userId = $(this).data('id');

		if (!userId) {
			Swal.fire({
				title: notif3,
				text: notif4,
				icon: "error",
				confirmButtonColor: "#28a745",
				confirmButtonText: "OK"
			});
			return;
		}

		Swal.fire({
			title: deletemessage1,
			text: activatemessage2,
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#28a745",
			cancelButtonColor: "#6c757d",
			confirmButtonText: notif5,
			cancelButtonText: deletemessage4
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/users/activate']) ?>',
					type: 'post',
					data: {
						id: userId,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function(response) {
						$('#datatable').DataTable().ajax.reload();

						Swal.fire({
							title: notif6,
							text: notif7,
							icon: "success",
							timer: 2000,
							showConfirmButton: false
						});
					},
					error: function(xhr) {
						Swal.fire({
							title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>!",
							text: "<?= Yii::$app->lang->t('extra', 'extra88') ?>.",
							icon: "error",
							confirmButtonColor: "#28a745",
							confirmButtonText: "OK"
						});
					}
				});
			}
		});
	});

	// Tombol Batal modal (jika ada di form)
$(document).on('click', '#btn-cancel', function() {
    $('#modal_form_user').modal('hide'); // tutup modal
    $('#datatable').DataTable().ajax.reload(); // reload DataTable
});

// AJAX submit form user tanpa reload halaman (dengan validasi error)
$(document).on('beforeSubmit', '#user-form', function (e) {
    e.preventDefault();
    var form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (response) {
            if (response.success) {
                // Jika berhasil simpan
                $('#modal_form_user').modal('hide');
                $('#datatable').DataTable().ajax.reload();
                Swal.fire({
                    title: notif19,
                    text: notif8,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                // Jika gagal (validasi duplikat, dsb.)
                $('#modal-content-user').html(response);
            }
        },
        error: function () {
            Swal.fire(notif9, notif10, notif9);
        }
    });

    return false;
});



	$(document).on('click', '.delete-contact', function() {
		var contactId = $(this).data('id'); // Ambil ID kontak

		if (!contactId) {
			Swal.fire({
				title: notif3,
				text: notif12,
				icon: "error",
				confirmButtonColor: "#d33",
				confirmButtonText: "OK"
			});
			return;
		}

		Swal.fire({
			title: deletemessage1,
			text: deletemessage2,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: deletemessage5,
			cancelButtonText: deletemessage4
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/users/delete']) ?>',
					type: 'post', // Harus POST
					data: {
						id: contactId,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
					},
					success: function(response) {
						console.log('Response:', response);

						// Reload DataTable
						$('#datatable').DataTable().ajax.reload();

						// Tampilkan notifikasi sukses
						Swal.fire({
							title: notif13,
							text: notif14,
							icon: "success",
							timer: 2000,
							showConfirmButton: false
						});
					},
					error: function(xhr) {
						console.error('Error:', xhr.responseText);

						// Tampilkan notifikasi error
						Swal.fire({
							title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>!",
							text: "<?= Yii::$app->lang->t('extra', 'extra88') ?>.",
							icon: "error",
							confirmButtonColor: "#d33",
							confirmButtonText: "OK"
						});
					}
				});
			}
		});
	});

	$(document).on('click', '.reset-password', function() {
    var userId = $(this).data('id');

    if (!userId) {
        Swal.fire({
            title: notif3,
            text: notif4,
            icon: "error",
            confirmButtonColor: "#d33",
            confirmButtonText: "OK"
        });
        return;
    }

    Swal.fire({
        title: notif15,
        text: notif16,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: notif17,
        cancelButtonText: notif18
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= Url::to(['/users/resetpassword']) ?>',
                type: 'post',
                data: {
                    id: userId,
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                },
                headers: {
                    "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>",
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: notif19,
                            text: response.message,
                            icon: "success",
                            timer: 2500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire("Gagal!", response.message, "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire(notif3, notif20, notif9);
                }
            });
        }
    });
});





	// Update User popup
$(document).on('click', '.btn-update-user', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    if (!id) {
        alert(notif21);
        return;
    }
    let url = '<?= Url::to(["users/update"]) ?>?id=' + id;

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            let parser = new DOMParser();
            let doc = parser.parseFromString(data, 'text/html');
            $('#modal-content-user').html(doc.body.innerHTML);
            $('#modal-title-user').html("<?= Yii::$app->lang->t('back_home', 'edit_user') ?>");
            $('#modal_form_user').modal('show');
        },
        error: function () {
            $('#modal-content-user').html('<p class="text-danger">Error loading form.</p>');
        }
    });
});



	$(document).on('click', '.status-role', function() {
		var userId = $(this).data('id');
		var roleType = $(this).data('type');

		if (!userId || !roleType) {
			Swal.fire({
				title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>!",
				text: "<?= Yii::$app->lang->t('extra', 'extra88') ?>.",
				icon: "error",
				confirmButtonColor: "#d33",
				confirmButtonText: "OK"
			});
			return;
		}

		// Mapping role type ke role_id
		const roleMap = {
			superadmin: 0,
			admin: 1,
			owner: 2,
			affiliator: 3
		};

		const roleId = roleMap[roleType];

		Swal.fire({
			title: `<?= Yii::$app->lang->t('extra', 'extra89') ?> ${roleType}?`,
			text: "<?= Yii::$app->lang->t('extra', 'extra90') ?>.",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#28a745",
			cancelButtonColor: "#6c757d",
			confirmButtonText: "<?= Yii::$app->lang->t('extradouble', 'double2') ?>",
			cancelButtonText: "<?= Yii::$app->lang->t('back_home', 'chat34') ?>"
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?= Url::to(['/users/setrole']) ?>',
					type: 'post',
					data: {
						id: userId,
						role_id: roleId,
						_csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
					},
					headers: {
						"X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>",
					},
					success: function(response) {
						$('#datatable').DataTable().ajax.reload();

						Swal.fire({
							title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>!",
							text: "<?= Yii::$app->lang->t('extra', 'extra91') ?>.",
							icon: "success",
							timer: 2000,
							showConfirmButton: false
						});
					},
					error: function(xhr) {
						console.error('Error:', xhr.responseText);

						Swal.fire({
							title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>!",
							text: "<?= Yii::$app->lang->t('extra', 'extra88') ?>.",
							icon: "error",
							confirmButtonColor: "#d33",
							confirmButtonText: "OK"
						});
					}
				});
			}
		});
	});
</script>


