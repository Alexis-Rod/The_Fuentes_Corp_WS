var url = "bd/crud_items_requisiciones.php";
var url2 = ".";

const appRequesition = new Vue({
    el: "#AppPresion",
    // Se define la estructura de datos para la aplicación
    data: {
        // Lista de items de la hoja
        itemsHoja: [],
        // Información de la hoja
        hojas: [],
        // Información de la obra
        obras: [],
        // Lista de obras activas
        obrasLista: [],
        // Nombre del usuario
        NameUser: "",
        // Nombre del producto
        producto: "",
        // Unidad del producto
        unidad: 0,
        // Cantidad del producto
        cantidad: 0,
        // Precio unitario del producto
        precio: 0,
        // IVA del producto
        IVA: 0,
        // Subtotal del producto
        subTotal: 0,
        // Total auxiliar del producto
        AuxTotal: 0,
        // Retenciones del producto
        Retenciones: 0,
        // Bandera para indicar si el producto tiene retención por flete
        bandFlete: false,
        // Bandera para indicar si el producto tiene retención por renta persona física
        bandeFisica: false,
        // Bandera para indicar si el producto tiene retención por RESICO
        bandResico: false,
        // HTML para mostrar las retenciones
        HtmlRet: "",
        // Cadena para mostrar la retención por flete
        strFlete: "",
        // Cadena para mostrar la retención por renta persona física
        strFisca: "",
        // Cadena para mostrar la retención por RESICO
        strResico: "",
        // ID del producto
        id: 0,
        // Clave de la requisición
        clve: "",
        // Número de la requisición
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
        /**
             * Elimina un item de la base de datos.
             * 
             * Este método recibe como parámetros el ID del item a eliminar, la cantidad y el precio del item.
             * Utiliza axios para enviar una solicitud al servidor y eliminar el item de la base de datos.
             * 
             * @param {number} ID - El ID del item a eliminar.
             * @param {number} cantidad - La cantidad del item a eliminar.
             * @param {number} precio - El precio unitario del item a eliminar.
             * @param {number} id_Hoja - El ID de la hoja de requisición.
             * @param {number} iva - El IVA del item a eliminar.
             * @param {number} retenciones - Las retenciones del item a eliminar.
         */
        deleteItem: function (ID, cantidad, precio, id_Hoja, iva, retenciones) {
            // Realiza una petición POST al servidor para eliminar el item de la base de datos
            axios.post(url, {
                // Acción a realizar (4: eliminar item)
                accion: 4,
                // ID del item a eliminar
                id: ID,
                // Total del item a eliminar
                total: this.AuxTotal,
                // ID de la hoja de requisición
                id_Hoja: id_Hoja
            }).then(response => {
                // Procesa la respuesta del servidor
                console.log(response.data);
            });
        },
        /**
             * Agrega la información de la hoja, como su proveedor, emisor, etc.
             * Esta función realiza una petición POST a la URL especificada para obtener la información de la hoja con el id proporcionado.
             * 
             * @param {number} idHoja - El id de la hoja para la cual se desean obtener los datos.
         */
        agregarInformacionHoja: function (idHoja) {
            // Realiza una petición POST a la URL para obtener la información de la hoja.
            axios.post(url, { accion: 5, id_Hoja: idHoja })
                .then(response => {
                    // Asigna la respuesta de la petición a la variable 'hojas'.
                    this.hojas = response.data;
                    // Muestra en la consola la información de la hoja.
                    console.log(this.hojas);
                });
        },
        /**
         * Función para agregar un nuevo item a la hoja seleccionada.
         * Esta función despliega una serie de alertas en SweetAlert2 para confirmar y registrar el nuevo item.
         * La primera alerta de confirmación pregunta si se desea agregar otro item a la hoja seleccionada.
         * Si la respuesta es positiva, se muestra una segunda alerta con un formulario para agregar la información del nuevo item.
         * Finalmente, se invoca el método addItem para registrar la información del item en la base de datos.
        */
        agregarItem: async function () {
            // Muestra una alerta de confirmación para preguntar si se desea agregar otro item
            const { value: formValues } = await Swal.fire({
                // Título del modal de confirmación para agregar un nuevo item a la hoja
                title: "¿Quieres Agregar otro item a esta Requisicion Existente?",
                // Mostrar botón de cancelar en el modal
                showCancelButton: true,
                // Texto del botón de confirmación en el modal
                confirmButtonText: "Continuar",
            }).then((result) => { // Si se confirma la acción, se muestra el formulario para agregar el item
                if (result.isConfirmed) {
                    if (this.hojas[0].hojaRequisicion_formaPago == "Transferencia") { // 
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
                            `; // este codigo html es el que se muestra en el formulario de forma de pago por transferencia.
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
                        `; // este es el codigo html que se muestra en el formulario de forma de pago por efectivo.
                    }
                    // aqui se invoca el metodo de addItemAlert que muestra las alertas para agregar el item segun sea la forma de pago.
                    this.addItemAlert();
                }
            });
        },
        /**
            * Muestra una alerta en SweetAlert2 con un formulario para agregar un nuevo item a la hoja.
            * La alerta incluye configuraciones para mostrar un título, un formulario con campos para ingresar la información del item,
            * y botones para confirmar o cancelar la acción.
            * La función también captura los datos ingresados en el formulario, los valida y, si son válidos, invoca el método addItem para registrar la información en la base de datos.
        */
        addItemAlert: async function () {
            const { value: formValues } = await Swal.fire({
                // Título de la alerta
                title: "Agregar Item",
                // Contenido HTML de la alerta, que incluye el formulario para ingresar la información del item
                html: this.HtmlRet,
                // Deshabilitar el foco en el botón de confirmación
                focusConfirm: false,
                // Mostrar botón de cancelar
                showCancelButton: true,
                // Texto del botón de confirmación
                confirmButtonText: 'Agregar',
                // Color del botón de confirmación
                confirmButtonColor: '#0d6efd',
                // Color del botón de cancelar
                cancelButtonColor: '#dc3545',
                // Función de validación antes de confirmar
                preConfirm: () => {
                    // Verificar si la forma de pago es transferencia
                    if (this.hojas[0].hojaRequisicion_formaPago == "Transferencia") {
                        // Obtener los valores de los campos del formulario
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
                    // Si la forma de pago no es transferencia
                    else {
                        // Obtener los valores de los campos del formulario
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
            // Si se confirma la acción, invoca el método addItem para registrar la información en la base de datos
            if (formValues) {
                // Invoca el método addItem para registrar la información en la base de datos
                this.addItem()
                // Configura un toast de SweetAlert2 para mostrar un mensaje de éxito
                const Toast = Swal.mixin({
                    toast: true, // mostrar el mensaje como un toast
                    position: 'top-end', // posición del mensaje
                    showConfirmButton: false, // no mostrar el botón de confirmación
                    timer: 3000 // tiempo de duración del mensaje
                });
                // Muestra el toast con un mensaje de éxito
                Toast.fire({
                    icon: 'success', // icono del mensaje
                    title: 'Item Agregado' // título del mensaje
                }).then(() => {
                    // Redirecciona a la página de items de requisición
                    window.location.href = url2 + "/items_requisicion.php";
                });
            }
        },
        /**
             * Agrega un nuevo item a la base de datos.
             * 
             * Este método utiliza el método POST de axios para enviar una solicitud al servidor y agregar el item a la base de datos.
             * La solicitud incluye los datos del item, como la unidad, el producto, el IVA, las retenciones, y la forma de pago.
             * 
             * @return {void}
        */
        addItem: function () {
            // Calcula el valor total del item
            var aux = this.cantidad * this.precio;

            // Inicializa variables para calcular las retenciones y el IVA
            var auxFlete = 0;
            var auxFisico = 0;
            var auxResico = 0;
            var auxRet = 0;
            var auxIVA = 0;

            // Verifica si la forma de pago es transferencia
            if (this.hojas[0].hojaRequisicion_formaPago == "Transferencia") {
                // Calcula las retenciones y el IVA para pago por transferencia
                if (this.bandFlete == true) {
                    auxFlete = aux * 0.04; // Retención por flete (4%)
                }
                if (this.bandeFisica == true) {
                    auxFisico = aux * 0.1067; // Retención por renta persona física (10.67%)
                }
                if (this.bandResico == true) {
                    auxResico = aux * 0.0125; // Retención por RESICO (1.25%)
                }
                auxIVA = aux * 0.16; // IVA (16%)
                auxRet = auxFisico + auxFlete + auxResico; // Total de retenciones
            }

            // Envía la solicitud al servidor para agregar el item a la base de datos
            axios.post(url, {
                accion: 6, // Acción a realizar (6: agregar item)
                unidad: this.unidad, // Unidad del item
                producto: this.producto, // Nombre del producto del item
                iva: auxIVA, // Valor del IVA del item
                retenciones: auxRet, // Valor total de las retenciones del item
                banderaFlete: this.bandFlete, // Indica si el item tiene retención por flete
                banderaFisica: this.bandeFisica, // Indica si el item tiene retención por renta persona física
                banderaResico: this.bandResico, // Indica si el item tiene retención por RESICO
                precio: this.precio, // Precio unitario del item
                cantidad: this.cantidad, // Cantidad del item
                total: this.AuxTotal, // Valor total del item
                id_Hoja: this.hojas[0].hojaRequisicion_id // ID de la hoja de requisición
            }).then(response => {
                console.log(response.data); // Muestra la respuesta del servidor en la consola
            });
        },
        /**
             * Solicita la validación de la Hoja.
             * 
             * Esta función muestra una alerta al usuario para confirmar si quiere enviar la Hoja a revisión.
             * Si la respuesta es positiva, se invoca el método solicitarRevision para realizar la lógica en el servidor.
             * 
             * @return {void}
        */
        validarRequisicion: async function () {
            // Configuración de la alerta para solicitar la validación de la Hoja
            const { value: formValues } = await Swal.fire({
                // Título de la alerta
                title: "¿Quieres enviar a revisión la Hoja?",
                // Mostrar botón de cancelar
                showCancelButton: true,
                // Texto del botón de confirmación
                confirmButtonText: "Continuar",
            }).then((result) => {
                // Verifica si la respuesta del usuario es positiva
                if (result.isConfirmed) {
                    // Invoca el método solicitarRevision para realizar la lógica en el servidor
                    this.solicitarRevision(localStorage.getItem("idRequisicion"));
                    // Muestra un mensaje de éxito al usuario
                    Swal.fire("Hoja enviada a revisión", "", "success");
                }
            });
        },
        /**
             * Solicita la revisión de una requisición.
             * 
             * Este método realiza una petición POST a la URL especificada para solicitar la revisión de una requisición.
             * La petición incluye el ID de la requisición a revisar.
             * 
             * @param {number} idReq - El ID de la requisición a revisar.
        */
        solicitarRevision: function (idReq) {
            // Realiza una petición POST a la URL para solicitar la revisión de la requisición.
            axios.post(url, {
                // Acción a realizar (7: solicitar revisión)
                accion: 7,
                // ID de la requisición a revisar
                id_req: idReq
            }).then(response => {
                // Procesa la respuesta del servidor
                console.log(response.data);
            });
        },
        /**
             * Imprime la requisición generando un PDF.
             * 
             * Este método realiza la vinculación del front con el método de generarPDFRequisicion, 
             * que se encarga de generar el PDF de la Hoja correspondiente.
             * 
             * @return {void}
        */
        imprimirReq: function () {
            // Llama al método generarPDFRequisicion para generar el PDF de la Hoja
            // Pasando como parámetros los datos necesarios para la generación del PDF
            generarPDFRequisicion(
                this.Numero_Req, // Número de la requisición
                this.clve, // Clave de la requisición
                this.hojas[0], // Información de la Hoja
                this.NameUser, // Nombre del usuario
                this.itemsHoja, // Items de la Hoja
                this.obras[0] // Información de la obra
            );
        },
        /**
             * Obtiene la información de la obra que le pertenece a la requisición y hoja respectivamente.
             * 
             * Este método utiliza el método POST de axios para solicitar a la base de datos la información de la obra correspondiente.
             * 
             * @param {number} idObras - El ID de la obra para la cual se desea obtener la información.
        */
        obtnerInfoObras: function (idObras) {
            // Realiza una petición POST a la URL para obtener la información de la obra.
            axios.post(url, {
                // Acción a realizar (8: obtener información de la obra)
                accion: 8,
                // ID de la obra para la cual se desea obtener la información
                obra: idObras
            }).then(response => {
                // Asigna la respuesta de la petición a la variable 'obras'.
                this.obras = response.data;
                // Muestra en la consola la información de la obra.
                console.log(this.obras);
            });
        },
        /**
             * Obtiene la información de la requisición que le pertenece a la obra.
             * 
             * Este método utiliza el método POST de axios para solicitar a la base de datos la información de la requisición correspondiente.
             * La información se utiliza para mostrar al usuario la clave y el número de la requisición.
             * 
             * @param {number} idReq - El ID de la requisición para la cual se desea obtener la información.
        */
        obtenerInfoRequisicion: function (idReq) {
            // Realiza una petición POST a la URL para obtener la información de la requisición.
            axios.post(url, {
                accion: 9, // Acción a realizar (9: obtener información de la requisición)
                id_req: idReq // ID de la requisición para la cual se desea obtener la información
            }
            ).then(response => {
                // Asigna la clave y el número de la requisición a las variables correspondientes.
                this.clve = response.data[0].requisicion_Clave;
                this.Numero_Req = response.data[0].requisicion_Numero;
            });
        },
        /**
             * Obtiene todas las obras activas de la base de datos.
             * 
             * Este método utiliza el método POST de axios para solicitar a la base de datos la información de las obras activas.
             * La información se almacena en la variable 'obrasLista' para su posterior uso.
             * 
             * @return {void}
        */
        listarObras: function () {
            // Realiza una petición POST a la URL para obtener la información de las obras activas.
            axios.post(url, {
                // Acción a realizar (10: obtener obras activas)
                accion: 10
            }).then(response => {
                // Asigna la respuesta de la petición a la variable 'obrasLista'.
                this.obrasLista = response.data;
                // Muestra en la consola la información de las obras activas.
                console.log(this.obrasLista);
            });
        },
        /**
             * Redirecciona a la página de obras después de establecer la obra activa en el almacenamiento local.
             * 
             * @param {number} idObra - El ID de la obra a establecer como activa.
        */
        irObra(idObra) {
            // Establece la obra activa en el almacenamiento local
            localStorage.setItem("obraActiva", idObra);

            // Redirecciona a la página de obras
            window.location.href = url2 + "/obras.php";
        }
    },
    /**
         * Función que se ejecuta cuando se crea el componente.
         * Inicializa la tabla de datos y realiza varias peticiones a la base de datos para obtener información.
    */
    created: function () {
        /**
         * Inicializa la tabla de datos con la configuración especificada.
         * La configuración incluye el idioma y las opciones de paginación.
         */
        $('#example').DataTable({
            "order": [], // No ordenar la tabla por defecto
            "language": { // Configuración del idioma
                "sProcessing": "Procesando...", // Mensaje de procesamiento
                "sLengthMenu": "Mostrar _MENU_ registros", // Menú de longitud
                "sZeroRecords": "No se encontraron resultados", // Mensaje de cero registros
                "sEmptyTable": "Ningún dato disponible en esta tabla", // Mensaje de tabla vacía
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros", // Información de registros
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros", // Información de registros vacía
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)", // Información de registros filtrados
                "sInfoPostFix": "", // Postfijo de información
                "sSearch": "Buscar:", // Etiqueta de búsqueda
                "sUrl": "", // URL de la tabla
                "sInfoThousands": ",", // Separador de miles
                "sLoadingRecords": "Cargando...", // Mensaje de carga
                "oPaginate": { // Configuración de paginación
                    "sFirst": "Primero", // Etiqueta de primer página
                    "sLast": "Último", // Etiqueta de última página
                    "sNext": "Siguiente", // Etiqueta de página siguiente
                    "sPrevious": "Anterior" // Etiqueta de página anterior
                },
                "oAria": { // Configuración de accesibilidad
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente", // Etiqueta de orden ascendente
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente" // Etiqueta de orden descendente
                }
            }
        });
        /**
         * Realiza varias peticiones a la base de datos para obtener información.
         * Las peticiones incluyen la lista de obras, la información de la requisición y la información de la obra.
         */
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