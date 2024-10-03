var url = "bd/crud_presionDetail.php";

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
        timeNow: ""
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
        cargarDatosPresion: function (obrasId) {
            axios.post(url, { accion: 3, obra: obrasId, dia: this.dia, semana: this.semana }).then(response => {
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
        irPresion(idPresion) {
            localStorage.setItem("obraActiva", idPresion);
            window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/presiones.php";
        },
        asignarDiaySamana() {
            this.semana = localStorage.getItem("Semana");
            this.dia = localStorage.getItem("Dia");
        },
        ordenarDatosPresion(dataArray)
        {
            var auxRow = {
                'clave' : "",
                'requisicion' : "",
                'proveedor' : "",
                'concepto' : [],
                'adeudo' : "",
                'neto' : "",
                'observaciones' : [],
                'formaPago' : ""
            };
            var AuxArray = [];

            for(var i = 0;i < dataArray.length; i++)
            {
                
            }
        },
        cargarDataTable: function()
        {
            let table = new DataTable('#example',{
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
        AplicarItem: function(id,parcial, fecha, banco){
            //alert("Agregado"+id+parcial+" "+fecha+" "+banco);
            var estatus = "LIQUIDADO";
            this.timeNow = this.getTime();
            if(parcial > 0)
            {
                estatus = "PAGO PARCIAL"
            }
            axios.post(url, { accion: 5,  idReq: id , time: this.timeNow, parcial: "0", fechaPago: fecha, bancoPago: banco, status: estatus }).then(response => {
                console.log(response.data);
            });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: 'success',
                title: 'Se Actualizo los datos'
            })
        },
        exportarExcel: function()
        {
            axios.post(url, { accion: 6 , export: ""}).then(response => {
                console.log(response.data);
            });
        }
    },
    created: function () {
        this.listarObras();
        this.asignarDiaySamana();
        this.infoObraActiva(localStorage.getItem("obraActiva"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
        this.cargarDatosPresion(localStorage.getItem("obraActiva"));
        this.cargarDataTable();
    },
    computed: {

    }
});