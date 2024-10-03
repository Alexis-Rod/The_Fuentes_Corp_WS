var url = "bd/crud_index.php";

const appRequesition = new Vue({
    el: "#AppIndex",
    data: {
      users: [],
      obras: [],
      NameUser: ""
    },
    methods: {
        consultarUsuario: function(user_id){
            axios.post(url, { accion: 1, id_user: user_id}).then(response => {
                this.users = response.data;
                this.NameUser = this.users[0].user_name;
                console.log(this.users);
            });
        },
        listarObras: function(){
            axios.post(url, { accion: 2}).then(response => {
                this.obras = response.data;
                console.log(this.obras);
            });
        },
        irPresion(idPresion)
        {
            localStorage.setItem("obraActiva", idPresion);
            window.location.href = "http://localhost/FuentesCorp/FuentesCorp-Platform/presiones.php";
        }
    },
    created: function () {
        this.listarObras();
        this.consultarUsuario(localStorage.getItem("NameUser"));
    },
    computed: {

    }
});