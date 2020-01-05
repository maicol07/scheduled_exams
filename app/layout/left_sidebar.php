<aside class="mdc-drawer mdc-drawer--dismissible">
    <div class="mdc-drawer__content">
        <div class="mdc-list">
            <?php $current_page = basename($_SERVER['SCRIPT_NAME'], '.php') ?>
            <a class="mdc-list-item <?php echo $current_page == "index" ? 'mdc-list-item--activated" aria-current="page' : '"href="index' ?>">
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
                    $classroom_users = json_decode($classroom->users);
                    if (is_array($classroom_users) and !in_array($user->getId(), $classroom_users)) {
                        continue;
                    }
                    echo '<a class="mdc-list-item ' . (($current_page == "classroom" and get('view') == $classroom->code) ?
                            'mdc-list-item--activated" aria-current="page' : 'href="app/classroom?view=' . $classroom->code . '') . '">
                            <i class="mdi-outline-class mdc-list-item__graphic"></i>
                            <span class="mdc-list-item__text mdc-typography--subtitle2">' . $classroom->name . '</span>
                          </a>';
                }
            }
            ?>
        </div>
    </div>
</aside>