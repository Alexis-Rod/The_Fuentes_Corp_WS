var url = "bd/crud_enlazar_requisiciones.php";
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
        clave: ""
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
        consultarInfoPresion: function (idPresion) {
            axios.post(url, { accion: 6, idPresion: idPresion }).then(response => {
                this.presiones = response.data;
                console.log(this.presiones);
            });
        },
        enlazarConPresion: async function (idHoja, idReq, NameReq, NumHoja) {
            const { value: formValues } = await Swal.fire({
                title: "¿Seguro que quieres Continuar?",
                text: 'Se enlazara la Presion "'+this.presiones[0].presiones_nombre+'" con la Requisicion  "'+NameReq+' Hoja N° '+NumHoja+ '" El cambio ya no se podra revertir',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Enlazarlo"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.linkPresionToRequisicion(localStorage.getItem("IdPresion"), idHoja, idReq);
                    Swal.fire({
                        title: "Enlazado",
                        text: "la presion se enlazo correctamente",
                        icon: "success"
                    }).then(() => {
                        // Se recarga la pagian
                        window.location.href = url2 + "/enlazar_requisiciones.php"; // Cambia esto por la URL de tu página
                    });
                }
            });
        },
        linkPresionToRequisicion: function(idPresion, idHoja, idReq){
            axios.post(url, { accion: 7, idPresion: idPresion , idReq: idReq, idHoja: idHoja}).then(response => {
                console.log(response.data);
            });
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
        this.listarRequisiciones(localStorage.getItem("obraActiva"));
        this.consultarUsuario(localStorage.getItem("NameUser"));
        this.infoObraActiva(localStorage.getItem("obraActiva"));
        this.consultarInfoPresion(localStorage.getItem("IdPresion"));
    },
    computed: {

    }
});