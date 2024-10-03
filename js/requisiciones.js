var url = "bd/crud_Requisiciones.php";

const appRequesition = new Vue({
    el: "#AppPresion",
    data: {
        requisiciones: [],
        presiones: [],
        obras: [],
        requisicion: "",
        NameUser: "",
        gastosTotalPresion: 0
    },
    methods: {
        ConsultarItemRq: async function (idRq) {
            localStorage.setItem("idRequisicion", idRq);
            window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/items_requisicion.php";
        },
        listarRequisiciones: function (idPresion) {
            axios.post(url, { accion: 1, id_Presion: idPresion }).then(response => {
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
        cargarDatosPresion: function (idPresion) {
            axios.post(url, { accion: 3, id_Presion: idPresion }).then(response => {
                this.presiones = response.data;
                console.log(this.presiones);
            });
        },
        obtenerTotalPresion: function (idPresion) {
            axios.post(url, { accion: 4, id_Presion: idPresion }).then(response => {
                this.gastosTotalPresion = response.data[0].totalPresion;;
                console.log(this.gastosTotalPresion);
            });
        },
        listarObras: function () {
            axios.post(url, { accion: 5 }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        irPresion(idPresion) {
            localStorage.setItem("obraActiva", idPresion);
            window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/presiones.php";
        }
    },
    created: function () {
        this.listarObras();
        this.listarRequisiciones(localStorage.getItem("IdPresion"));
        this.obtenerTotalPresion(localStorage.getItem("IdPresion"));
        this.cargarDatosPresion(localStorage.getItem("IdPresion"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
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