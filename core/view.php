<?php


abstract class View {

    function render_login() {
        $plantilla = file_get_contents("static/login.html");
        $dict = array("{app_nombre}"=>APP_TITTLE,
                      "{app_version}"=>APP_VERSION,
                      "{url_app}"=>URL_APP,
                      "{url_static}"=>URL_STATIC);
        return $this->render($dict, $plantilla);
    }

    function render_template($contenido) {
        $dict = array("{app_nombre}"=>APP_TITTLE,
                      "{app_version}"=>APP_VERSION,
                      "{url_static}"=>URL_STATIC,
                      "{app_footer}"=>APP_TITTLE . " " . date("Y"),
                      "{contenido}"=>$contenido);

        $plantilla = file_get_contents(TEMPLATE);
        $plantilla = $this->render($dict, $plantilla);
        $plantilla = str_replace("{url_app}", URL_APP, $plantilla);
        $plantilla = str_replace("{url_static}", URL_STATIC, $plantilla);
        return $plantilla;
    }

    function render($dict, $html) {
        $render = str_replace(array_keys($dict), array_values($dict), $html);
        return $render;
    }

    function get_regex($tag, $html) {
        $pcre_limit = ini_set("pcre.recursion_limit", 10000);
        $regex = "/<!--$tag-->(.|\n){1,}<!--$tag-->/";
        preg_match($regex, $html, $coincidencias);
        ini_set("pcre.recursion_limit", $pcre_limit);
        return $coincidencias[0];
    }

    function render_regex($tag, $base, $coleccion) {
        $render = '';
        $codigo = $this->get_regex($tag, $base);
        $coleccion = $this->set_collection_dict($coleccion);
        foreach($coleccion as $dict) {
            $render .= $this->render($dict, $codigo);
        }
        $render_final = str_replace($codigo, $render, $base);
        return $render_final;
    }

    function render_regex_dict($tag, $base, $coleccion) {
        $render = '';
        $codigo = $this->get_regex($tag, $base);
        if (!empty($coleccion)) {
            foreach($coleccion as $dict) {
                $render .= $this->render($dict, $codigo);
            }
        } else {
            $render = "<center><strong>No hay registros para mostrar!</strong></center>";
        }
        
        $base = str_replace($codigo, $render, $base);
        return $base;
    }

    function set_dict($obj) {
        $new_dict = array();
        foreach($obj as $clave=>$valor) {
            if (is_object($valor)) {
                $new_dict = array_merge($new_dict, $this->set_dict($valor));
            } else {
                $name_object = strtolower(get_class($obj));
                $new_dict["{{$name_object}-{$clave}}"] = $valor;
            }
        }
        return $new_dict;        
    }

    function set_dict_array($array) {
        $new_dict = array();
        foreach($array as $clave=>$valor) $new_dict["{{$clave}}"] = $valor;
        return $new_dict;        
    }

    function set_collection_dict($collection) {
        $new_array = array();
        foreach($collection as $obj) $new_array[] = $this->set_dict($obj);
        return $new_array;
    }

    function order_collection_dict($collection, $column, $criterion) {
        $array_temp = array();
        foreach ($collection as $array) {
            $array_temp[] = $array["{{$column}}"];
        }
        array_multisort($array_temp, $criterion, $collection);
        return $collection;
    }

    function order_collection_array($collection, $column, $criterion) {
        $array_temp = array();
        foreach ($collection as $array) {
            $array_temp[] = $array["{$column}"];
        }
        array_multisort($array_temp, $criterion, $collection);
        return $collection;
    }

    function order_collection_objects($collection, $column, $criterion) {
        $array_temp = array();
        foreach ($collection as $array) {
            $array_temp[] = $array->$column;
        }
        array_multisort($array_temp, $criterion, $collection);
        return $collection;
    }

    function descomponer_fecha($fecha='') {
        $dia = date('d');
        $dias_semana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
        $dia_semana = date('w');
        $dia_semana = $dias_semana[$dia_semana];
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $mes = date('m');
        $mes = $mes - 1;
        $mes = $meses[$mes];
        $anio = date('Y');

        $array_fecha = array(
            "{fecha_dia}" => $dia,
            "{fecha_dia_semana}" => $dia_semana,
            "{fecha_mes}" => $mes,
            "{fecha_anio}" => $anio);

        return $array_fecha;
    }

    function descomponer_periodo($periodo='') {
        $anio = substr($periodo, 0, 4);
        $mes_valor = substr($periodo, 5, 1);
        $mes = substr($periodo, 5, 1);
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $mes = $mes - 1;
        $mes = $meses[$mes];
        $array_fecha = array(
            "{fecha_mes}" => $mes,
            "{fecha_anio}" => $anio,
            "{mes}" => $mes_valor);

        return $array_fecha;
    }

    function reacomodar_fecha($fecha) {
        $fecha_descompuesta = explode("-", $fecha);
        $anio = $fecha_descompuesta[0];
        $mes = $fecha_descompuesta[1];
        $dia = $fecha_descompuesta[2];
        $nueva_fecha = "{$dia}/{$mes}/{$anio}";
        return $nueva_fecha;
    }
}
?>