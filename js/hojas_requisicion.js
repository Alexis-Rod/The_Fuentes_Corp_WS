var url = "bd/crud_hojas_requisicion.php";
var url2 = ".";

const appRequesition = new Vue({
    el: "#AppHojas",
    data: {
        requisiciones: [],
        hojas: [],
        presiones: [],
        obras: [],
        obrasLista: [],
        requisicion: "",
        NameUser: "",
        gastosTotalPresion: 0,
        nombreRequisicion: "",
        fechaGeneracion: "",
        clave: ""
    },
    methods: {
        ConsultarItemHoja: async function (idHoja) {
            localStorage.setItem("idHoja", idHoja);
            localStorage.setItem("validate", false);
            window.location.href = url2 + "/items_requisicion.php";
        },
        infoObraActiva: function (obrasId) {
            axios.post(url, { accion: 3, obra: obrasId }).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        listarHojas: function (idRq) {
            axios.post(url, { accion: 1, IdReq: idRq }).then(response => {
                this.hojas = response.data;
                console.log(this.hojas);
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
        addHoja: function () {
            window.location.href = url2 + "/nueva_hoja.php";
        },
        infoReqActiva: function(ReqId){
            axios.post(url, { accion: 7, IdReq: ReqId }).then(response => {
                this.requisiciones = response.data;
                console.log(this.requisiciones);
            });
        },
        irDireecion: function(){
            window.location.href = url2 + "/direccion.php";
        }
    },
    created: function () {
        this.listarObras();
        this.infoObraActiva(localStorage.getItem("obraActiva"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
        this.infoReqActiva(localStorage.getItem("idRequisicion"));
        this.listarHojas(localStorage.getItem("idRequisicion"));
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