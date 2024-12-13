var url = "bd/crud_presionDetail.php";
var url2 = ".";

const appRequesition = new Vue({
    el: "#AppPresionDetail",
    data: {
        users: [],
        presiones: [],
        obras: [],
        obraActiva: [],
        NameUser: "",
        semana: "",
        dia: "",
        PagoParcial: "",
        FechaPago: "",
        BancoPago: "",
        timeNow: "",
        estatus: "",
        infoHojaPression: [],
        itemsPresion: []
    },
    methods: {
        consultarUsuario: function (user_id) {
            axios.post(url, { accion: 1, id_user: user_id }).then(response => {
                this.users = response.data;
                this.NameUser = this.users[0].user_name;
                console.log(this.users);
            });
        },
        listarObras: function () {
            axios.post(url, { accion: 2 }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        cargarDatosPresion: function (IdPresion) {
            axios.post(url, { accion: 3, idPresion: IdPresion, dia: this.dia, semana: this.semana }).then(response => {
                console.log(response.data);
                this.presiones = response.data;
                console.log(this.presiones);
            });
        },
        infoObraActiva: function (obrasId) {
            axios.post(url, { accion: 4, obra: obrasId }).then(response => {
                this.obraActiva = response.data;
                console.log(this.obraActiva);
            });
        },
        irObra(idObra) {
            localStorage.setItem("obraActiva", idObra);
            window.location.href = url2 + "/obras.php";
        },
        asignarDiaySamana() {
            this.semana = localStorage.getItem("Semana");
            this.dia = localStorage.getItem("Dia");
        },
        ordenarDatosPresion(dataArray) {
            var auxRow = {
                'clave': "",
                'requisicion': "",
                'proveedor': "",
                'concepto': [],
                'adeudo': "",
                'neto': "",
                'observaciones': [],
                'formaPago': ""
            };
            var AuxArray = [];

            for (var i = 0; i < dataArray.length; i++) {

            }
        },
        cargarDataTable: function () {
            let table = new DataTable('#example', {
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
        pagarItem: async function (idHoja, fecha, banco) {
            const swalWithBootstrapButtons = await Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: "¿Aprobaras este este Concepto para pago?",
                text: "Esta operacion no se puede revertir",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "SI",
                cancelButtonText: "NO",
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    this.marcarpagado(idHoja, fecha, banco);
                    swalWithBootstrapButtons.fire({
                        title: "Pagado",
                        text: "El articulo se marco como pagado",
                        icon: "success"
                    });
                } 
            });
        },
        marcarpagado: function(idHoja, fecha, banco){
              //alert("Agregado"+id+parcial+" "+fecha+" "+banco);
              var estatus = "PAGADO";
              axios.post(url, { accion: 5, idHoja:idHoja , fechaPago: fecha, bancoPago: banco, status: estatus}).then(response => {
                  console.log(response.data);
              });
        },
        imprimirReq: function(NumReq,clave,id_hoja){
             axios.post(url, { accion: 8, idHoja: id_hoja}).then(response => {
                this.infoHojaPression = response.data[0]['infoHoja'];
                this.itemsPresion = response.data[0]['items'];
                 console.log(response.data);
                  generarPDFRequisicion(
                    NumReq, // Número de la requisición
                    clave, // Clave de la requisición
                    this.infoHojaPression, // Información de la Hoja
                    this.NameUser, // Nombre del usuario
                    this.itemsPresion, // Items de la Hoja
                    this.obras[0] // Información de la obra
                ); 
             });
        },
        
        exportarExcel: function () {
            axios.post(url, { accion: 6, datos: JSON.stringify(this.presiones), namePres: this.obraActiva[0].obras_nombre ,export: "" }, { responseType: 'blob' })
                .then(response => {
                    // Crear un objeto URL para el blob
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    // Crear un enlace para descargar el archivo
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'PRESIONES DE LA SEMANA "'+this.semana+'" Y DIA "'+this.dia+'" DE LA OBRA "'+this.obraActiva[0].obras_nombre+'".xls'); // Nombre del archivo que se descargará
                    // Agregar el enlace al DOM
                    document.body.appendChild(link);
                    // Hacer clic en el enlace para iniciar la descarga
                    link.click();
                    // Limpiar el DOM
                    link.remove();
                })
                .catch(error => {
                    console.error('Error al descargar el archivo:', error);
                });
        },
        cerrarPresion: async function () {
            const swalWithBootstrapButtons = await Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: "¿Quieres cerrar esta presion?",
                text: "Esta operacion no se puede revertir",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "SI",
                cancelButtonText: "NO",
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closePresion(localStorage.getItem("IdPresion"));
                    swalWithBootstrapButtons.fire({
                        title: "CARRADA",
                        text: "La presion a sido cerrada con exito.",
                        icon: "success"
                    }).then(() => {
                        window.location.reload();
                    });
                } 
            });
        },
        closePresion: function (idPresion) {
            axios.post(url, { accion: 7, idPresion: idPresion}).then(response => {
                console.log(response.data);
            });
        },
        irDireecion: function(){
            window.location.href = url2 + "/direccion.php";
        },
        consultarEstatus: function(idPresion){
            axios.post(url, { accion: 9, idPresion: idPresion}).then(response => {
                this.estatus = response.data[0]['presiones_estatus'];
                console.log(response.data[0]['presiones_estatus']);
            });
        }
    },
    created: function () {
        this.listarObras();
        this.asignarDiaySamana();
        this.infoObraActiva(localStorage.getItem("obraActiva"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
        this.cargarDatosPresion(localStorage.getItem("IdPresion"));
        this.consultarEstatus(localStorage.getItem("IdPresion"));
        this.cargarDataTable();
    },
    computed: {

    }
});