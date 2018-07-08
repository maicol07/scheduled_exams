<?php
$alba = 6;
$giorno = 18;
$ora = date("H");
?>
<?php if ($ora >= 3 && $ora <= $alba) { ?>
    <!-- Se l'ora attuale è maggiore di 3 e minore di $alba che è 6 -->
    <style>
        /* Mostra questo codice css per l'alba */
        body {
            background-size: auto;
            background: url(https://dl.dropboxusercontent.com/s/6m36z4d8zyi8ily/3%20-%20vVsVx5p.png?dl=0) repeat center;
        }
    </style>

<?php } elseif ($ora > $alba && $ora <= $giorno) { ?>

    <!-- Se l'ora attuale è maggiore di 6 ($alba) e minore di $giorno che è 18 -->
    <style>
        /* Mostra questo codice css per il giorno */
        body {
            background-size: auto;
            background: url(https://dl.dropboxusercontent.com/s/ka8x2wcs46y03kd/4%20-%20ZFabsbM.png?dl=0) repeat center;
        }
    </style>

<?php } else { ?>

    <!-- Se nessuna delle precedenti condizioni è soddisfatta allora è notte -->

    <style>
        /* Mostra questo codice css per la notte */
        body {
            background-size: auto;
            background: url(https://dl.dropboxusercontent.com/s/0qe4q8rl7pfspal/2%20-%20lGi5EO6.png?dl=0) repeat center;
        }
    </style>

<?php } ?>