<?php

use yii\helpers\Html;
use yii\helpers\Url;

if (!empty($trackings)) {
    usort($trackings, function ($a, $b) {
        return strtotime($a->time) - strtotime($b->time);
    });
}

$lastTracking = !empty($trackings) ? $trackings[count($trackings) - 1] : null;
$total = count($trackings);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking | Total <?= Html::encode($tran->tranno) ?></title>
    <link rel="icon" type="image/png" href="/assets/media/logos/total.svg">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-body-tertiary">

    <!-- Top Bar -->
    <nav class="navbar navbar-expand bg-white border-bottom sticky-top shadow-sm">
        <div class="container-fluid px-3 px-md-4">
            <img src="/assets/media/logos/total.svg" alt="Total" class="navbar-brand p-0 m-0" height="32">
            <span class="ms-auto text-muted small text-truncate">
                Total <strong class="text-primary"><?= Html::encode($tran->tranno) ?></strong>
            </span>
        </div>
    </nav>

    <div class="container py-4 pb-5">

        <!-- Detail Header -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="fs-4 fw-bold text-primary"><?= Html::encode($tran->tranno) ?></div>
                    <div class="text-muted small">Tracking</div>
                </div>
                <?php if ($lastTracking): ?>
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2 d-inline-flex align-items-center gap-2 fs-6 fw-semibold">
                        <span class="spinner-grow spinner-grow-sm text-success" role="status" aria-hidden="true"></span>
                        <?= Html::encode($lastTracking->status) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">

            <!-- ═══ LEFT: Info Panel ═══ -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h2 class="h6 mb-0 text-primary">
                            <i class="fa-solid fa-circle-info me-2"></i>Detail Transaksi
                        </h2>
                    </div>
                    <div class="card-body">

                        <!-- Transaksi -->
                        <h3 class="text-uppercase text-muted small fw-bold border-bottom pb-2 mb-3">Informasi Transaksi</h3>

                        <div class="pb-2 mb-2 border-bottom">
                            <div class="text-uppercase text-muted small fw-semibold">No. Transaksi</div>
                            <div class="fw-semibold text-primary"><?= Html::encode($tran->tranno) ?></div>
                        </div>

                        <?php if (!empty($tran->customer)): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Customer</div>
                                <div class="fw-medium">
                                    <?= Html::encode($tran->contact ? $tran->contact->contact_name ?? '-' : '-') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($tran->startdate)): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Tanggal Mulai</div>
                                <div class="fw-medium"><?= Html::encode($tran->startdate) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($tran->enddate)): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Tanggal Selesai</div>
                                <div class="fw-medium"><?= Html::encode($tran->enddate) ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Event -->
                        <?php if (!empty($tran->eventname) || !empty($tran->locations)): ?>
                            <h3 class="text-uppercase text-muted small fw-bold border-bottom pb-2 mb-3 mt-4">Informasi Event</h3>
                        <?php endif; ?>

                        <?php if (!empty($tran->eventname)): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Nama Event</div>
                                <div class="fw-medium"><?= Html::encode($tran->eventname) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($tran->eventtype)): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Tipe Event</div>
                                <div class="fw-medium"><?= Html::encode($tran->eventtype) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($tran->locations)): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Lokasi</div>
                                <div class="fw-medium"><?= Html::encode($tran->locations) ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- PIC -->
                        <?php
                        $hasPic = !empty($tran->aeid) || !empty($tran->operatorid) || !empty($tran->storemanid) || !empty($tran->pic1id) || !empty($tran->pic2id);
                        if ($hasPic):
                            // Helper: get employee name by ID
                            $empName = function ($id) {
                                if (empty($id))
                                    return null;
                                return \Yii::$app->db->createCommand(
                                    "SELECT contact_name FROM contacts WHERE contact_id = :id LIMIT 1"
                                )->bindValue(':id', $id)->queryScalar() ?: $id;
                            };
                            ?>
                            <h3 class="text-uppercase text-muted small fw-bold border-bottom pb-2 mb-3 mt-4">PIC (Person In Charge)</h3>
                            <div class="row row-cols-2 g-3">
                                <?php if (!empty($tran->aeid)): ?>
                                    <div class="col">
                                        <div class="text-uppercase text-muted small fw-semibold">AE</div>
                                        <div class="fw-medium"><?= Html::encode($empName($tran->aeid)) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($tran->operatorid)): ?>
                                    <div class="col">
                                        <div class="text-uppercase text-muted small fw-semibold">Operator</div>
                                        <div class="fw-medium"><?= Html::encode($empName($tran->operatorid)) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($tran->storemanid)): ?>
                                    <div class="col">
                                        <div class="text-uppercase text-muted small fw-semibold">Storeman</div>
                                        <div class="fw-medium"><?= Html::encode($empName($tran->storemanid)) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($tran->pic1id)): ?>
                                    <div class="col">
                                        <div class="text-uppercase text-muted small fw-semibold">PIC 1</div>
                                        <div class="fw-medium"><?= Html::encode($empName($tran->pic1id)) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($tran->pic2id)): ?>
                                    <div class="col">
                                        <div class="text-uppercase text-muted small fw-semibold">PIC 2</div>
                                        <div class="fw-medium"><?= Html::encode($empName($tran->pic2id)) ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Tracking Summary -->
                        <h3 class="text-uppercase text-muted small fw-bold border-bottom pb-2 mb-3 mt-4">Tracking</h3>
                        <div class="pb-2 mb-2 border-bottom">
                            <div class="text-uppercase text-muted small fw-semibold">Total Update</div>
                            <div class="fw-medium"><?= $total ?> update</div>
                        </div>
                        <?php if ($lastTracking): ?>
                            <div class="pb-2 mb-2 border-bottom">
                                <div class="text-uppercase text-muted small fw-semibold">Update Terakhir</div>
                                <div class="fw-medium"><?= Html::encode($lastTracking->time) ?></div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Share Box -->
                <div class="card bg-body-tertiary border-0 mt-3">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small fw-semibold mb-2">
                            <i class="fa-solid fa-link me-1"></i> Tracking Link
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" id="tracking-link" class="form-control" readonly
                                value="<?= Url::to(['/site/trackview', 'id' => $tran->tranid], true) ?>">
                            <button class="btn btn-primary d-flex align-items-center gap-1" type="button"
                                onclick="copyLink()" id="copy-btn">
                                <i class="fas fa-copy"></i> <span id="copy-text">Copy</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- /info-side -->

            <!-- ═══ RIGHT: Timeline ═══ -->
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h2 class="h6 mb-0 text-primary">
                            <i class="fa-solid fa-route me-2"></i>Tracking History
                        </h2>
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle fw-semibold"><?= $total ?> update</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($trackings)): ?>
                            <?php foreach ($trackings as $i => $t): ?>
                                <?php
                                $isLatest = ($i === $total - 1);
                                $isLast = ($i === $total - 1);
                                $ts = strtotime($t->time);
                                $dateStr = $ts ? date('Y-m-d', $ts) : $t->time;
                                $timeStr = $ts ? date('H:i:s', $ts) : '';
                                ?>
                                <div class="row g-0">
                                    <div class="col-3 col-md-2 text-end pe-3">
                                        <div class="fw-semibold small"><?= Html::encode($dateStr) ?></div>
                                        <small class="text-muted d-block"><?= Html::encode($timeStr) ?></small>
                                    </div>
                                    <div class="col-9 col-md-10 border-start border-2 <?= $isLatest ? 'border-primary' : 'border-secondary-subtle' ?> ps-3 <?= $isLast ? 'pb-1' : 'pb-4' ?>">
                                        <?php if ($t->location): ?>
                                            <div class="text-uppercase text-muted small fw-bold"><?= Html::encode($t->location) ?></div>
                                        <?php endif; ?>
                                        <?php if ($t->status): ?>
                                            <div class="fw-semibold <?= $isLatest ? 'text-primary' : '' ?>">
                                                <i class="fa-solid fa-circle fa-2xs me-1 <?= $isLatest ? 'text-primary' : 'text-secondary' ?>"></i>
                                                <?= Html::encode($t->status) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($t->remarks): ?>
                                            <p class="text-muted small mb-2"><?= Html::encode($t->remarks) ?></p>
                                        <?php endif; ?>
                                        <?php if ($t->image): ?>
                                            <div class="row">
                                                <div class="col-8 col-sm-5 col-lg-4">
                                                    <img src="<?= Yii::getAlias('@web') . '/' . $t->image ?>" alt="Tracking Image"
                                                        class="img-fluid rounded border" loading="lazy" role="button"
                                                        data-bs-toggle="modal" data-bs-target="#imgModal"
                                                        onclick="document.getElementById('modal-img').src=this.src">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class="fa-solid fa-box-open fs-1 opacity-50 d-block mb-3"></i>
                                <div>Belum ada data tracking.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div><!-- /timeline-side -->

        </div>
    </div>

    <!-- Lightbox (Bootstrap Modal) -->
    <div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-0">
                    <img id="modal-img" src="" class="img-fluid rounded" alt="Tracking Image Preview">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyLink() {
            const input = document.getElementById('tracking-link');
            const btn = document.getElementById('copy-btn');
            const text = document.getElementById('copy-text');
            navigator.clipboard.writeText(input.value).then(() => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                text.textContent = 'Copied!';
                setTimeout(() => { btn.classList.remove('btn-success'); btn.classList.add('btn-primary'); text.textContent = 'Copy'; }, 2000);
            }).catch(() => {
                input.select();
                document.execCommand('copy');
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                text.textContent = 'Copied!';
                setTimeout(() => { btn.classList.remove('btn-success'); btn.classList.add('btn-primary'); text.textContent = 'Copy'; }, 2000);
            });
        }
    </script>

</body>

</html>