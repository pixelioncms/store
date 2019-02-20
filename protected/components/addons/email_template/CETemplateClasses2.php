<?php

# Websun template parser by Mikhail Serov (1234ru at gmail.com)
# http://webew.ru/articles/3609.webew
# 2010-2016 (c)



/*
  0.1.93 - recursively parsing "else" part of if as well
 */

class CETemplateClasses {

    public $vars;
    public $templates_root_dir; // templates_root_dir указывать без закрывающего слэша!
    public $templates_current_dir;
    public $TIMES;
    public $no_global_vars;
    private $profiling;
    private $predecessor; // объект шаблонизатора верхнего уровня, из которого делался вызов текущего

    function __construct($options) {

        // $options - ассоциативный массив с ключами:
        // - data - данные
        // - templates_root - корневой каталог шаблонизатора
        // - predecessor - объект-родитель (из которого вызывается дочерний)
        // - allowed_extensions - список разрешенных расширений шаблонов (по умолчанию: *.tpl, *.html, *.css, *.js) 
        // - no_global_vars - разрешать ли использовать в шаблонах переменные глобальной области видимости
        // - profiling - включать ли измерения скорости (пока не до конца отлажено)

        $this->vars = $options['data'];

        if (isset($options['templates_root']) AND $options['templates_root']) // корневой каталог шаблонов
            $this->templates_root_dir = $this->template_real_path($options['templates_root']);
        else { // если не указан, то принимается каталог файла, в котором вызван websun
            // С 0.50 - НЕ getcwd()! Т.к. текущий каталог - тот, откуда он запускается,
            // $this->templates_root_dir = getcwd();
            foreach (debug_backtrace() as $trace) {
                if (preg_match('/^websun_parse_template/', $trace['function'])) {
                    $this->templates_root_dir = dirname($trace['file']);
                    break;
                }
            }

            if (!$this->templates_root_dir) {
                foreach (debug_backtrace() as $trace) {
                    if ($trace['class'] == 'websun') {
                        $this->templates_root_dir = dirname($trace['file']);
                        break;
                    }
                }
            }
        }
        $this->templates_current_dir = $this->templates_root_dir . '/';

        $this->predecessor = (isset($options['predecessor']) ? $options['predecessor'] : FALSE);

        $this->allowed_extensions = (isset($options['allowed_extensions'])) ? $options['allowed_extensions'] : array('tpl', 'html', 'css', 'js', 'xml');

        $this->no_global_vars = (isset($options['no_global_vars']) ? $options['no_global_vars'] : FALSE);

        $this->profiling = (isset($options['profiling']) ? $options['profiling'] : FALSE);
    }

    function parse_template($template) {
        if ($this->profiling)
            $start = microtime(1);

        $template = preg_replace('/ \\/\* (.*?) \*\\/ /sx', '', $template); /*         * ПЕРЕПИСАТЬ ПО JEFFREY FRIEDL'У !!!* */

        $template = str_replace('\\\\', "\x01", $template);  // убираем двойные слэши
        $template = str_replace('\*', "\x02", $template); // и экранированные звездочки
        // С 0.1.51 отключили
        // $template = preg_replace_callback( // дописывающие модули
        // 	'/
        // 	{\*
        // 	&(\w+)
        // 	(?P<args>\([^*]*\))?
        // 	\*}
        // 	/x', 
        // 	array($this, 'addvars'), 
        // 	$template
        // 	);

        $template = $this->find_and_parse_cycle($template);

        $template = $this->find_and_parse_if($template);

        $template = preg_replace_callback(// переменные, шаблоны и функци
                '/
				{\*
				(.*?)
				\*}
				/x',
                /* подумать о том, чтобы вместо (.*?) 
                  использовать жадное, но более строгое
                  (
                  (?:
                  [^*]*+
                  |
                  \*(?!})
                  )+
                  )
                 */ array($this, 'parse_vars_templates_functions'), $template
        );

        $template = str_replace("\x01", '\\\\', $template); // возвращаем двойные слэши обратно
        $template = str_replace("\x02", '*', $template); // а звездочки - уже без экранирования 

        if ($this->profiling AND ! $this->predecessor) {
            $this->TIMES['_TOTAL'] = round(microtime(1) - $start, 4) . " s";
            // ksort($this->TIMES);
            echo '<pre>' . print_r($this->TIMES, 1) . '</pre>';
        }

        return $template;
    }

