<aside class="mdc-drawer mdc-drawer--dismissible">
    <div class="mdc-drawer__content">
        <nav class="mdc-list">
            <?php $current_page = basename($_SERVER['SCRIPT_NAME'], '.php') ?>
            <a class="mdc-list-item <?php echo $current_page == "index" ? 'mdc-list-item--activated" aria-current="page' : '"href="index' ?>"
               tabindex="0">
                <i class="mdi-outline-dashboard mdc-list-item__graphic" aria-hidden="true"></i>
                <span class="mdc-list-item__text mdc-typography--subtitle2"><?php echo __("Dashboard") ?></span>
            </a>
            <?php

            use src\Classroom;
            use src\Collection;

            $classroom_obj = new Classroom($db, $user);
            $list_obj = new Collection($db, $user);
            $classrooms = $classroom_obj->getClassrooms();
            if (!empty($classrooms)) {
                echo '<hr class="mdc-list-divider">
            <h6 class="mdc-list-group__subheader mdc-typography--subtitle1">' . __("Classi") . '</h6>
            <div id="left_sidebar_classrooms">';
                foreach ($classrooms as $classroom) {
                    $classroom = (object)$classroom;
                    $classroom_users = json_decode($classroom->users);
                    if (is_array($classroom_users) and !in_array($user->getId(), $classroom_users)) {
                        continue;
                    }
                    echo '<a id="ls_classroom_' . $classroom->code . '" class="mdc-list-item ' . (($current_page == "classroom" and get('view') == $classroom->code) ?
                            'mdc-list-item--activated" aria-current="page' : '" href="classroom?view=' . $classroom->code . '') . '">
                            <i class="mdi-outline-class mdc-list-item__graphic"></i>
                            <span class="mdc-list-item__text mdc-typography--subtitle2">' . $classroom->name . '</span>
                          </a>';
                    $lists = $list_obj->getLists($classroom->id);
                    if (!empty($lists)) {
                        echo '<hr class="mdc-list-divider mdc-menu-classroom-list">
            <h6 class="mdc-list-group__subheader mdc-typography--subtitle1 mdc-menu-classroom-list">' . __("Liste") . '</h6>
            <div id="left_sidebar_lists">';
                        foreach ($lists as $list) {
                            $list = (object)$list;
                            echo '<a id="ls_list_' . $list->code . '" class="mdc-menu-classroom-list mdc-list-item ' . (($current_page == "list" and get('view') == $list->code) ?
                                    'mdc-list-item--activated" aria-current="page' : '" href="list?view=' . $list->code . '') . '">
                            <i class="mdi-outline-format_list_numbered mdc-list-item__graphic"></i>
                            <span class="mdc-list-item__text mdc-typography--subtitle2">' . $list->name . '</span>
                          </a>';
                        }
                        echo '</div>';
                    }
                }
                echo '</div>';
            }


            ?>
        </nav>
    </div>
</aside>