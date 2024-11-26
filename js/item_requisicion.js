var url = "bd/crud_items_requisiciones.php";
var url2 = ".";

const appRequesition = new Vue({
    el: "#AppPresion",
    data: {
        itemsHoja: [],
        hojas: [],
        obras: [],
        obrasLista: [],
        NameUser: "",
        producto: "",
        unidad: 0,
        cantidad: 0,
        precio: 0,
        IVA: 0,
        subTotal: 0,
        AuxTotal: 0,
        Retenciones: 0,
        bandFlete: false,
        bandeFisica: false,
        bandResico: false,
        HtmlRet: "",
        strFlete: "",
        strFisca: "",
        strResico: "",
        id: 0,
        clve: "",
        Numero_Req: "",
    },
    methods: {
        /**
             * Función para listar los items de una hoja específica.
             * 
             * Esta función realiza una petición POST a la URL especificada para obtener los items
             * asociados a la hoja con el id proporcionado.
             * 
             * @param {number} id_Hoja - El id de la hoja para la cual se desean obtener los items.
         */
        listarItems: function (id_Hoja) {
            /**
             * Realiza una petición POST a la URL para obtener los items de la hoja.
             * 
             * @param {string} url - La URL a la cual se realizará la petición.
             * @param {object} data - El objeto que contiene los datos a enviar en la petición.
             * @param {number} data.accion - La acción a realizar (en este caso, 1 para obtener los items).
             * @param {number} data.id_Hoja - El id de la hoja para la cual se desean obtener los items.
             */
            axios.post(url, { accion: 1, id_Hoja: id_Hoja }).then(response => {
                /**
                 * Asigna la respuesta de la petición a la variable itemsHoja.
                 * 
                 * @type {array} response.data - La respuesta de la petición, que contiene los items de la hoja.
                 */
                this.itemsHoja = response.data;
                console.log(this.itemsHoja);
            });
        },
        /**
            * Función para consultar la información del usuario activo en la sesión de base de datos.
            * 
            * Esta función realiza una petición POST a la URL especificada para obtener la información del usuario
            * con el id proporcionado. La respuesta se almacena en la variable 'users' y se extrae el nombre del usuario.
            * 
            * @param {number} user_id - El id del usuario para consultar la información.
        */
        consultarUsuario: function (user_id) {
            /**
             * Realiza una petición POST a la URL para obtener la información del usuario.
             * 
             * @param {string} url - La URL a la cual se realizará la petición.
             * @param {object} data - El objeto que contiene los datos a enviar en la petición.
             * @param {number} data.accion - La acción a realizar (en este caso, 2 para obtener la información del usuario).
             * @param {number} data.id_user - El id del usuario para consultar la información.
             */
            axios.post(url, { accion: 2, id_user: user_id }).then(response => {
                /**
                 * Asigna la respuesta de la petición a la variable 'users'.
                 * 
                 * @type {array} response.data - La respuesta de la petición, que contiene la información del usuario.
                 */
                this.users = response.data;
                /**
                 * Extrae el nombre del usuario de la respuesta y lo asigna a la variable 'NameUser'.
                 * 
                 * @type {string} this.users[0].user_name - El nombre del usuario.
                 */
                this.NameUser = this.users[0].user_name;
                console.log(this.users);
            });
        },
        /**
             * Función para editar un item existente en la requisición.
             * 
             * Esta función lanza una modal de SweetAlert 2 que muestra los valores actuales del item seleccionado.
             * El usuario puede editar estos valores según sea conveniente.
             * La edición de los valores depende de la forma de pago, que puede ser transferencia o efectivo.
             * 
             * @param {string} productoEdit - El nombre del producto del item a editar.
             * @param {number} cantidadEdit - La cantidad del item a editar.
             * @param {number} precioEdit - El precio unitario del item a editar.
             * @param {number} IVAEdit - El IVA del item a editar.
             * @param {boolean} banderaFlete - Indica si el item tiene retención por flete.
             * @param {boolean} banderaFisica - Indica si el item tiene retención por renta persona física.
             * @param {boolean} banderaResico - Indica si el item tiene retención por RESICO.
             * @param {number} ID - El ID del item a editar.
         */
        editItem: async function (productoEdit, cantidadEdit, precioEdit, IVAEdit, banderaFlete, banderaFisica, banderaResico, ID) {
            this.id = ID;
            this.subTotal = cantidadEdit * precioEdit;
            /**
                 * El siguiente if condiciona si la forma de pago es transferencia o efectivo.
                 * La logica indica que si hay el valor de un IVA presenta el pago es transferencia,
                 * pues no puede existir el valor de un IVA en un pago en efectivo y el caso contrario.
             */
            if (IVAEdit > 0) {
                /* 
                    Las siguientes condicionales validan las banderas de retenciones que estan activas 
                    para que se guarden en los datos de la estructura Vue segun el caso. 
                    También para que en el formulario de SweetAlert los check correspondiente a las 
                    retencion esten activos segun el caso.
                */
                if (banderaFlete == true) {
                    this.strFlete = "checked";
                }
                if (banderaFisica == true) {
                    this.strFisca = "checked";
                }
                if (banderaResico == true) {
                    this.strResico = "checked";
                }
                this.HtmlRet = `
                    <div class="col">
                        <hr />
                        <div class="row form-group mx-0 my-3">
                            <div class="col">
                                <label for="producto" class="form-label">Nombre del Producto</label>
                                <textarea class="form-control" id="producto" rows="3">`+ productoEdit + `</textarea>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col-4">
                                <label for="unidad" class="form-label">Unidad</label>
                                <select class="form-select" id="unidad" aria-label="Default select example">
                                    <option value="Selecciona Unidad" selected>Selecciona la Cantidad</option>
                                    <option value="DISEÑO">DISEÑO</option>
                                    <option value="PIEZAS">PIEZAS</option>
                                    <option value="BULTOS">BULTOS</option>
                                    <option value="PESOS">PESOS</option>
                                    <option value="LTS">LITROS</option>
                                    <option value="SER">SERVICIO</option>
                                    <option value="MES">MENSUALIDAD</option>
                                    <option value="RENTA">RENTA</option>
                                    <option value="CUBETA">CUBETA</option>
                                    <option value="TONELADAS">TONELADAS</option>
                                    <option value="METROS">METROS</option>
                                    <option value="METROS CUADRADOS">METROS CUADRADOS</option>
                                    <option value="METROS CUBICOS">METROS CUBICOS</option>
                                    <option value="KILOGRAMOS">KILOGRAMOS</option>
                                    <option value="VIAJES">VIAJES</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" min="0" class="form-control" id="cantidad" value="`+ cantidadEdit + `">
                            </div>
                            <div class="col-4">
                                <label for="precio" class="form-label">Precio Unitario</label>
                                <input type="number" min="0" class="form-control" id="precio" value="`+ precioEdit + `">
                            </div>
                        </div>
                        <hr />
                        <div class="row mx-0 my-3">
                            <div class="col">
                                <h5 class="text-start fw-bold">Activa las Requisiciones Necesarias</h5>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="RetFlete" `+ this.strFlete + `>
                                    <label class="form-check-label" for="RetFlete">Retencion por Flete (4%)</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="RetPersonaFIsica"  `+ this.strFisca + `>
                                    <label class="form-check-label" for="RetPersonaFIsica">Retencion por Renta Persona Fisica
                                        (10.67%)</label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="RetencionRESICO"  `+ this.strResico + `>
                                    <label class="form-check-label" for="RetencionRESICO">Retencion por RESICO (1.25%)</label>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </div>
                `;
            }
            else {
                this.HtmlRet = `
                    <div class="col">
                        <hr />
                        <div class="row form-group mx-0 my-3">
                            <div class="col">
                                <label for="producto" class="form-label">Nombre del Producto</label>
                                <textarea class="form-control" id="producto" rows="3">`+ productoEdit + `</textarea>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col-4">
                                <label for="unidad" class="form-label">Unidad</label>
                                <select class="form-select" id="unidad" aria-label="Default select example">
                                    <option value="Selecciona Unidad" selected>Selecciona la Cantidad</option>
                                    <option value="DISEÑO">DISEÑO</option>
                                    <option value="PIEZAS">PIEZAS</option>
                                    <option value="BULTOS">BULTOS</option>
                                    <option value="PESOS">PESOS</option>
                                    <option value="LTS">LITROS</option>
                                    <option value="SER">SERVICIO</option>
                                    <option value="MES">MENSUALIDAD</option>
                                    <option value="RENTA">RENTA</option>
                                    <option value="CUBETA">CUBETA</option>
                                    <option value="TONELADAS">TONELADAS</option>
                                    <option value="METROS">METROS</option>
                                    <option value="METROS CUADRADOS">METROS CUADRADOS</option>
                                    <option value="METROS CUBICOS">METROS CUBICOS</option>
                                    <option value="KILOGRAMOS">KILOGRAMOS</option>
                                    <option value="VIAJES">VIAJES</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" min="0" class="form-control" id="cantidad" value="`+ cantidadEdit + `">
                            </div>
                            <div class="col-4">
                                <label for="precio" class="form-label">Precio Unitario</label>
                                <input type="number" min="0" class="form-control" id="precio" value="`+ precioEdit + `">
                            </div>
                        </div>
                        <hr />
                    </div>
                `;
            }
            // Despliegue del modal de SweetAlert2 para edición de item
            const { value: formValues } = await Swal.fire({
                // Título del modal
                title: "Editar Item",
                // Contenido HTML del modal
                html: this.HtmlRet,
                // Deshabilitar el foco en el botón de confirmación
                focusConfirm: false,
                // Mostrar botón de cancelar
                showCancelButton: true,
                // Texto del botón de confirmación
                confirmButtonText: 'Modificar',
                // Color del botón de confirmación
                confirmButtonColor: '#0d6efd',
                // Color del botón de cancelar
                cancelButtonColor: '#dc3545',
                // Función de validación antes de confirmar
                preConfirm: () => {
                    // Verificar si el IVA es mayor a 0, indica si es pago por transferenci o efectivo
                    if (IVAEdit > 0) {
                        // Obtener los valores de los campos del modal
                        this.producto = document.getElementById("producto").value;
                        this.unidad = document.getElementById("unidad").value;
                        this.cantidad = document.getElementById("cantidad").value;
                        this.precio = document.getElementById("precio").value;
                        this.bandFlete = document.getElementById("RetFlete").checked;
                        this.bandeFisica = document.getElementById("RetPersonaFIsica").checked;
                        this.bandResico = document.getElementById("RetencionRESICO").checked;
                        // Validar si el campo producto no excede los 200 caracteres
                        if (this.producto.length > 200) {
                            Swal.showValidationMessage('El campo Producto no puede exceder los 200 caracteres.');
                            return false;
                        }
                        // Validar si todos los campos están completos
                        if (!this.producto || this.unidad === "Selecciona Unidad" || !this.cantidad || !this.precio) {
                            Swal.showValidationMessage('Por favor completa todos los campos');
                            return false;
                        }
                        return true;
                    }
                    else {
                        // Obtener los valores de los campos del modal
                        this.producto = document.getElementById("producto").value;
                        this.unidad = document.getElementById("unidad").value;
                        this.cantidad = document.getElementById("cantidad").value;
                        this.precio = document.getElementById("precio").value;
                        // Validar si el campo producto no excede los 200 caracteres
                        if (this.producto.length > 200) {
                            Swal.showValidationMessage('El campo Producto no puede exceder los 200 caracteres.');
                            return false;
                        }
                        // Validar si todos los campos están completos
                        if (!this.producto || this.unidad === "Selecciona Unidad" || !this.cantidad || !this.precio) {
                            Swal.showValidationMessage('Por favor completa todos los campos');
                            return false;
                        }
                        return true;
                    }
                }
            });
            if (formValues) {
                /**
                 * Verifica si se han recibido valores válidos en el formulario de SweetAlert2.
                 * Si es así, se invoca el método actualizarDatos para registrar los cambios en la base de datos.
                 * Luego, se genera un toast de SweetAlert2 para notificar al usuario que las correcciones fueron exitosas.
                 */
                this.actualizarDatos(IVAEdit, this.bandFlete, this.bandeFisica, this.bandResico);
                /**
                 * Configura el toast de SweetAlert2 para mostrar un mensaje de éxito.
                 * El toast se mostrará en la esquina superior derecha de la pantalla y permanecerá visible durante 3 segundos.
                 */
                const Toast = Swal.mixin({
                    // Configuración para mostrar el mensaje como un toast
                    toast: true,
                    // Posición del toast en la pantalla
                    position: 'top-end',
                    // Deshabilitar el botón de confirmación
                    showConfirmButton: false,
                    // Tiempo de duración del toast en milisegundos
                    timer: 3000
                });
                /**
                 * Muestra el toast de SweetAlert2 con un mensaje de éxito.
                 * El mensaje indica que el item ha sido modificado correctamente.
                 */
                Toast.fire({
                    icon: 'success',
                    title: 'Item Modificado'
                }).then(() => {
                    /**
                     * Redirecciona al usuario a la página de items de requisición después de mostrar el toast.
                     */
                    window.location.href = url2 + "/items_requisicion.php";
                });
            }
        },
        /**
             * Actualiza los datos de un item en la base de datos.
             * 
             * Este método recibe como parámetros el IVA nuevo y las banderas de las retenciones.
             * Luego, calcula los valores de las retenciones y el IVA, y los registra en la base de datos
             * utilizando el servidor como método de comunicación a través de axios.
             * 
             * @param {number} IVAEdit - El IVA nuevo.
             * @param {boolean} banderaFlete - Indica si el item tiene retención por flete.
             * @param {boolean} banderaFisica - Indica si el item tiene retención por renta persona física.
             * @param {boolean} banderaResico - Indica si el item tiene retención por RESICO.
        */
        actualizarDatos: function (IVAEdit, banderaFlete, banderaFisica, banderaResico) {
            // Auxiliar para calcular el valor del item
            var aux = this.cantidad * this.precio;
            // Auxiliar para calcular el valor de la retención por flete
            var auxFlete = 0;
            // Auxiliar para calcular el valor de la retención por renta persona física
            var auxFisico = 0;
            // Auxiliar para calcular el valor de la retención por RESICO
            var auxResico = 0;
            // Auxiliar para calcular el valor total de las retenciones
            var auxRet = 0;
            // Auxiliar para calcular el valor del IVA
            var auxIVA = 0;

            // Verifica si el IVA es mayor a 0, indica si es pago por transferencia o efectivo
            if (IVAEdit > 0) {
                // Calcula los valores de las retenciones y el IVA
                if (banderaFlete == true) {
                    auxFlete = aux * 0.04
                }
                if (banderaFisica == true) {
                    auxFisico = aux * 0.1067;
                }
                if (banderaResico == true) {
                    auxResico = aux * 0.0125;
                }
                auxIVA = aux * 0.16;
                auxRet = auxFisico + auxFlete + auxResico;
            }
            // Registra los datos en la base de datos utilizando axios
            axios.post(url, {
                // Acción a realizar (3: actualizar item)
                accion: 3,
                // Unidad del item
                unidad: this.unidad,
                // Nombre del producto del item
                producto: this.producto,
                // Valor del IVA del item
                iva: auxIVA,
                // Valor total de las retenciones del item
                retenciones: auxRet,
                // Indica si el item tiene retención por flete
                banderaFlete: banderaFlete,
                // Indica si el item tiene retención por renta persona física
                banderaFisica: banderaFisica,
                // Indica si el item tiene retención por RESICO
                banderaResico: banderaResico,
                // Precio unitario del item
                precio: this.precio,
                // Cantidad del item
                cantidad: this.cantidad,
                // Valor total del item
                total: this.AuxTotal,
                // ID del item
                id: this.id,
                // ID de la hoja de requisición
                id_Hoja: localStorage.getItem("idHoja")
            }).then(response => {
                console.log(response.data);
            });
        },
        /**
             * Notifica al usuario por medio de SweetAlert2 si en verdad eliminará el item que seleccionó.
             * Esto como una forma de seguridad dentro de la misma.
             * 
             * @param {number} ID - El ID del item a eliminar.
             * @param {number} cantidad - La cantidad del item a eliminar.
             * @param {number} precio - El precio unitario del item a eliminar.
             * @param {number} iva - El IVA del item a eliminar.
             * @param {number} retenciones - Las retenciones del item a eliminar.
        */
        eliminarItem: async function (ID, cantidad, precio, iva, retenciones) {
            const { value: formValues } = await Swal.fire({
                // Título del modal
                title: "¿Quieres eliminar el Item?",
                // Mostrar botón de cancelar
                showCancelButton: true,
                // Texto del botón de confirmación
                confirmButtonText: "Eliminar",
            }).then((result) => {
                // Verifica si la respuesta del usuario es positiva
                if (result.isConfirmed) {
                    // Llama al método deleteItem para eliminar el item de la base de datos
                    this.deleteItem(ID, cantidad, precio, localStorage.getItem("idHoja"), iva, retenciones);
                    // Muestra un mensaje de éxito al usuario
                    Swal.fire("El item fue eliminado con exito", "", "success").then(() => {
                        // Redirecciona al usuario a la página de items de requisición
                        window.location.href = url2 + "/items_requisicion.php";
                    });
                }
            });
        },
        deleteItem: function (ID, cantidad, precio, id_Hoja, iva, retenciones) {
            axios.post(url, { accion: 4, id: ID, total: this.AuxTotal, id_Hoja: id_Hoja }).then(response => {
                console.log(response.data);
                console.log("y el total es " + this.AuxTotal);
            });
        },
        agregarInformacionHoja: function (idHoja) {
            axios.post(url, { accion: 5, id_Hoja: idHoja }).then(response => {
                this.hojas = response.data;
                this.AuxTotal = Number.parseFloat(this.hojas[0].hojaRequisicion_total);
                console.log(this.hojas);
                console.log("y el total es " + this.AuxTotal);
            });
        },
        agregarItem: async function () {
            const { value: formValues } = await Swal.fire({
                title: "¿Quieres Agregar otro item a esta Requisicion Existente?",
                showCancelButton: true,
                confirmButtonText: "Continuar",
            }).then((result) => {
                if (result.isConfirmed) {
                    if (this.hojas[0].hojaRequisicion_formaPago == "Transferencia") {
                        this.HtmlRet = `
                    <div class="col">
    <hr />
    <div class="row form-group mx-0 my-3">
        <div class="col">
            <label for="producto" class="form-label">Nombre del Producto</label>
            <textarea class="form-control" id="producto" rows="3"></textarea>
        </div>
    </div>
    <div class="row form-group mx-0 my-3">
        <div class="col-4">
            <label for="unidad" class="form-label">Unidad</label>
            <select class="form-select" id="unidad" aria-label="Default select example">
                <option value="Selecciona Unidad" selected>Selecciona la Cantidad</option>
                <option value="DISEÑO">DISEÑO</option>
                <option value="PIEZAS">PIEZAS</option>
                <option value="BULTOS">BULTOS</option>
                <option value="PESOS">PESOS</option>
                <option value="LITROS">LITROS</option>
                <option value="SERVICIOS">SERVICIOS</option>
                <option value="MESUALIDAD">MESUALIDAD</option>
                <option value="RENTA">RENTA</option>
                <option value="CUBETAS">CUBETAS</option>
                <option value="TONELADAS">TONELADAS</option>
                <option value="METROS">METROS</option>
                <option value="METROS CUADRADOS">METROS CUADRADOS</option>
                <option value="METROS CUBICOS">METROS CUBICOS</option>
                <option value="KILOGRAMOS">KILOGRAMOS</option>
              </select>
        </div>
        <div class="col-4">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" min="0" class="form-control" id="cantidad">
        </div>
        <div class="col-4">
            <label for="precio" class="form-label">Precio Unitario</label>
            <input type="number" min="0" class="form-control" id="precio">
        </div>
    </div>
    <hr />
    <div class="row mx-0 my-3">
        <div class="col">
            <h5 class="text-start fw-bold">Activa las Requisiciones Necesarias</h5>
        </div>
    </div>
    <div class="row form-group mx-0 my-3">
        <div class="col-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="RetFlete">
                <label class="form-check-label" for="RetFlete">Retencion por Flete (4%)</label>
            </div>
        </div>
        <div class="col-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="RetPersonaFIsica">
                <label class="form-check-label" for="RetPersonaFIsica">Retencion por Renta Persona Fisica
                    (10.67%)</label>
            </div>
        </div>
    </div>
    <div class="row form-group mx-0 my-3">
        <div class="col-6">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="RetencionRESICO">
                <label class="form-check-label" for="RetencionRESICO">Retencion por RESICO (1.25%)</label>
            </div>
        </div>
    </div>
    <hr />
</div>
                        `;
                    } else {
                        this.HtmlRet = `
                        <div class="col">
        <hr />
        <div class="row form-group mx-0 my-3">
            <div class="col">
                <label for="producto" class="form-label">Nombre del Producto</label>
                <textarea class="form-control" id="producto" rows="3"></textarea>
            </div>
        </div>
        <div class="row form-group mx-0 my-3">
            <div class="col-4">
                <label for="unidad" class="form-label">Unidad</label>
                <select class="form-select" id="unidad" aria-label="Default select example">
                <option value="Selecciona Unidad" selected>Selecciona la Cantidad</option>
                <option value="DISEÑO">DISEÑO</option>
                <option value="PIEZAS">PIEZAS</option>
                <option value="BULTOS">BULTOS</option>
                <option value="PESOS">PESOS</option>
                <option value="LITROS">LITROS</option>
                <option value="SERVICIOS">SERVICIOS</option>
                <option value="MESUALIDAD">MESUALIDAD</option>
                <option value="RENTA">RENTA</option>
                <option value="CUBETAS">CUBETAS</option>
                <option value="TONELADAS">TONELADAS</option>
                <option value="METROS">METROS</option>
                <option value="METROS CUADRADOS">METROS CUADRADOS</option>
                <option value="METROS CUBICOS">METROS CUBICOS</option>
                <option value="KILOGRAMOS">KILOGRAMOS</option>
                  </select>
            </div>
            <div class="col-4">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" min="0" class="form-control" id="cantidad">
            </div>
            <div class="col-4">
                <label for="precio" class="form-label">Precio Unitario</label>
                <input type="number" min="0" class="form-control" id="precio">
            </div>
        </div>
        <hr />
    </div>
                        `;
                    }
                    this.addItemAlert();
                }
            });
        },
        addItemAlert: async function () {
            const { value: formValues } = await Swal.fire({
                title: "Agregar Item",
                html: this.HtmlRet,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                    if (this.hojas[0].hojaRequisicion_formaPago == "Transferencia") {
                        this.producto = document.getElementById("producto").value;
                        this.unidad = document.getElementById("unidad").value;
                        this.cantidad = document.getElementById("cantidad").value;
                        this.precio = document.getElementById("precio").value;
                        this.bandFlete = document.getElementById("RetFlete").checked;
                        this.bandeFisica = document.getElementById("RetPersonaFIsica").checked;
                        this.bandResico = document.getElementById("RetencionRESICO").checked;
                        if (this.producto.length > 200) {
                            Swal.showValidationMessage('El campo Producto no puede exceder los 200 caracteres.');
                            return false;
                        }
                        if (!this.producto || this.unidad === "Selecciona Unidad" || !this.cantidad || !this.precio) {
                            Swal.showValidationMessage('Por favor completa todos los campos');
                            return false;
                        }
                        return true;
                    }
                    else {
                        this.producto = document.getElementById("producto").value;
                        this.unidad = document.getElementById("unidad").value;
                        this.cantidad = document.getElementById("cantidad").value;
                        this.precio = document.getElementById("precio").value;
                        if (this.producto.length > 200) {
                            Swal.showValidationMessage('El campo Producto no puede exceder los 200 caracteres.');
                            return false;
                        }
                        if (!this.producto || this.unidad === "Selecciona Unidad" || !this.cantidad || !this.precio) {
                            Swal.showValidationMessage('Por favor completa todos los campos');
                            return false;
                        }
                        return true;
                    }
                }
            });
            if (formValues) {
                this.addItem()
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Item Agregado'
                }).then(() => {
                    window.location.href = url2 + "/items_requisicion.php";
                });
            }
        },
        addItem: function () {
            var aux = this.cantidad * this.precio;
            var auxFlete = 0;
            var auxFisico = 0;
            var auxResico = 0;
            var auxRet = 0;
            var auxIVA = 0;
            if (this.hojas[0].hojaRequisicion_formaPago == "Transferencia") {
                if (this.bandFlete == true) {
                    auxFlete = aux * 0.04
                }
                if (this.bandeFisica == true) {
                    auxFisico = aux * 0.1067;
                }
                if (this.bandResico == true) {
                    auxResico = aux * 0.0125;
                }
                auxIVA = aux * 0.16;
                auxRet = auxFisico + auxFlete + auxResico;
            }
            axios.post(url, { accion: 6, unidad: this.unidad, producto: this.producto, iva: auxIVA, retenciones: auxRet, banderaFlete: this.bandFlete, banderaFisica: this.bandeFisica, banderaResico: this.bandResico, precio: this.precio, cantidad: this.cantidad, total: this.AuxTotal, id_Hoja: this.hojas[0].hojaRequisicion_id }).then(response => {
                console.log(response.data);
            });
        },
        validarRequisicion: async function () {
            const { value: formValues } = await Swal.fire({
                title: "¿Quieres enviar a validacion la Requisicion?",
                showCancelButton: true,
                confirmButtonText: "Continuar",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.solicitarRevision(localStorage.getItem("idRequisicion"));
                    Swal.fire("Requisicion enviada", "", "success");
                }
            });
        },
        solicitarRevision: function (idReq) {
            axios.post(url, { accion: 7, id_req: idReq }).then(response => {
                console.log(response.data);
            });
        },
        imprimirReq: function () {
            generarPDFRequisicion(this.Numero_Req, this.clve, this.hojas[0], this.NameUser, this.itemsHoja, this.obras[0]);
        },
        obtnerInfoObras: function (idObras) {
            axios.post(url, { accion: 8, obra: idObras }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        obtenerInfoRequisicion: function (idReq) {
            axios.post(url, { accion: 9, id_req: idReq }).then(response => {
                this.clve = response.data[0].requisicion_Clave;
                this.Numero_Req = response.data[0].requisicion_Numero;
                console.log(this.clve + " " + this.Numero_Req);
            });
        },
        listarObras: function () {
            axios.post(url, { accion: 10 }).then(response => {
                this.obrasLista = response.data;
                console.log(this.obrasLista);
            });
        },
        irObra(idObra) {
            localStorage.setItem("obraActiva", idObra);
            window.location.href = url2 + "/obras.php";
        }
    },
    created: function () {
        $('#example').DataTable({
            "order": [],
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
        this.listarObras();
        this.obtenerInfoRequisicion(localStorage.getItem("idRequisicion"));
        this.obtnerInfoObras(localStorage.getItem("obraActiva"));
        this.agregarInformacionHoja(localStorage.getItem("idHoja"));
        this.listarItems(localStorage.getItem("idHoja"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
    },
    computed: {

    }
});