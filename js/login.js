var url = "./bd/LoginAcces.php";
var url2 = ".";

const appLogin = new Vue({
    el: "#LoginApp",
    data: {
        User: "",
        Password: "",
        Credenciales: []
    },
    methods: {
        EntarLogin: async function (User, Password) {
            if (User == "" || Password == "") {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "warning",
                    title: "Datos incompletos"
                });
            }
            else {
                this.login();
            }
        },
        login: function () {
            axios.post(url, { user: this.User, password: this.Password }).then(response => {
                console.log("La respuesta es: "+response.data);
                this.Credenciales = response.data;
                if (this.Credenciales.bandera == "true") {
                    localStorage.setItem("NameUser",this.Credenciales.user_id);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Autenticacion Correcta"
                    }).then(()=>{
                        window.location.href = url2+"/index.php";
                    });
                }
                else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "error",
                        title: "Verifica la informacion"
                    });

                }
            });
        }
    },
    created: function () { },
    computed: {}
});