var url = "bd/crud_Presiones.php";

const appRequesition = new Vue({
    el: "#AppPresion",
    data: {
        presiones: [],
        users: [],
        obras: [],
        obrasLista: [],
        presion: "",
        semana: "",
        dia: "",
        clave: "",
        NameUser: "",
        alias: "",
        timeNow: ""
    },
    methods: {
        ConsultarPresion: async function (idPresion, Accion, week, day) {
            console.log(idPresion + " " + Accion);
            switch (Accion) {
                case 1:
                    localStorage.setItem("IdPresion", idPresion);
                    window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/requisiciones.php";
                    break;
                case 2:
                    localStorage.setItem("Semana", week);
                    localStorage.setItem("Dia", day);
                    localStorage.setItem("IdPresion", idPresion);
                    window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/presiones_detalles.php";
                    break;
            }

        },
        NewPression: async function () {
            const date = new Date(this.getCurrentDate()); // 1 de enero de 2023
            const numweek = this.getWeekNumber(date);
            const dayName = this.getDayOfWeek(date);
            const { value: formValues } = await Swal.fire({
                title: "Nueva Presion",
                html: `
                    <div class="col">
                    <hr/>
                        <div class="row form-group mx-0 my-3">
                            <div class="col d-flex flex-column">
                                <label class="text-start py-2" for="Sem_Press">Semana</label>
                                 <input type="number" value="`+ numweek + `" class="form-control" min="1" max="52" id="Sem_Press" name="Sem_Press" disabled>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col d-flex flex-column">
                                <label class="text-start py-2" for="Day_Press">Dia</label>
                                    <input type="text" value="`+ dayName + `" class="form-control" id="Day_Press" name="Day_Press" disabled>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col d-flex flex-column">
                                <label class="text-start py-2" for="Alias">Alias</label>
                                <select class="form-select" aria-label="Default select example" id="Alias">
                                    <option> Selecciona Alias</option>
                                    <option value="Acarreo">Acarreo</option>
                                    <option value="Indirectos">Indirectos</option>
                                    <option value="Maquinaria">Maquinaria</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col d-flex flex-column">
                                <label for="Clv" class="text-start py-2">Clave</label>
                                <select class="form-select" aria-label="Default select example" id="Clv">
                                    <option> Selecciona Clave</option>
                                    <option value="MAT">MAT -Material</option>
                                    <option value="EQH">EQH -Equipo/Maquinaria</option>
                                    <option value="IND">IND -Indirectos</option>
                                    <option value="MO">MO -Mano de Obra</option>
                                </select>
                            </div>
                        </div>
                    <hr/>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                    return [
                        this.semana = document.getElementById("Sem_Press").value,
                        this.dia = document.getElementById("Day_Press").value,
                        this.clave = document.getElementById("Clv").value,
                        this.alias = document.getElementById("Alias").value,
                    ];
                }
            });
            if (this.semana == 0 || this.dia == "Selecciona Dia") {
                Swal.fire({
                    icon: 'info',
                    title: 'Datos Incompletos',
                });
            }
            else {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Presion Agregada'
                })
                this.agregarPresion();
            }
        },
        listarPresiones: function (obrasId) {
            axios.post(url, { accion: 1, obra: obrasId }).then(response => {
                this.presiones = response.data;
                console.log(this.presiones);
            });
        },
        consultarUsuario: function (user_id) {
            axios.post(url, { accion: 2, id_user: user_id }).then(response => {
                this.users = response.data;
                this.NameUser = this.users[0].user_name;
                console.log(this.users);
            });
        },
        agregarPresion: function () {
            const fecha = new Date();
            var mes = fecha.getMonth() + 1;
            var dia = fecha.getDate();
            this.timeNow = this.getTime();
            mes = mes < 10 ? '0' + mes : mes;
            dia = dia < 10 ? '0' + dia : dia;
            var fechaActual = fecha.getFullYear() + "-" + mes + "-" + dia;
            console.log(fechaActual);
            axios.post(url, { accion: 3, alias: this.alias, semana: this.semana, dia: this.dia, clave: this.clave, time: this.timeNow, fecha: fechaActual, user_creado: this.NameUser, obra: localStorage.getItem("obraActiva") }).then(response => {
                console.log(response.data);
            });
        },
        infoObraActiva: function (obrasId) {
            axios.post(url, { accion: 4, obra: obrasId }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        listarObras: function () {
            axios.post(url, { accion: 5 }).then(response => {
                this.obrasLista = response.data;
                console.log(this.obrasLista);
            });
        },
        irPresion: function (idPresion) {
            localStorage.setItem("obraActiva", idPresion);
            window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/presiones.php";
        },
        getWeekNumber: function (date) {
            const onejan = new Date(date.getFullYear(), 0, 1);
            const week = Math.ceil((((date - onejan) / 86400000) + onejan.getDay() + 1) / 7);
            return week;
        },
        getDayOfWeek: function (date) {
            const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            return days[date.getDay() + 1];
        },
        getCurrentDate: function () {
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        getTime: function () {
            const currentTime = new Date();
            const hours = currentTime.getHours();
            const minutes = currentTime.getMinutes();
            const seconds = currentTime.getSeconds();

            const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

           return formattedTime;
        },
        cargarDataTable: function () {

        }
    },
    mounted: function () {
        this.$nextTick(function () {
            var table = $('#example').DataTable({
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
            this.infoObraActiva(localStorage.getItem("obraActiva"));
            this.listarPresiones(localStorage.getItem("obraActiva"));
            this.consultarUsuario(localStorage.getItem("NameUser"));

        });
    },
    computed: {

    }
});