    // // дописывание массива переменных из шаблона
    // // (хак для Бурцева)
    // 0.1.51 - убрали; все равно Бурцев не пользуется
    // function addvars($matches) {
    // 	// if ($this->profiling) 
    // 	// 	// $start = microtime(1);
    // 	// 
    // 	// $module_name = 'module_'.$matches[1];
    // 	// # ДОБАВИТЬ КЛАССЫ ПОТОМ
    // 	// $args = (isset($matches['args'])) 
    // 	// 	// ? explode(',', mb_substr($matches['args'], 1, -1) ) // убираем скобки
    // 	// 	// : array();
    // 	// $this->vars = array_merge(
    // 	// 	// $this->vars, 
    // 	// 	// call_user_func_array($module_name, $args)
    // 	// 	// ); // call_user_func_array быстрее, чем call_user_func
    // 	// 
    // 	// if ($this->profiling) 
    // 	// 	// $this->write_time(__FUNCTION__, $start, microtime(1));
    // 	// 
    // 	// return TRUE;
    // }

    function var_value($string) {

        if ($this->profiling)
            $start = microtime(1);

        if (mb_substr($string, 0, 1) == '=') { # константа
            $C = mb_substr($string, 1);
            $out = (defined($C)) ? constant($C) : '';
        }

        // можно делать if'ы:
        // {*var_1|var_2|"строка"|134*}
        // сработает первая часть, которая TRUE
        elseif (mb_strpos($string, '|') !== FALSE) {
            $f = __FUNCTION__;

            foreach (explode('|', $string) as $str) {
                // останавливаемся при первом же TRUE
                if ($val = $this->$f($str))
                    break;
            }

            $out = $val;
        }

        elseif (# скалярная величина
                mb_substr($string, 0, 1) == '"'
                AND
                mb_substr($string, -1) == '"'
        )
            $out = mb_substr($string, 1, -1);

        elseif (is_numeric($string))
            $out = $string;

        else {

            if (mb_substr($string, 0, 1) == '$') {
                // глобальная переменная
                if (!$this->no_global_vars) {
                    $string = mb_substr($string, 1);
                    $value = $GLOBALS;
                } else
                    $value = '';
            } else
                $value = $this->vars;

            // допустимы выражения типа {*var^COUNT*}
            // (вернет count($var)) )
            if (mb_substr($string, -6) == '^COUNT') {
                $string = mb_substr($string, 0, -6);
                $return_mode = 'count';
            } else
                $return_mode = FALSE; // default

            $rawkeys = explode('.', $string);
            $keys = array();
            foreach ($rawkeys as $v) {
                if ($v !== '')
                    $keys[] = $v;
            }
            // array_filter() использовать не получается, 
            // т.к. числовой индекс 0 она тоже считает за FALSE и убирает
            // поэтому нужно сравнение с учетом типа
            // пустая строка указывает на корневой массив
            foreach ($keys as $k) {
                if (is_array($value) AND isset($value[$k]))
                    $value = $value[$k];

                elseif (is_object($value) AND property_exists($value, $k))
                    $value = $value->$k;

                else {
                    $value = NULL;
                    break;
                }
            }

            // в зависимости от $return_mode действуем по-разному:
            $out = (!$return_mode)
                    // возвращаем значение переменной (обычный случай)
                    ? $value

                    // возвращаем число элементов в массиве
                    : ( is_array($value) ? count($value) : FALSE )

            ;
        }

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $out;
    }

