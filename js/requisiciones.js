var url = "bd/crud_Requisiciones.php";
var url2 = ".";

const appRequesition = new Vue({
    el: "#AppPresion",
    data: {
        requisiciones: [],
        presiones: [],
        obras: [],
        obrasLista: [],
        requisicion: "",
        NameUser: "",
        gastosTotalPresion: 0,
        nombreRequisicion: "",
        fechaGeneracion: "",
        clave: "",
        folioReq: "",
        hojaReq: ""
    },
    methods: {
        ConsultarItemRq: async function (idRq) {
            localStorage.setItem("idRequisicion", idRq);
            window.location.href = url2 + "/hojas_requisicion.php";
        },
        infoObraActiva: function (obrasId) {
            axios.post(url, { accion: 3, obra: obrasId }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        listarRequisiciones: function (idObra) {
            axios.post(url, { accion: 1, obra: idObra }).then(response => {
                this.requisiciones = response.data;
                console.log(this.requisiciones);
            });
        },
        consultarUsuario: function (user_id) {
            axios.post(url, { accion: 2, id_user: user_id }).then(response => {
                this.users = response.data;
                this.NameUser = this.users[0].user_name;
                console.log(this.users);
            });
        },
        listarObras: function () {
            axios.post(url, { accion: 5 }).then(response => {
                this.obrasLista = response.data;
                console.log(this.obrasLista);
            });
        },
        irObra(idObra) {
            localStorage.setItem("obraActiva", idObra);
            window.location.href = url2 + "/obras.php";
        },
        addRequisicion: async function () {
            if (this.obras[0].obra_automatico == 1) {
                const { value: formValues } = await Swal.fire({
                    title: "Nueva Requisicion",
                    html: `
                        <div class="col">
                            <hr/>
                            <form id="requisicionForm">
                                <div class="row form-group mx-0 my-3">
                                    <div class="col d-flex flex-column">
                                        <label class="text-start py-2" for="nombreRequisicion">Nombre de la Requisición</label>
                                        <input type="text" class="form-control" id="nombreRequisicion" placeholder="Ingrese el nombre de la requisición" required>
                                    </div>
                                </div>
                                <div class="row form-group mx-0 my-3">
                                    <div class="col d-flex flex-column">
                                        <label class="text-start py-2" for="fechaGeneracion">Fecha de Generación</label>
                                        <input type="date" class="form-control" id="fechaGeneracion" required>
                                    </div>
                                </div>
                                <div class="row form-group mx-0 my-3">
                                    <div class="col d-flex flex-column">
                                        <label for="Clv" class="text-start py-2">Clave</label>
                                        <select class="form-select" aria-label="Default select example" id="Clv">
                                            <option>Selecciona Clave</option>
                                            <option value="MAT">MAT -Material</option>
                                            <option value="EQH">EQH -Equipo/Maquinaria</option>
                                            <option value="IND">IND -Indirectos</option>
                                            <option value="MO">MO -Mano de Obra</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#dc3545',
                    preConfirm: () => {
                        this.nombreRequisicion = document.getElementById("nombreRequisicion").value;
                        this.fechaGeneracion = document.getElementById("fechaGeneracion").value;
                        this.clave = document.getElementById("Clv").value;

                        if (!this.nombreRequisicion || !this.fechaGeneracion || this.clave === "Selecciona Clave") {
                            Swal.showValidationMessage('Por favor completa todos los campos');
                            return false;
                        }
                        return true;
                    }
                });

                if (formValues) {
                    // Lógica para solo "Agregar"
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Requisicion Agregada'
                    });
                    this.newRequisicionAuto();
                }
            }else{
                const { value: formValues } = await Swal.fire({
                    title: "Nueva Requisicion",
                    html: `
                        <div class="col">
                            <hr/>
                            <form id="requisicionForm">
                                <div class="row form-group mx-0 my-3">
                                    <div class="col-6 d-flex flex-column">
                                        <label class="text-start py-2" for="FolioReq">Folio de la Requisicion:</label>
                                        <input type="text" class="form-control" id="FolioReq" placeholder="Ingrese el Folio de la Requisicion" required>
                                    </div>
                                    <div class="col-6 d-flex flex-column">
                                        <label class="text-start py-2" for="HojaReq">Hoja de la Requisicion:</label>
                                        <input type="text" class="form-control" id="HojaReq" placeholder="Ingrese la Hoja de la Requisicion" required>
                                    </div>
                                </div>
                                <div class="row form-group mx-0 my-3">
                                    <div class="col d-flex flex-column">
                                        <label class="text-start py-2" for="nombreRequisicion">Nombre de la Requisición</label>
                                        <input type="text" class="form-control" id="nombreRequisicion" placeholder="Ingrese el nombre de la requisición" required>
                                    </div>
                                </div>
                                <div class="row form-group mx-0 my-3">
                                    <div class="col d-flex flex-column">
                                        <label class="text-start py-2" for="fechaGeneracion">Fecha de Generación</label>
                                        <input type="date" class="form-control" id="fechaGeneracion" required>
                                    </div>
                                </div>
                                <div class="row form-group mx-0 my-3">
                                    <div class="col d-flex flex-column">
                                        <label for="Clv" class="text-start py-2">Clave</label>
                                        <select class="form-select" aria-label="Default select example" id="Clv">
                                            <option>Selecciona Clave</option>
                                            <option value="MAT">MAT -Material</option>
                                            <option value="EQH">EQH -Equipo/Maquinaria</option>
                                            <option value="IND">IND -Indirectos</option>
                                            <option value="MO">MO -Mano de Obra</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <hr/>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#dc3545',
                    preConfirm: () => {
                        this.nombreRequisicion = document.getElementById("nombreRequisicion").value;
                        this.fechaGeneracion = document.getElementById("fechaGeneracion").value;
                        this.clave = document.getElementById("Clv").value;
                        this.folioReq = document.getElementById("FolioReq").value;
                        this.hojaReq = document.getElementById("HojaReq").value;

                        if (!this.nombreRequisicion || !this.fechaGeneracion || !this.folioReq || !this.hojaReq || this.clave === "Selecciona Clave") {
                            Swal.showValidationMessage('Por favor completa todos los campos');
                            return false;
                        }
                        return true;
                    }
                });

                if (formValues) {
                    // Lógica para solo "Agregar"
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Requisicion Agregada'
                    });
                    this.newRequisicionManual();
                }
            }
        },
        newRequisicionAuto: function () {
            axios.post(url, { accion: 6, nombreReq: this.nombreRequisicion, fechaReq: this.fechaGeneracion, clave: this.clave, obra: localStorage.getItem("obraActiva") }).then(response => {
                console.log(response.data);
            });
        },
        newRequisicionManual: function () {
            axios.post(url, { accion: 7, nombreReq: this.nombreRequisicion, fechaReq: this.fechaGeneracion, clave: this.clave, folio: this.folioReq, hoja: this.hojaReq, obra: localStorage.getItem("obraActiva") }).then(response => {
                console.log(response.data);
            });
        }
    },
    created: function () {
        this.listarObras();
        this.listarRequisiciones(localStorage.getItem("obraActiva"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
        this.infoObraActiva(localStorage.getItem("obraActiva"));
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
    },
    computed: {

    }
});