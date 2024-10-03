var url = "bd/crud_items_requisiciones.php";

const appRequesition = new Vue({
    el: "#AppPresion",
    data: {
        itemsRequisicion: [],
        requisicion: [],
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
        clve: ""
    },
    methods: {
        listarItems: function (idReq) {
            axios.post(url, { accion: 1, id_req: idReq }).then(response => {
                this.itemsRequisicion = response.data;
                console.log(this.itemsRequisicion);
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
            <input type="text" class="form-control" id="producto" value="`+ productoEdit + `">
        </div>
    </div>
    <div class="row form-group mx-0 my-3">
        <div class="col-4">
            <label for="unidad" class="form-label">Unidad</label>
            <select class="form-select" id="unidad" aria-label="Default select example">
                <option value="" selected>Selecciona la Cantidad</option>
                <option value="DISEÑO">DISEÑO</option>
                <option value="PIEZAS">PIEZAS</option>
                <option value="BULTOS">BULTOS</option>
                <option value="PESOS">PESOS</option>
                <option value="LTS">LITROS</option>
                <option value="SER">SERVICIO</option>
                <option value="MES">MENSUALIDAD</option>
                <option value="RENTA">RENTA</option>
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
            <input type="text" class="form-control" id="producto" value="`+ productoEdit + `">
        </div>
    </div>
    <div class="row form-group mx-0 my-3">
        <div class="col-4">
            <label for="unidad" class="form-label">Unidad</label>
            <select class="form-select" id="unidad" aria-label="Default select example">
                <option value="" selected>Selecciona la Cantidad</option>
                <option value="DISEÑO">DISEÑO</option>
                <option value="PIEZAS">PIEZAS</option>
                <option value="BULTOS">BULTOS</option>
                <option value="PESOS">PESOS</option>
                <option value="LTS">LITROS</option>
                <option value="SER">SERVICIO</option>
                <option value="MES">MENSUALIDAD</option>
                <option value="RENTA">RENTA</option>
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
                confirmButtonText: 'Agregar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                    if (IVAEdit > 0) {
                        return [
                            this.producto = document.getElementById("producto").value,
                            this.unidad = document.getElementById("unidad").value,
                            this.cantidad = document.getElementById("cantidad").value,
                            this.precio = document.getElementById("precio").value,
                            this.bandFlete = document.getElementById("RetFlete").checked,
                            this.bandeFisica = document.getElementById("RetPersonaFIsica").checked,
                            this.bandResico = document.getElementById("RetencionRESICO").checked,
                        ];
                    }
                    else {
                        return [
                            this.producto = document.getElementById("producto").value,
                            this.unidad = document.getElementById("unidad").value,
                            this.cantidad = document.getElementById("cantidad").value,
                            this.precio = document.getElementById("precio").value,
                        ];
                    }
                }
            });
            if (this.producto == '' || this.unidad == "" || this.cantidad == 0 || this.precio == 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No se Edito el Item',
                });
            }
            else {
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
                })
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
            axios.post(url, { accion: 3, unidad: this.unidad, producto: this.producto, iva: auxIVA, retenciones: auxRet, banderaFlete: banderaFlete, banderaFisica: banderaFisica, banderaResico: banderaResico, precio: this.precio, cantidad: this.cantidad, total: this.AuxTotal, id: this.id }).then(response => {
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
                    this.deleteItem(ID, cantidad, precio, localStorage.getItem("idRequisicion"), iva, retenciones);
                    Swal.fire("El item fue eliminado con exito", "", "success");
                }
            });
        },
        deleteItem: function (ID, cantidad, precio, idReq, iva, retenciones) {
            var aux = cantidad * precio;
            aux = aux + Number.parseFloat(iva);
            aux = aux - Number.parseFloat(retenciones);
            this.AuxTotal = this.AuxTotal - Number.parseFloat(aux);
            axios.post(url, { accion: 4, id: ID, total: this.AuxTotal, id_req: idReq }).then(response => {
                console.log(response.data);
                console.log("y el total es " + this.AuxTotal);
            });
        },
        agregarInformacionRequisicion: function (idReq) {
            axios.post(url, { accion: 5, id_req: idReq }).then(response => {
                this.requisicion = response.data;
                this.AuxTotal = Number.parseFloat(this.requisicion[0].requisicion_total);
                console.log(this.requisicion);
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
                    if (this.requisicion[0].requisicion_formaPago == "Transferencia") {
                        this.HtmlRet = `
                    <div class="col">
    <hr />
    <div class="row form-group mx-0 my-3">
        <div class="col">
            <label for="producto" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="producto">
        </div>
    </div>
    <div class="row form-group mx-0 my-3">
        <div class="col-4">
            <label for="unidad" class="form-label">Unidad</label>
            <select class="form-select" id="unidad" aria-label="Default select example">
                <option value="" selected>Selecciona la Cantidad</option>
                <option value="DISEÑO">DISEÑO</option>
                <option value="PIEZAS">PIEZAS</option>
                <option value="BULTOS">BULTOS</option>
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
                <input type="text" class="form-control" id="producto">
            </div>
        </div>
        <div class="row form-group mx-0 my-3">
            <div class="col-4">
                <label for="unidad" class="form-label">Unidad</label>
                <select class="form-select" id="unidad" aria-label="Default select example">
                    <option value="" selected>Selecciona la Cantidad</option>
                    <option value="DISEÑO">DISEÑO</option>
                    <option value="PIEZAS">PIEZAS</option>
                    <option value="BULTOS">BULTOS</option>
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
                    if (this.requisicion[0].requisicion_formaPago == "Transferencia") {
                        return [
                            this.producto = document.getElementById("producto").value,
                            this.unidad = document.getElementById("unidad").value,
                            this.cantidad = document.getElementById("cantidad").value,
                            this.precio = document.getElementById("precio").value,
                            this.bandFlete = document.getElementById("RetFlete").checked,
                            this.bandeFisica = document.getElementById("RetPersonaFIsica").checked,
                            this.bandResico = document.getElementById("RetencionRESICO").checked,
                        ];
                    }
                    else {
                        return [
                            this.producto = document.getElementById("producto").value,
                            this.unidad = document.getElementById("unidad").value,
                            this.cantidad = document.getElementById("cantidad").value,
                            this.precio = document.getElementById("precio").value,
                        ];
                    }
                }
            });
            if (this.producto == '' || this.unidad == "" || this.cantidad == 0 || this.precio == 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No se Edito el Item',
                });
            }
            else {
                this.addItem()
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Item Modificado'
                })
            }
        },
        addItem: function () {
            var aux = this.cantidad * this.precio;
            var auxFlete = 0;
            var auxFisico = 0;
            var auxResico = 0;
            var auxRet = 0;
            var auxIVA = 0;
            if (this.requisicion[0].requisicion_formaPago == "Transferencia") {
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
            axios.post(url, { accion: 6, unidad: this.unidad, producto: this.producto, iva: auxIVA, retenciones: auxRet, banderaFlete: this.bandFlete, banderaFisica: this.bandeFisica, banderaResico: this.bandResico, precio: this.precio, cantidad: this.cantidad, total: this.AuxTotal, id_req: this.requisicion[0].requisicion_id }).then(response => {
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
            generarPDFRequisicion(this.requisicion[0], this.NameUser, this.itemsRequisicion,this.obras[0]);
        },
        obtnerInfoObras: function(idObras){
            axios.post(url, { accion: 8, obra: idObras }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        obtenerInfoPresion: function(idPress){
            axios.post(url, { accion: 9, idPresion: idPress }).then(response => {
                this.clve = response.data[0].presiones_clave;
                console.log(response.data);
            });
        },
        listarObras: function(){
            axios.post(url, { accion: 10}).then(response => {
                this.obrasLista = response.data;
                console.log(this.obrasLista);
            });
        },
        irPresion(idPresion)
        {
            localStorage.setItem("obraActiva", idPresion);
            window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/presiones.php";
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
        this.obtenerInfoPresion(localStorage.getItem("IdPresion"));
        this.obtnerInfoObras(localStorage.getItem("obraActiva"));
        this.agregarInformacionRequisicion(localStorage.getItem("idRequisicion"));
        this.listarItems(localStorage.getItem("idRequisicion"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
    },
    computed: {

    }
});