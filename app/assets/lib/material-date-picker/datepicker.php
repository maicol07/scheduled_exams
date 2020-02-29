<!-- DATE PICKER -->
<div id="date-picker">
    <div>
        <div id="date-picker-header">
            <div id="date-picker-dow"></div>
            <div id="date-picker-month"></div>
            <div id="date-picker-day"></div>
            <div id="date-picker-year"></div>
        </div>
        <div id="date-picker-cal-month">
            <span id="date-picker-cal-month-prev"><i class="mdi-outline-chevron_left"></i>&nbsp;&nbsp;&nbsp;</span>
            <p id="date-picker-cal-month-label"></p>
            <span id="date-picker-cal-month-next">&nbsp;&nbsp;&nbsp;<i class="mdi-outline-chevron_right"></i></span>
        </div>
        <div id="date-picker-cal-wrapper">
            <table id="date-picker-cal" class="table borderless">
                <thead>
                <tr>
                    <?php
                    $days = [__("Dom"), __("Lun"), __("Mar"), __("Mer"), __("Gio"), __("Ven"), __("Sab")];
                    foreach ($days as $day) {
                        echo "<td>$day</td>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="date-picker-buttons" style="text-align: right">
            <a id="date-picker-reset-button" class="mdc-icon-button" title="<?php echo __("Reimposta") ?>">
                <i class="mdc-icon-button__icon mdi-outline-today"></i>
            </a>
            <a id="date-picker-cancel-button" class="mdc-button">
                <div class="mdc-button__ripple"></div>
                <span class="mdc-button__label"><?php echo __("Annulla") ?></span>
            </a>
            <a id="date-picker-ok-button" class="mdc-button">
                <div class="mdc-button__ripple"></div>
                <span class="mdc-button__label"><?php echo __("OK") ?></span>
            </a>
        </div>
    </div>
</div>
<!-- DATE PICKER -->
