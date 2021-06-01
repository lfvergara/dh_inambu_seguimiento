<?php
require_once "modules/seguimiento/view.php";


class SeguimientoController {

	function __construct() {
		$this->view = new SeguimientoView();
	}

	function consultar() {
		$this->view->consultar();

	}

	function buscar() {
		$numero_guia = filter_input(INPUT_POST, "numero_guia");
		$select = "date_format(src.fecha, '%d/%m/%Y') AS FECHA, CONCAT(LPAD(rc.punto_venta, 4, 0), '-', LPAD(rc.numero, 8, 0)) AS REMITO,
				   erc.denominacion AS ESTADO, CASE WHEN erc.estadoremitocarga_id = 1 THEN CONCAT(p.denominacion, ' - ', l.denominacion) 
				   WHEN erc.estadoremitocarga_id = 2 THEN '-' WHEN erc.estadoremitocarga_id = 3 THEN (SELECT CONCAT(pa.denominacion, ' - ', la.denominacion) 
				   FROM localidad la INNER JOIN provincia pa ON la.provincia = pa.provincia_id WHERE la.localidad_id = rc.localidad) ELSE '-' END AS UBICACION,
				   CASE WHEN erc.estadoremitocarga_id = 1 THEN 'building' WHEN erc.estadoremitocarga_id = 2 THEN 'truck' 
				   WHEN erc.estadoremitocarga_id = 3 THEN 'check-square' ELSE 'question-circle' END AS ICON";
		$from = "remitocarga rc INNER JOIN seguimientoremitocarga src ON rc.remitocarga_id = src.remitocarga_id INNER JOIN
				 usuario u ON src.usuario_id = u.usuario_id INNER JOIN usuariodetalle ud ON u.usuariodetalle = ud.usuariodetalle_id INNER JOIN 
				 oficina o ON ud.oficina = o.oficina_id INNER JOIN localidad l ON o.localidad = l.localidad_id INNER JOIN
				 provincia p ON l.provincia = p.provincia_id INNER JOIN estadoremitocarga erc ON src.estadoremitocarga = erc.estadoremitocarga_id";
		$where = "CONCAT(LPAD(rc.punto_venta, 4, 0), '-', LPAD(rc.numero, 8, 0)) = '{$numero_guia}' ORDER BY src.seguimientoremitocarga_id ASC"; 
		$rst = CollectorCondition()->get('RemitoCarga', $where, 4, $from, $select);
		$this->view->seguimiento($rst);
	}
}
?>