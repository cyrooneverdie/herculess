<?php

use yii\helpers\Html;
use yii\helpers\Url;

// var_dump($details);die;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= $model->tranno ?></title>
    <style>
        /* CSS Reset untuk konsistensi */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Pengaturan dasar */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        .invoice-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ccc;
            background: #fff;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            /* sejajarkan atas */
            margin-bottom: 30px;
        }

        .company-info {
            display: flex;
            align-items: center;
            /* sejajarkan logo & teks di tengah vertikal */
            gap: 20px;
            /* jarak antar logo dan teks */
        }

        .logo img {
            width: 120px;
            height: auto;
        }
.notes-section {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-top: 20px;
}

.notes-box, .event-info-box {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 12px 15px;
    width: 50%;
    background-color: #f9f9f9;
}

.notes-header, .event-info-header {
    font-weight: bold;
    margin-bottom: 6px;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
}

.notes-content, .event-info-content {
    font-size: 13px;
    color: #555;
    line-height: 1.5;
}

.event-info-box {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 8px 10px;
    width: 50%;
    background-color: #fafafa;
    margin-top: 8px;
}

.event-info-header {
    font-weight: bold;
    margin-bottom: 4px;
    color: #555;
    font-size: 11px;
    text-transform: uppercase;
}

.event-info-content {
    font-size: 11px;
    color: #555;
    line-height: 1.4;
}

