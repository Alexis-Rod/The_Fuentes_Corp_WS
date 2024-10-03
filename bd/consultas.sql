/*esta es la consulta del inner join para tener toda la informacion de la contruccion de la presion para enviar*/
SELECT * FROM `presiones` 
JOIN ciudadesObras 
ON ciudadesObras.ciudadesObras_id = presiones.presiones_cuidad 
JOIN obras 
ON obras.obras_id = presiones.presiones_obra 
JOIN requisiciones 
ON requisiciones.requisicion_presionID = presiones.presiones_id 
JOIN itemrequisicion 
ON itemrequisicion.itemRequisicion_idReq = requisiciones.requisicion_id;

SELECT `presiones_clave`,`requisicion_Numero`,`proveedor_nombre`,`itemRequisicion_producto`,`requisicion_total`,`requisicion_observaciones`,`requisicion_formaPago` FROM `presiones`
JOIN requisiciones
ON presiones.presiones_id = requisiciones.requisicion_idPresion
JOIN itemrequisicion
on requisiciones.requisicion_id = itemrequisicion.itemRequisicion_idReq
JOIN provedores
on requisiciones.requisicion_receptorID = provedores.proveedor_id
WHERE`presiones_semana`= 29 AND `presiones_dia` = 'LUNES' AND `presiones_obra` = 1