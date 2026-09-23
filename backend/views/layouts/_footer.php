<div id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-gray-900">
            <span class="text-muted fw-semibold me-1">2016&copy;</span>
            <a href="https://dwansoft.com" target="_blank" class="text-gray-800 text-hover-primary">Dwansoft</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="https://dwansoft.com" target="_blank"
                    class="menu-link px-2"><?= Yii::$app->lang->t('back_foot', 'chat1') ?></a>
            </li>
            <li class="menu-item">
                <a href="https://dwansoft.com" target="_blank"
                    class="menu-link px-2"><?= Yii::$app->lang->t('back_foot', 'chat2') ?></a>
            </li>
            <li class="menu-item">
                <a href="https://dwansoft.com" target="_blank"
                    class="menu-link px-2"><?= Yii::$app->lang->t('back_foot', 'chat3') ?></a>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function initMasking() {

        Inputmask({
            alias: 'numeric',
            allowMinus: false,
            rightAlign: true,
            removeMaskOnSubmit: true,
            groupSize: 3,
            groupSeparator: ',',
            autoGroup: true,
            integerDigits: 10,
            digits: 2, // ← ubah dari 0 ke 2
            digitsOptional: false,
            radixPoint: '.', // titik tetap digunakan sebagai pemisah desimal
            placeholder: '0',
            autoUnmask: true,
            min: 0,
            oncomplete: function () {
                var value = this.value;
                if (value && !value.includes('.')) {
                    this.value = value + '.00';
                } else if (value && value.endsWith('.')) {
                    this.value = value + '00';
                } else if (value && value.match(/\.\d$/)) {
                    this.value = value + '0';
                }
            }
        }).mask($('.money'));


        flatpickr(".pickdate", {
            dateFormat: "d/m/Y",
            allowInput: true,
            disableMobile: true
        });

        flatpickr(".picktime", {
            enableTime: true,
            dateFormat: "d/m/Y H:i",
            time_24hr: true,
            allowInput: true,
            static: true,
            disableMobile: true
        });

    }

    function escapeHtml(mystring) {
        return mystring.replace(/(?:\r\n|\r|\n)/g, '<br />');
    }

    function formatMoney(number, places, symbol, thousand, decimal) {
        number = number || 0;
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "Rp.";
        thousand = thousand || ".";
        decimal = decimal || ",";
        var negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
    }

    function textAreaAdjust(o) {
        o.style.height = "1px";
        o.style.height = (5 + o.scrollHeight) + "px";
    }

    function convertDate(dateStr) {
        if (!dateStr) return null;

        if (dateStr.includes('-')) {
            let isoParts = dateStr.split(' ')[0].split('-');
            if (isoParts.length === 3) {
                return `${isoParts[0]}/${isoParts[1]}/${isoParts[2]}`;
            }
        }

        let parts = dateStr.split('/');
        if (parts.length !== 3) return null;
        let day = parts[0].padStart(2, '0');
        let month = parts[1].padStart(2, '0');
        let year = parts[2];
        return `${year}/${month}/${day}`;
    }

    function convertY2d(dateStr) {
        let parts = dateStr.split('/');
        if (parts.length !== 3) return null;
        let day = parts[0].padStart(2, '0');
        let month = parts[1].padStart(2, '0');
        let year = parts[2];
        return `${year}/${month}/${day}`;
    }

    function addDays(date, days) {
        var dateparse = convertDate(date);
        var result = new Date(dateparse);
        result.setDate(result.getDate() + parseInt(days));
        var d = new Date(result);
        var date = [
            ('0' + d.getDate()).slice(-2),
            ('0' + (d.getMonth() + 1)).slice(-2),
            d.getFullYear(),
        ].join('/');
        return date;
    }

    function StringtoDate(d) {
        var parts = d.split("/");
        // console.log(parts);
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    function DatetoString(d) {
        var curr_date = d.getDate();
        var curr_month = d.getMonth() + 1;
        var curr_year = d.getFullYear();

        if (curr_month < 10) {
            curr_month = '0' + curr_month;
        }
        if (curr_date < 10) {
            curr_date = '0' + curr_date;
        }

        return curr_date + "/" + curr_month + "/" + curr_year;
    }
    $(document).on('select2:clear', 'select', function (e) {
        $(this).append('<option value=""></option>').val('').trigger('change');
    });

    function DatetoStringTime(d) {
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }

    function convertDateTime(dateStr) {
        let parts = dateStr.split('/');
        if (parts.length !== 3) return null;

        let day = parts[0].padStart(2, '0');
        let month = parts[1].padStart(2, '0');
        let year = parts[2];

        return `${year}-${month}-${day} 00:00`;
    }
</script>