.event-info-content p {
    margin: 2px 0;
}

        .company-details {
            color: #333;
            line-height: 1.6;
        }

        .company-name {
            color: #555;
            font-weight: bold;
        }

        .invoice-title {
            text-align: right;
            flex: 2;
            font-size: 10px;
            font-weight: bold;
            color: #3f51b5;
        }

        /* Date Info */
        .date-info {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .date-table {
            width: 350px;
            border-collapse: collapse;
        }

        .date-table td {
            padding: 5px 10px;
            border: 1px solid #ddd;
        }

        .date-table td:first-child {
            font-weight: bold;
            background-color: #f8f8f8;
            width: 40%;
        }

        /* Bill To Section */
        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            width: 100%;
        }

        .bill-to-section {
            flex: 1;
            margin-right: 30px;
        }

        .invoice-info {
            flex: 1;
            text-align: start;
        }

        .bill-to-header {
            background-color: #344584;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bill-to-content {
            padding: 5px 0;
            line-height: 1.6;
        }

        /* Table Section */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            background-color: #344584;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Payment Section */
        .bottom-section {
            display: flex;
            margin-bottom: 40px;
        }

        .payment-info {
            flex: 1;
            margin-right: 30px;
        }

        .payment-header {
            background-color: #344584;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .payment-content {
            padding: 10px 0;
            line-height: 1.6;
        }

        .terbilang {
            margin-top: 15px;
            font-weight: bold;
        }

        .totals {
            flex: 1;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table tr td {
            padding: 5px;
        }

        .totals-table tr td:first-child {
            text-align: right;
            width: 50%;
        }

        .totals-table tr td:last-child {
            text-align: right;
            width: 50%;
        }

        /* Signature Section */
        .signature-section {
            text-align: right;
            margin-top: 0px;
        }

        .signature-text {
            text-align: right;
            margin-bottom: 0px;
        }

        .signature-img {
            max-width: 150px;
            margin-bottom: 0px;
        }

        .signature-name {
            font-weight: bold;
            /* margin-top: 60px; */
        }

        .signature-title {
            margin-bottom: 0px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 50px;
            color: #666;
        }

        /* Print Styles - Crucial untuk hasil cetak */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                border: none;
                width: 100%;
                max-width: none;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            /* Pastikan tabel tidak rusak saat dicetak */
            .invoice-table {
                border-collapse: collapse;
                width: 100%;
                page-break-inside: auto;
            }

            .invoice-table th {
                background-color: #344584 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .payment-header,
            .bill-to-header {
                background-color: #344584 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Gunakan border sebagai alternatif untuk background yang sulit */
            table,
            th,
            td {
                border: 1px solid #ddd !important;
            }

            /* Hindari pemisahan elemen */
            tr,
            td,
            th,
            div {
                page-break-inside: avoid;
            }
        }

        /* Print button */
        .print-button {
            background: #344584;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">Print Invoice</button>

    <div class="invoice-container">
        <div class="header" style="border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">
            <div class="company-info" style="display: flex; align-items: center;">
                <div class="logo" style="margin-right: 15px;">
                    <?php
                    $path = Yii::getAlias('@webroot') . '/assets/media/logos/mlogo.webp';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                    <img src="<?= $base64 ?>" width="150" alt="Dwansoft Logo">
                </div>
                <div class="company-details">
                    <div class="company-name" style="font-weight: bold; font-size: 18px;">PT. Maxindo Visual Pratama</div>
                    <div>Jl. Gempol Raya No.3A, RT.005/RW.009, Kunciran Indah, Kec. Pinang, Kota Tangerang, Banten 15144</div>
                    <div>082382777394 / maxindo@maxindoled.com</div>
                </div>
            </div>
        </div>
        <div class="top-section">
            <div class="bill-to-section">
                <div class="bill-to-header">BILL TO</div>
                <div class="bill-to-content">
                    <?= !empty($details[0]['contact_name']) ? $details[0]['contact_name'] : 'Arif sanjaya purnama' ?><br>
                    <?= !empty($details[0]['jobcompany']) ? $details[0]['jobcompany'] . '<br>' : '' ?>
                    Batam Center, Kota Batam, Kepulauan Riau<br>
                    Up : Finance & Admin Dept
                </div>
            </div>

            <div class="invoice-info">
                <div class="invoice-title" style="font-size:12px; font-weight:bold; color:#344584;">INVOICE</div>
                <div class="date-info">
                    <table class="date-table">
                        <tr>
                            <td>NOMOR</td>
                            <td><?= $model->tranno ?></td>
                        </tr>
                        <tr>
                            <td>TANGGAL</td>
                            <td><?= Yii::$app->formatter->asDate($model->trandate, 'dd/MM/yyyy') ?></td>
                        </tr>
                        <tr>
                            <td>TGL. JATUH TEMPO</td>
                            <td>
                                <?php
                                $dueDate = $model->tranduedate ? $model->tranduedate : date('Y-m-d', strtotime('+7 days', strtotime($model->trandate)));
                                echo Yii::$app->formatter->asDate($dueDate, 'dd/MM/yyyy');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Payment</td>
                            <td><?= isset($details[0]['term']) ? Html::encode($details[0]['term']) : '-' ?> day</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="35%">PRODUK</th>
                    <th width="55%">DESKRIPSI</th>
                    <th width="10%" class="text-center">KUANTITAS</th>
                    <th width="15%" class="text-right">HARGA</th>
                    <th width="10%" class="text-center">DISKON</th>
                    <th width="20%" class="text-center">PAJAK</th>
                    <th width="30%" class="text-right">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($details as $detail):
                    $price = floatval($detail['price']);
                    $subtotal = floatval($detail['itemsubtotal']);
                    $discount = 0; // Set your discount logic here
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-left"><?= Html::encode($detail['productname']) ?></td>
                        <td><?= !empty($detail['itemtype']) ? Html::encode($detail['itemtype']) : Html::encode($detail['description'])  ?></td>
                        <td class="text-center"><?= $detail['amount'] ?></td>
                        <td class="text-right"><?= 'Rp.' . number_format($price, 0, ',', '.') ?></td>
                        <td class="text-center"><?= $detail['discount'] ?>%</td>
                        <td class="text-center">
                            <?php
                            if ($detail['ppnamount'] != null) {
                                echo 'Rp.' . number_format($detail['ppnamount'], 0, ',', '.');
                            } elseif ($detail['pphamount'] != null) {
                                echo 'Rp.' . number_format($detail['pphamount'], 0, ',', '.');
                            } elseif ($detail['pphamount'] > 0 && $detail['ppnamount'] > 0) {
                                echo 'Rp.' . number_format($detail['pajak_total'], 0, ',', '.');
                            }
                            ?>
                        </td>
                        <!-- <td class="text-center"></td> -->
                        <td class="text-right"><?= number_format($detail['itemsubtotaltax'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bottom-section">
            <div class="payment-info">
                <div class="payment-header">
                    PESAN
                </div>
                <div class="payment-content">
                    Mohon agar pembayaran tersebut dapat ditransfer ke rekening kami di BANK OCBC cabang Batam Center No. Rekening 090800021371 a/n PT. Dwasoft Global Indonesia.
                </div>
                <div class="terbilang">
                    Terbilang<br>
                    <?= isset($terbilang) ? ucfirst($terbilang) : 'Tiga Ratus Juta Dua Belas' ?> Rupiah
                </div>
            </div>
            <div class="totals">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php if ($model->pphamount != null) {
                        $total = $total - $model->pphamount;
                    ?>
                        <tr>
                            <td>Pemotongan</td>
                            <td>(Rp <?= number_format($model->pphamount, 0, ',', '.') ?>)</td>
                        </tr>
                    <?php } ?>
                    <?php if ($model->disc != null) {
                        $total = $total - $model->disc;
                    ?>
                        <tr>
                            <td>Diskon</td>
                            <td>(Rp <?= number_format($model->disc, 0, ',', '.') ?>)</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>Total</td>
                        <td>Rp <?= number_format($detail['totalafterdisc'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>

                        <td><b>SISA TAGIHAN</b></td>
                        <td><b>Rp <?= number_format($detail['totalafterdisc'], 0, ',', '.') ?></b></td>

                    </tr>
                </table>
            </div>
        </div>
<div class="notes-section">
    <div class="notes-box">
        <div class="notes-header">Notes</div>
        <div class="notes-content">
            <?= !empty($detail['note']) ? Html::encode($detail['note']) : '-' ?>
        </div>
    </div>

    <div class="event-info-box mt-3">
        <div class="event-info-header">Detail Acara</div>
        <div class="event-info-content">
            <p><strong>Lokasi Acara:</strong> <?= !empty($detail['locations']) ? Html::encode($detail['locations']) : '-' ?></p>
            <p><strong>Tanggal Antar:</strong> <?= !empty($detail['trandate']) ? Html::encode($detail['trandate']) : '-' ?></p>
            <p><strong>Tanggal Tarik:</strong> <?= !empty($detail['tranduedate']) ? Html::encode($detail['tranduedate']) : '-' ?></p>
        </div>
    </div>
</div>


        <div class="signature-section">
            <div class="signature-text">Dengan Hormat,</div>
            <div>
                <?php
                $path = Yii::getAlias('@webroot') . '/assets/media/report/ttdbunita.png';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <img src="<?= $base64 ?>" alt="Signature and Stamp" class="signature-img">

            </div>
            <div class="signature-name">PT. Maxindo Visual Pratama</div>
            <div class="signature-title">Finance Dept</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>