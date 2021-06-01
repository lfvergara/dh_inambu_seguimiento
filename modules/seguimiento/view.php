<?php


class SeguimientoView extends View {
	function consultar() {
		$gui = file_get_contents("static/modules/seguimiento/consultar.html");
		$template = $this->render_template($gui);
		print $template;
	}

	function seguimiento($estado_collection) {
		$gui = file_get_contents("static/modules/seguimiento/seguimiento.html");
		$gui_lst_estado = file_get_contents("static/modules/seguimiento/lst_seguimiento.html");
		$gui_alerta = file_get_contents("static/modules/seguimiento/alerta.html");
		
		$gui_lst_estado = $this->render_regex_dict('LST_SEGUIMIENTO', $gui_lst_estado, $estado_collection);
		if (empty($estado_collection)) {
			$gui = str_replace('{alerta}', $gui_alerta, $gui);
		} else {
			$gui = str_replace('{alerta}', "", $gui);
		}
		
		$render = str_replace('{lst_seguimiento}', $gui_lst_estado, $gui);
		$template = $this->render_template($render);
		print $template;
	}	
}
?>