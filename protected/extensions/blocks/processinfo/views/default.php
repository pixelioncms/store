        <div class="tab_content">
            <?php
            $_disabled = @ini_get('disable_functions') ? explode(',', @ini_get('disable_functions')) : array();
            $_shellExecAvail = in_array('shell_exec', $_disabled) ? false : true;

            if ($_shellExecAvail) {
                if (strpos(strtolower(PHP_OS), 'win') === 0) {
                    $tasks = @shell_exec("tasklist");
                    //echo @shell_exec("printenv");
                    //die();

                    $tasks = str_replace(" ", "&nbsp;", iconv('CP866', 'UTF-8', $tasks));
                } else if (strtolower(PHP_OS) == 'darwin') {
                    $tasks = @shell_exec("top -l 1");
                    $tasks = str_replace(" ", "&nbsp;", $tasks);
                } else {
                    $tasks = @shell_exec("top -b -n 1");
                    $tasks = str_replace(" ", "&nbsp;", $tasks);
                }
            } else {
                $tasks = '';
            }

            if (!$tasks) {
                $tasks = 'Не возможно получить информацию о процессе';
            } else {
                $tasks = "<pre>" . $tasks . "</pre>";
            }
            echo $tasks;
            ?>
        </div>