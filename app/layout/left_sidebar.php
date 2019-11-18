<aside class="mdc-drawer mdc-drawer--dismissible">
    <div class="mdc-drawer__header">
        <h3 class="mdc-drawer__title"><?php echo $user->getName() ?></h3>
        <h6 class="mdc-drawer__subtitle"><?php echo $user->getUsername() ?></h6>
    </div>
    <div class="mdc-drawer__content">
        <div class="mdc-list">
            <a class="mdc-list-item mdc-list-item--activated" aria-current="page">
                <i class="mdi-outline-dashboard mdc-list-item__graphic" aria-hidden="true"></i>
                <span class="mdc-list-item__text"><?php echo __("Dashboard") ?></span>
            </a>
            <a class="mdc-list-item">
                <i class="mdi-outline-class mdc-list-item__graphic" aria-hidden="true"></i>
                <span class="mdc-list-item__text"><?php echo __("Classi") ?></span>
            </a>
        </div>
    </div>
</aside>