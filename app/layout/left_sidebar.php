<aside class="mdc-drawer mdc-drawer--dismissible">
    <div class="mdc-drawer__content">
        <div class="mdc-list">
            <a class="mdc-list-item mdc-list-item--activated" aria-current="page">
                <i class="mdi-outline-dashboard mdc-list-item__graphic" aria-hidden="true"></i>
                <span class="mdc-list-item__text mdc-typography--subtitle2"><?php echo __("Dashboard") ?></span>
            </a>
            <?php

            use src\Classroom;

            $classroom_obj = new Classroom($db, $user);
            $classrooms = $classroom_obj->getClassrooms();
            if (!empty($classrooms)) {
                echo '<hr class="mdc-list-divider">
            <h6 class="mdc-list-group__subheader mdc-typography--subtitle1">' . __("Classi") . '</h6>';
                foreach ($classrooms as $classroom) {
                    $classroom = (object)$classroom;
                    if (!in_array($user->getId(), unserialize($classroom->users))) {
                        continue;
                    }
                    echo '<a href="app/classroom?view=' . $classroom->code . '" class="mdc-list-item">
                            <span class="mdc-list-item__text mdc-typography--subtitle2">' . $classroom->name . '</span>
                          </a>';
                }
            }
            ?>
        </div>
    </div>
</aside>