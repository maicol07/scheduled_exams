<?php

use Gravatar\Gravatar;
use src\Utils;

require_once DOCROOT . '/vendor/gravatarphp/gravatar/src/Gravatar.php';
?>
<header class="mdc-top-app-bar">
    <div class="mdc-top-app-bar__row">
        <section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
            <a class="mdc-top-app-bar__navigation-icon mdc-icon-button"><i class="mdi-outline-menu"></i></a>
            <img src="<?php echo Utils::buildAssetsURI("/app/assets/img/logo.svg") ?>"
                 alt="<?php echo __("Interrogazioni Programmate") ?>"
                 style="margin-left: 15px; width: 40px; height: 40px; margin-right: -8px">
            <span class="mdc-top-app-bar__title" style="vertical-align: middle">
                <?php echo __("Interrogazioni Programmate") ?>
            </span>
        </section>
        <section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end">
            <div class="mdc-top-app-bar__action-item mdc-menu-surface--anchor">
                <button class="mdc-icon-button menu-button" aria-label="<?php echo __("Cambia lingua") ?>">
                    <i class="mdi-outline-language"></i>
                </button>
                <div class="mdc-menu mdc-menu-surface" style="margin-top: 55px;">
                    <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
                        <?php
                        foreach ($langs as $code => $details) {
                            echo '
                            <li class="mdc-list-item" role="menuitem" ' . (($code != $lang) ? 'onclick="langNotice(\'' . $code . '\')' : '') . '">
                                <svg class="mdc-list-item__graphic flag-icon-' . $details['flag'] . '" style="height: 17.5px;"></svg><span class="mdc-list-item__text">' . $details['text'] .
                                ($code == $lang ? ' <i class="mdi-outline-check" style="vertical-align: middle; margin-left: 10px"></i>' : '') . '</span>
                            </li>
                            ';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
            $gravatar = new Gravatar();
            $user_img = $gravatar->avatar($user->getEmail());
            ?>
            <div class="mdc-top-app-bar__action-item">
                <button class="mdc-icon-button" aria-label="<?php echo __("Il tuo profilo") ?>" id="user_btn"
                        data-user-img="<?php echo $user_img ?>" data-user-name="<?php echo $user->getName() ?>"
                        data-user-email="<?php echo $user->getEmail() ?>">
                    <img src="<?php echo $user_img ?>" alt="<?php echo $user->getName() ?>" style="border-radius: 50%">
                </button>
            </div>
        </section>
    </div>
</header>