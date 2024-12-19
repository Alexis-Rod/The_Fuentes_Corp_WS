var url = "bd/crud_all_presiones.php";
var url2 = ".";

const appRequesition = new Vue({
    el: "#AppIndex",
    data: {
        users: [],
        obras: [],
        NameUser: "",
        presiones: [],
        adeudo: "",
        comentarios: ""
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
                this.listarPresiones();
                //console.log(this.obras);
            });
        },
        irObra(idObra) {
            localStorage.setItem("obraActiva", idObra);
            window.location.href = url2 + "/obras.php";
        },
        irDireecion: function () {
            window.location.href = url2 + "/direccion.php";
        },
        listarPresiones: function () {
            console.log(this.obras);
            axios.post(url, { accion: 3, obras: JSON.stringify(this.obras) }).then(response => {
                this.presiones = response.data;
                console.log(this.presiones);
            });
        },
        quitarEspacios: function (cadena) {
            return cadena.replace(/\s+/g, ''); // Elimina todos los espacios
        },
        convertirADecimal: function (cadena) {
            // Elimina el símbolo de dólar y convierte la cadena a número
            return parseFloat(cadena.replace('$', '').replace(',', ''));
        },
        autoriar: async function (idHoja, adeudo) {
            const { value: formValues, isConfirmed, isDenied } = await Swal.fire({
                title: "¿Desea Autorizar este Concepto?",
                html: `
                    <div class="col">
                        <div class="row form-group mx-0 my-3">
                            <div class="col">
                                <label for="adeudo" class="form-label">Adeudo a Pagar</label>
                                <input type="number" min="0" class="form-control" id="adeudo" value=`+ adeudo + `>
                                
                            </div>
                        </div>
                        <div class="row form-group mx-0 my-3">
                            <div class="col">
                               <label for="comentarios" class="form-label">Comentarios </label>
                               <textarea class="form-control" id="comentarios" rows="3"></textarea>
                            </div>
                        </div>
                        <hr />
                    </div>
                `,
                focusConfirm: false,
                showDenyButton: true,
                confirmButtonText: 'Autorizar',
                confirmButtonColor: '#0d6efd',
                denyButtonText: 'Rechazar', // Cambia el texto del botón de cancelar
                preConfirm: () => {
                    const productoValue = document.getElementById("comentarios").value;
                    console.log(productoValue);
                    this.comentarios = document.getElementById("comentarios").value;;
                    console.log(this.comentarios);
                    this.adeudo = document.getElementById("adeudo").value;
                    if (productoValue.length > 200) {
                        Swal.showValidationMessage('El campo Producto no puede exceder los 200 caracteres.');
                        return false;
                    }
                    return true;
                }
            });
            if (isConfirmed) {
                // Acción para el botón "Autorizar"
                this.autorizado(idHoja, this.adeudo);
                Swal.fire({
                    title: "Autorizado",
                    text: "El articulo fue Aprovado.",
                    icon: "success"
                }).then(() => {
                    this.listarObras();
                });
                // Aquí puedes agregar la lógica para manejar la autorización
            } else if (isDenied) {
                // Acción para el botón "Rechazar"
                this.comentarios = document.getElementById("comentarios").value;
                this.rechazado(idHoja, this.comentarios);
                console.log(this.comentarios);
                Swal.fire({
                    title: "No autorizado",
                    text: "El articulo no se aprobo para pago.",
                    icon: "error"
                }).then(() => {
                    this.listarObras();
                });
                // Aquí puedes agregar la lógica para manejar el rechazo
            }
        },
        autorizado: function (idHoja, adeudo) {
            //alert("Agregado"+id+parcial+" "+fecha+" "+banco);
            var estatus = "AUTORIZADO";
            //this.timeNow = this.getTime();
            axios.post(url, { accion: 4, idHoja: idHoja, parcial: adeudo, status: estatus, autorizado: true }).then(response => {
                console.log(response.data);
            });
        },
        rechazado: function (idHoja, comentarios) {
            //alert("Agregado"+id+parcial+" "+fecha+" "+banco);
            var estatus = "RECHAZADO";
            //this.timeNow = this.getTime();
            axios.post(url, { accion: 4, idHoja: idHoja, coments: comentarios, status: estatus, autorizado: false }).then(response => {
                console.log(response.data);
            });
        },
        formatearMoneda: function (cadena) {
            // Convertir la cadena a un número
            let numero = parseFloat(cadena);
            // Verificar si la conversión fue exitosa
            if (isNaN(numero)) {
                return null; // O puedes lanzar un error si prefieres
            }
            // Formatear el número como moneda en pesos mexicanos
            return "$ " + numero.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        cambiarBooleano: function (valor, indice, index) {
            this.presiones[index].Presion_Obra[indice].showDetail = !valor; // Devuelve el valor opuesto
            if (!valor) {
                this.presiones[index].Presion_Obra[indice].atrClass = "inline-block fs-6";
                this.presiones[index].Presion_Obra[indice].strStyle = "max-width: 150px;";
            }
            else {
                this.presiones[index].Presion_Obra[indice].atrClass = "inline-block text-truncate fs-6";
                this.presiones[index].Presion_Obra[indice].strStyle = "max-width: 100px;";
            }

        }
    },
    created: function () {
        this.listarObras();
        this.consultarUsuario(localStorage.getItem("NameUser"));
    },
    computed: {

    }
});