    function find_and_parse_cycle($template) {
        if ($this->profiling)
            $start = microtime(1);
        // пришлось делать специальную функцию, чтобы реализовать рекурсию
        $out = preg_replace_callback(
                '/
			{ %\* ([^*]*) \* }
			( (?: [^{]* | (?R) | . )*? )
			{ (?: % | \*\1\*% ) }
			/sx', array($this, 'parse_cycle'), $template
        );
        // инвертный класс - [^{]* - для быстрого совпадения
        // непрерывных цепочек статистически наиболее часто встречающихся символов 

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $out;
    }

    function parse_cycle($matches) {

        if ($this->profiling)
            $start = microtime(1);

        $array_name = $matches[1];
        $array = $this->var_value($array_name);
        $array_name_quoted = preg_quote($array_name);

        if (!is_array($array))
            return FALSE;

        $parsed = '';

        $dot = ( $array_name != '' AND $array_name != '$' ) ? '.' : '';

        $i = 0;
        $n = 1;
        foreach ($array as $key => $value) {
            $parsed .= preg_replace(
                    array(// массив поиска
                "/(?<=[*=<>|&%])\s*$array_name_quoted\:\^KEY\b/",
                "/(?<=[*=<>|&%])\s*$array_name_quoted\:\^i\b/",
                "/(?<=[*=<>|&%])\s*$array_name_quoted\:\^N\b/",
                "/(?<=[*=<>|&%])\s*$array_name_quoted\:/"
                    ), array(// массив замены
                '"' . $key . '"', // preg_quote для ключей нельзя, 
                '"' . $i . '"',
                '"' . $n . '"',
                $array_name . $dot . $key . '.' // т.к. в них бывает удобно
                    ), // хранить некоторые данные,
                    $matches[2]                           // а preg_quote слишком многое экранирует
            );
            $i++;
            $n++;
        }
        $parsed = $this->find_and_parse_cycle($parsed);

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $parsed;
    }

    function find_and_parse_if($template) {

        if ($this->profiling)
            $start = microtime(1);

        $out = preg_replace_callback(
                '/
				{ (\if\!?) \*([^*]*)\* }      # открывающее условие
				( (?: [^{]* | (?R) | . )*? ) # 
				(?:
				  {else} 
				  ( (?: [^{]* | (?R) | . )*? ) 
				)? #  
				{endif}     # закрывающее условие
				/sx', array($this, 'parse_if'), $template
        );
        
        // old
        //$out = preg_replace_callback(
       //         '/
	//			{ (\?\!?) \*([^*]*)\* }      # открывающее условие
	//			( (?: [^{]* | (?R) | . )*? ) # 
	//			(?:
	//			  { \?\! } 
	//			  ( (?: [^{]* | (?R) | . )*? ) 
	//			)? #  
	//			{ (?: \?  | \*\2\*\1 ) }     # закрывающее условие
	//			/sx', array($this, 'parse_if'), $template
      //  );
        // пояснения к рег. выражению см. в find_and_parse_cycle

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $out;
    }

    function parse_if($matches) {
        # 1 - ? или ?!
        # 2 - условие
        # 3 - при положительном результате 
        # 4 - при отрицательном результате (если указано)

        if ($this->profiling)
            $start = microtime(1);

        $final_check = FALSE;

        $separator = (strpos($matches[2], '&')) ? '&'  // "AND"
                : '|'; // "OR"
        $parts = explode($separator, $matches[2]);
        $parts = array_map('trim', $parts); // убираем пробелы по краям

        $checks = array();

        foreach ($parts as $p)
            $checks[] = $this->check_if_condition_part($p);

        if ($separator == '|') // режим "OR" 
            $final_check = in_array(TRUE, $checks);
        else // режим "AND"
            $final_check = !in_array(FALSE, $checks);

        $result = ($matches[1] == 'if') ? $final_check : !$final_check; //change "?" to "if"

        $parsed_if = ($result) ? $this->find_and_parse_if($matches[3]) : ( (isset($matches[4])) ? $this->find_and_parse_if($matches[4]) : '' );

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $parsed_if;
    }

    function check_if_condition_part($str) {

        if ($this->profiling)
            $start = microtime(1);

        preg_match(
                '/^
				   (  
				   	"[^"*]+"       # строковый литерал
				   	
				   	|              # или
				   	
				   	=?[^*<>="]*+   # имя константы или переменной
				   )  
				   
					(?: # если есть сравнение с чем-то:
						
						([=<>])  # знак сравнения 
						
						\s*
						
						(.*)     # то, с чем сравнивают
					)?
					
					$
				/x', $str, $matches
        );

        $left = $this->var_value(trim($matches[1]));

        if (!isset($matches[2]))
            $check = ($left == TRUE);

        else {

            $right = (isset($matches[3])) ? $this->var_value($matches[3]) : FALSE;

            switch ($matches[2]) {
                case '=': $check = ($left == $right);
                    break;
                case '>': $check = ($left > $right);
                    break;
                case '<': $check = ($left < $right);
                    break;
                default: $check = ($left == TRUE);
            }
        }

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $check;
    }

    function parse_vars_templates_functions($matches) {
        if ($this->profiling)
            $start = microtime(1);

        // тут обрабатываем сразу всё - и переменные, и шаблоны, и функции
        $work = $matches[1];
        $work = trim($work); // убираем пробелы по краям

        if (mb_substr($work, 0, 1) == '@') { // функции {* @name(arg1,arg2) | template *}
            $p = '/
				^
				( [^(]++ ) # 1 - имя функции
				(?: \( ( (?: [^)"]++ | "[^"]++" )* ) \) \s* ) # 2 - аргументы
				(?: \| \s* (.++) )? # 3 - это уже до конца
				$
				/x';
            // выражение неплохо оптимизировано: захватывающие квантификаторы 
            // ("+" - не возвращаться назад) - и пр.,
            // однако сам по себе вызов функций является довольно короткой строкой,
            // так что хорошо работать будет любое выражение

            if (preg_match($p, mb_substr($work, 1), $m)) {
                $function_name = $this->get_var_or_string($m[1]);

                // С версии 0.1.51 - проверяем по специальному списку
                // if (PHP_VERSION_ID / 100 > 506) { // это включим позже, получим PHP 5.6
                // $list = - тут ссылка на константу из namespace
                // else
                global $WEBSUN_ALLOWED_CALLBACKS;
                $list = $WEBSUN_ALLOWED_CALLBACKS;
                // }

                if ($list and in_array($function_name, $list))
                    $allowed = TRUE;
                else {
                    $allowed = FALSE;
                    trigger_error("<b>$function_name()</b> is not in the list of allowed callbacks.", E_USER_WARNING);
                }

                if ($allowed) {

                    $args = array();

                    if (isset($m[2])) {
                        preg_match_all('
							/ 
								# выражение составлено так, что в каждой подмаске
								# должен совпасть хотя бы один символ 
								
								[^\s,"{\[]++ # переменные, константы или числа (ведущий пробел тоже исключаем) 
								|
								"[^"]*+" # строки
								|
								( \[ (?: [^\[\]]*+ | (?1) )* \] ) # JSON: обычные массивы (с числовыми ключами)
								|
								( { (?: [^{}]*+ | (?2) )* } ) # JSON: ассоциативные массивы
							/x', $m[2], $tmp
                        );

                        if ($tmp)
                            $args = array_map(array($this, 'get_var_or_string'), $tmp[0]);

                        unset($tmp);
                    }

                    $subvars = call_user_func_array($function_name, $args);

                    if (isset($m[3]))  // передали указание на шаблон
                        $html = $this->call_template($m[3], $subvars);
                    else
                        $html = $subvars; // шаблон не указан => функция возвращает строку
                } else
                    $html = '';
            } else
                $html = ''; // вызов функции сделан некорректно
        }
        elseif (mb_substr($work, 0, 1) == '+') {
            // шаблон - {* +*vars_var*|*tpl_var* *}
            // переменная как шаблон - {* +*var* | >*template_inside* *}
            $html = '';
            $parts = preg_split(
                    '/(?<=[\*\s])\|(?=[\*\s])/', // вертикальная черта
                    mb_substr($work, 1) // должна ловиться только как разделитель
                    // между переменной и шаблоном, но не должна ловиться 
                    // как разделитель внутри нотации переменой или шаблона
                    // (например, {* + *var1|$GLOBAL* | *tpl1|tpl2* *}
            );
            $parts = array_map('trim', $parts); // убираем пробелы по краям
            if (!isset($parts[1])) {  // если нет разделителя (|) - значит, 
                // передали только имя шаблона +template
                $html = $this->call_template($parts[0], $this->vars);
            } else {
                $varname_string = mb_substr($parts[0], 1, -1); // убираем звездочки
                // {* +*vars* | шаблон *} - простая передача переменной шаблону
                // {* +*?vars* | шаблон *} - подключение шаблона только в случае, если vars == TRUE
                // {* +*%vars* | шаблон *} - подключение шаблона не для самого vars, а для каждого его дочернего элемента 
                $indicator = mb_substr($varname_string, 0, 1);
                if ($indicator == '?') {
                    if ($subvars = $this->var_value(mb_substr($varname_string, 1)))
                    // 0.1.27 $html = $this->parse_child_template($tplname, $subvars);
                        $html = $this->call_template($parts[1], $subvars);
                }
                elseif ($indicator == '%') {
                    if ($subvars = $this->var_value(mb_substr($varname_string, 1))) {
                        foreach ($subvars as $row) {
                            // 0.1.27 $html .= $this->parse_child_template($tplname, $row);
                            $html .= $this->call_template($parts[1], $row);
                        }
                    }
                } else {
                    $subvars = $this->var_value($varname_string);
                    // 0.1.27 $html = $this->parse_child_template($tplname, $subvars);
                    $html = $this->call_template($parts[1], $subvars);
                }
            }
        } else
            $html = $this->var_value($work); // переменная (+ константы - тут же)

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $html;
    }

    function call_template($template_notation, $vars) {
        if ($this->profiling)
            $start = microtime(1);

        // $template_notation - либо путь к шаблону,
        // либо переменная, содержащая путь к шаблону,
        // либо шаблон прямо в переменной - если >*var*
        $c = __CLASS__; // нужен объект этого же класса - делаем
        $subobject = new $c(array(
            'data' => $vars,
            'templates_root' => $this->templates_root_dir,
            'predecessor' => $this,
            'no_global_vars' => $this->no_global_vars,
            'profiling' => $this->profiling,
        ));

        $template_notation = trim($template_notation);

        if (mb_substr($template_notation, 0, 1) == '>') {
            // шаблон прямо в переменной
            $v = mb_substr($template_notation, 1);
            $subtemplate = $this->get_var_or_string($v);
            $subobject->templates_current_dir = $this->templates_current_dir;
        } else {
            $path = $this->get_var_or_string($template_notation);
            $subobject->templates_current_dir = pathinfo($this->template_real_path($path), PATHINFO_DIRNAME) . '/';
            $subtemplate = $this->get_template($path);
        }

        $result = $subobject->parse_template($subtemplate);

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $result;
    }

    function get_var_or_string($str) {
        // используется, в основном, 
        // для получения имён шаблонов и функций

        $str = trim($str);

        if ($this->profiling)
            $start = microtime(1);

        $first_char = mb_substr($str, 0, 1);

        if ($first_char == '*') // если вокруг есть звездочки - значит, перменная или константа
            $out = $this->var_value(mb_substr($str, 1, -1));

        elseif ($first_char == '[' OR $first_char == '{') // JSON
            $out = json_decode($str, TRUE);
        else // нет звездочек - значит, скалярный литерал
            $out = ($first_char == '"') ? mb_substr($str, 1, -1) // в двойных кавычках - строка
                    : $str;  // число

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $out;
    }

    function get_template($tpl) {
        if ($this->profiling)
            $start = microtime(1);

        if (!$tpl)
            return FALSE;

        $tpl_real_path = $this->template_real_path($tpl);

        $ext = pathinfo($tpl_real_path, PATHINFO_EXTENSION);

        if (!in_array($ext, $this->allowed_extensions)) {
            trigger_error(
                    "Template's <b>$tpl_real_path</b> extension is not in the allowed list ("
                    . implode(", ", $this->allowed_extensions) . "). 
				 Check <b>allowed_extensions</b> option.", E_USER_WARNING
            );
            return '';
        }

        // return rtrim(file_get_contents($tpl_real_path), "\r\n");
        // (убираем перенос строки, присутствующий в конце любого файла)
        $out = preg_replace(
                '/\r?\n$/', '', file_get_contents($tpl_real_path)
        );

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $out;
    }

    function template_real_path($tpl) {
        // функция определяет реальный путь к шаблону в файловой системе
        // первый символ пути к шаблону определяет тип пути 
        // если в начале адреса есть / - интерпретируем как абсолютный путь ФС
        // если второй символ пути - двоеточие (путь вида C:/ - Windows) - 
        // также интепретируем как абсолютный путь ФС
        // если есть ^ - отталкиваемся от $templates_root_dir
        // если $ - от $_SERVER[DOCUMENT_ROOT]
        // во всех остальных случаях отталкиваемся от каталога текущего шаблона - templates_current_dir
        if ($this->profiling)
            $start = microtime(1);

        $dir_indicator = mb_substr($tpl, 0, 1);

        $adjust_tpl_path = TRUE;

        if ($dir_indicator == '^')
            $dir = $this->templates_root_dir;
        elseif ($dir_indicator == '$')
            $dir = $_SERVER['DOCUMENT_ROOT'];
        elseif ($dir_indicator == '/') {
            $dir = '';
            $adjust_tpl_path = FALSE;
        } // абсолютный путь для ФС 
        else {
            if (mb_substr($tpl, 1, 1) == ':') // Windows - указан абсолютный путь - вида С:/...
                $dir = '';
            else
                $dir = $this->templates_current_dir;

            $adjust_tpl_path = FALSE; // в обоих случаях строку к пути менять не надо
        }

        if ($adjust_tpl_path)
            $tpl = mb_substr($tpl, 1);

        $tpl_real_path = $dir . $tpl;

        if ($this->profiling)
            $this->write_time(__FUNCTION__, $start, microtime(1));

        return $tpl_real_path;
    }

    function write_time($method, $start, $end) {

        //echo ($this->predecessor) . '<br>';


        if (!$this->predecessor)
            $time = &$this->TIMES;
        else
            $time = &$this->predecessor->TIMES;

        if (!isset($time[$method]))
            $time[$method] = array(
                'n' => 0,
                'last' => 0,
                'total' => 0,
                'avg' => 0
            );

        $time[$method]['n'] += 1;
        $time[$method]['last'] = round($end - $start, 4);
        $time[$method]['total'] += $time[$method]['last'];
        $time[$method]['avg'] = round($time[$method]['total'] / $time[$method]['n'], 4);
    }

}

?>
