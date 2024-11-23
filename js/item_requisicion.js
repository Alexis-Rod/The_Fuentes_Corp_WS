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
        listarItems: function (id_Hoja) {
            axios.post(url, { accion: 1, id_Hoja: id_Hoja }).then(response => {
                this.itemsHoja = response.data;
                console.log(this.itemsHoja);
            });
        },
        consultarUsuario: function (user_id) {
            axios.post(url, { accion: 2, id_user: user_id }).then(response => {
                this.users = response.data;
                this.NameUser = this.users[0].user_name;
                console.log(this.users);
            });
        },
        editItem: async function (productoEdit, cantidadEdit, precioEdit, IVAEdit, banderaFlete, banderaFisica, banderaResico, ID) {
            this.id = ID;
            this.subTotal = cantidadEdit * precioEdit;
            if (IVAEdit > 0) {
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
            const { value: formValues } = await Swal.fire({
                title: "Editar Item",
                html: this.HtmlRet,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Modificar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                    if (IVAEdit > 0) {
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
                this.actualizarDatos(IVAEdit, this.bandFlete, this.bandeFisica, this.bandResico);
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Item Modificado'
                }).then(() => {
                    window.location.href = url2 + "/items_requisicion.php";
                });
            }
        },
        actualizarDatos: function (IVAEdit, banderaFlete, banderaFisica, banderaResico) {
            var aux = this.cantidad * this.precio;
            var auxFlete = 0;
            var auxFisico = 0;
            var auxResico = 0;
            var auxRet = 0;
            var auxIVA = 0;
            var auxTotal = 0;
            if (IVAEdit > 0) {
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
            auxTotal = Number.parseFloat(this.AuxTotal) - (Number.parseFloat(this.subTotal) + Number.parseFloat(IVAEdit));
            this.AuxTotal = aux + auxIVA;
            this.AuxTotal = this.AuxTotal - auxRet;
            this.AuxTotal = this.AuxTotal + auxTotal;
            console.log(this.AuxTotal);
            axios.post(url, { accion: 3, unidad: this.unidad, producto: this.producto, iva: auxIVA, retenciones: auxRet, banderaFlete: banderaFlete, banderaFisica: banderaFisica, banderaResico: banderaResico, precio: this.precio, cantidad: this.cantidad, total: this.AuxTotal, id: this.id , id_Hoja: localStorage.getItem("idHoja")}).then(response => {
                console.log(response.data);
                console.log("y el total es " + this.AuxTotal);
            });
        },
        eliminarItem: async function (ID, cantidad, precio, iva, retenciones) {
            const { value: formValues } = await Swal.fire({
                title: "¿Quieres eliminar el Item?",
                showCancelButton: true,
                confirmButtonText: "Eliminar",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteItem(ID, cantidad, precio, localStorage.getItem("idHoja"), iva, retenciones);
                    Swal.fire("El item fue eliminado con exito", "", "success").then(()=>{
                        window.location.href = url2 + "/items_requisicion.php";
                    });
                }
            });
        },
        deleteItem: function (ID, cantidad, precio, id_Hoja, iva, retenciones) {
            var aux = cantidad * precio;
            aux = aux + Number.parseFloat(iva);
            aux = aux - Number.parseFloat(retenciones);
            this.AuxTotal = this.AuxTotal - Number.parseFloat(aux);
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
                    auxFlete = aux * 0.4
                }
                if (this.bandeFisica == true) {
                    auxFisico = aux * 0.1067;
                }
                if (this.bandResico == true) {
                    auxResico = aux * 0.125;
                }
                auxIVA = aux * 0.16;
                auxRet = auxFisico + auxFlete + auxResico;
            }
            this.subTotal = aux + auxIVA;
            this.subTotal = this.subTotal - auxRet;
            this.AuxTotal = this.AuxTotal + this.subTotal;
            //alert(this.unidad+" "+this.producto+" "+auxIVA+" "+auxRet+" "+this.bandFlete+" "+this.bandeFisica+" "+this.bandResico+" "+this.precio+" "+this.cantidad+" el total es ["+total+"] "+ this.requisicion[0].requisicion_id);
            axios.post(url, { accion: 6, unidad: this.unidad, producto: this.producto, iva: auxIVA, retenciones: auxRet, banderaFlete: this.bandFlete, banderaFisica: this.bandeFisica, banderaResico: this.bandResico, precio: this.precio, cantidad: this.cantidad, total: this.AuxTotal, id_Hoja: this.hojas[0].hojaRequisicion_id }).then(response => {
                console.log(response.data);
                console.log("y el total es " + this.AuxTotal);
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