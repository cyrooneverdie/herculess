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
            /* margin-bottom: 50px; */
        }

        .company-info {
            flex: 1;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
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
            flex: 1;
            font-size: 24px;
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
        .bill-to-section {
            margin-bottom: 30px;
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

            .payment-header, .bill-to-header {
                background-color: #344584 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Gunakan border sebagai alternatif untuk background yang sulit */
            table, th, td {
                border: 1px solid #ddd !important;
            }

            /* Hindari pemisahan elemen */
            tr, td, th, div {
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
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="logo">
                <?php
                $path = Yii::getAlias('@webroot') . '/assets/media/report/logodwansoft.png';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <img src="<?= $base64 ?>" width="150" alt="Dwansoft Logo">
                </div>
                <div class="company-details">
                    <div class="company-name">PT. Dwansoft Global Indonesia</div>
                    <div>Ruko Cluster Daisy Blok E17 No 18.19, Batam Center</div>
                    <div>Telp: 081321210500</div>
                    <div>Email: dwansoft@gmail.com</div>
                    <div>NPWP : 81.490.988.3-225.000</div>
                </div>
            </div>
            <div class="invoice-title">
                INVOICE
            </div>
        </div>

        <!-- Date Info -->
        <div class="date-info">
            <table class="date-table">
                <tr>
                    <td>NOMOR</td>
                    <td><?= $model->tranno?></td>
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
            </table>
        </div>

        <!-- Bill To Section -->
        <div class="bill-to-section">
            <div class="bill-to-header">
                TAGIHAN KEPADA
            </div>
            <div class="bill-to-content">
                <?= !empty($details[0]['contact_name']) ? $details[0]['contact_name'] : 'Arif sanjaya purnama' ?><br>
                <?= !empty($details[0]['jobcompany']) ? $details[0]['jobcompany'] . '<br>' : '' ?>
                Batam Center, Kota Batam, Kepulauan Riau<br>
                Up : Finance & Admin Dept
            </div>
        </div>

        <!-- Items Table -->
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
                    <td><?= !empty($detail['itemtype']) ? Html::encode($detail['itemtype']) : Html::encode($detail['deskripsi'])  ?></td>
                    <td class="text-center"><?= $detail['amount'] ?></td>
                    <td class="text-right"><?= 'Rp.' . number_format($price, 0, ',', '.') ?></td>
                    <td class="text-center"><?= $discount ?>%</td>
                    <td class="text-center">
                        <?php
                        if($detail['ppnamount'] != null){
                            echo 'Rp.' . number_format($detail['ppnamount'], 0, ',', '.');
                        }elseif($detail['pphamount'] != null){
                            echo 'Rp.' . number_format($detail['pphamount'] , 0 , ',' , '.');
                        }elseif($detail['pphamount'] > 0 && $detail['ppnamount'] > 0){
                            echo 'Rp.' . number_format($detail['pajak_total'], 0, ',', '.');
                        }
                        ?>
                    </td>
                    <!-- <td class="text-center"></td> -->
                    <td class="text-right"><?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Payment and Totals Section -->
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
                        <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                    <?php if ($model->pphamount != null){
                        $total = $total - $model->pphamount;
                        ?>
                    <tr>
                        <td>Pemotongan</td>
                        <td>(Rp <?= number_format($model->pphamount, 0, ',', '.') ?>)</td>
                    </tr>
                    <?php } ?>
                    <?php if ($model->disc != null){
                        $total = $total - $model->disc;
                        ?>
                    <tr>
                        <td>Diskon</td>
                        <td>(Rp <?= number_format($model->disc, 0, ',', '.') ?>)</td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Total</td>
                        <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        
                        <td><b>SISA TAGIHAN</b></td>
                        <td><b>Rp <?= number_format($total, 0, ',', '.') ?></b></td>
                        
                    </tr>
                </table>
            </div>
        </div>

        <!-- Signature Section -->
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
            <div class="signature-name">Nita Puspita</div>
            <div class="signature-title">Finance Dept</div>
        </div>
    </div>

    <script>
        // Auto print when document is ready
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
