var doc = new jsPDF('l', 'mm', 'a4', true);
var image = new Image();
var watermark = new Image();
image.src = "./images/LogoFuentes.png";
watermark.src = "./images/watermark.jpg"
var ultimapagina = false;

function generarPDFRequisicion(requisicion, NameUser, itemsOrdenArray, obras) {
    console.log('primera llamada');

    var pages = createPages(convertToArrayStrings(itemsOrdenArray));

    pages.forEach((ArrayItems, index) => {
        if (index == pages.length - 1) {
            ultimapagina = true
        }
        //crea el encabezado de la pagina
        creaEncabezadoOrden();
        //datos de la empresa
        datosEmpresa(requisicion);
        //datos del proveedor
        datosProveedor(requisicion);
        //datos complementarios
        complementarios(requisicion, obras);
        //se crea la tabla de los items
        itemDeOrden(ArrayItems, requisicion, ultimapagina);
        //Crea el pie de pagina de la orden
        creaPieDeOrden(NameUser, requisicion, index + 1, pages.length);
        if (index < pages.length - 1) {
            doc.addPage('a4', 'l');
        }
    });
    doc.output('dataurlnewwindow');
}
function creaEncabezadoOrden() {
    doc.setFontSize(12);
    doc.setFontStyle('bold');
    doc.addImage(watermark, 'JPG', 0, 0, 297, 210,);
    doc.addImage(image, 'PNG', 10, 10, 25, 25,);
    doc.text('ORDEN DE COMPRA', 145, 18, 'center');
    doc.setFontSize(10);
    const textWidth = doc.getTextWidth('AREA DE RECURSOS MATERIALES Y SERVICIOS GENERALES');
    doc.setLineWidth(0.5);
    doc.line(92, 29, 92 + textWidth, 29);
    doc.text('AREA DE RECURSOS MATERIALES Y SERVICIOS GENERALES', 145, 28, 'center');
}
function datosEmpresa(requisicion) {
    console.log('segunda llamada');
    console.log(requisicion);
    doc.setFontSize(8);
    doc.setLineWidth(0.2);
    doc.setDrawColor(0);
    doc.setFillColor(155, 168, 162);
    doc.rect(10, 37, 50, 6, 'FD');
    doc.rect(60, 37, 130, 6);
    doc.rect(190, 37, 95, 6);
    doc.rect(10, 43, 30, 12, 'FD');
    doc.rect(40, 43, 150, 12);
    doc.rect(190, 43, 95, 6);
    doc.rect(190, 49, 95, 6);
    doc.rect(10, 55, 30, 6, 'FD');
    doc.rect(40, 55, 245, 6);
    doc.rect(10, 61, 50, 6, 'FD');
    doc.rect(60, 61, 40, 6);
    doc.rect(100, 61, 30, 6, 'FD');
    doc.rect(130, 61, 50, 6);
    doc.rect(180, 61, 50, 6, 'FD');
    doc.rect(230, 61, 55, 6);

    doc.setFontStyle('normal');
    doc.text('NOMBRE DE LA EMPRESA', 35, 41, 'center');
    doc.text(requisicion.emisor_nombre, 125, 41, 'center');
    doc.text('ORDEN DE COMPRA No', 237, 41, 'center');

    doc.text('RFC', 25, 49, 'center');
    doc.text(requisicion.emisor_rfc, 115, 49, 'center');
    doc.text(requisicion.requisicion_Numero, 237, 47, 'center');
    doc.text('HOJA N°' + requisicion.requisicion_hojaNumero, 237, 53, 'center');

    doc.text('DIRECCION', 25, 59, 'center');
    doc.text(requisicion.emisor_direccion, 162, 59, 'center');

    doc.text('TELEFONO', 35, 65, 'center');
    doc.text(requisicion.emisor_telefono, 80, 65, 'center');
    doc.text('FAX', 115, 65, 'center');
    doc.text(requisicion.emisor_fax, 155, 65, 'center');
    doc.text('C.P', 205, 65, 'center');
    doc.text(requisicion.emisor_zipCode, 257, 65, 'center');
}

function datosProveedor(requisicion) {
    console.log('Tercera llamada');
    doc.setFontSize(8);
    doc.setFillColor(177, 223, 200);
    doc.rect(10, 69, 275, 6, 'FD');
    doc.rect(10, 75, 24, 6, 'FD');
    doc.rect(34, 75, 105, 6);
    doc.rect(139, 75, 40, 6, 'FD');
    doc.rect(179, 75, 40, 6);
    doc.rect(219, 75, 20, 6, 'FD');
    doc.rect(239, 75, 46, 6);
    doc.rect(10, 81, 24, 6, 'FD');
    doc.rect(34, 81, 30, 6);
    doc.rect(64, 81, 24, 6, 'FD');
    doc.rect(88, 81, 51, 6);
    doc.rect(139, 81, 40, 6, 'FD');
    doc.rect(179, 81, 40, 6);
    doc.rect(219, 81, 20, 6, 'FD');
    doc.rect(239, 81, 46, 6);
    doc.rect(10, 87, 24, 6, 'FD');
    doc.rect(34, 87, 105, 6);
    doc.rect(139, 87, 40, 6, 'FD');
    doc.rect(179, 87, 40, 6);
    doc.rect(219, 87, 20, 6, 'FD');
    doc.rect(239, 87, 46, 6);
    doc.setFillColor(155, 168, 162);
    doc.rect(10, 93, 40, 4, 'FD');
    doc.rect(50, 93, 235, 4);

    doc.setFontStyle('normal');
    doc.text('DATOS DEL PROVEEDOR', 137, 73, 'center');

    doc.text('PROVEEDOR', 22, 79, 'center');
    doc.text(requisicion.proveedor_nombre, 86, 79, 'center');
    doc.text('CLABE INTERBANCARIA', 159, 79, 'center');
    doc.text(formatString(requisicion.proveedor_clabe), 199, 79, 'center');
    doc.text('BANCO', 229, 79, 'center');
    doc.text(requisicion.proveedor_banco, 262, 79, 'center');

    doc.text('RFC', 22, 85, 'center');
    doc.text(requisicion.proveedor_rfc, 49, 85, 'center');
    doc.text('REF BANCARIA', 76, 85, 'center');
    doc.text(formatString(requisicion.proveedor_refBanco), 113, 85, 'center');
    doc.text('N° DE CUENTA', 159, 85, 'center');
    doc.text(formatString(requisicion.proveedor_numeroCuenta), 199, 85, 'center');
    doc.text('SUCURSAL', 229, 85, 'center');
    doc.text(requisicion.proveedor_sucursal, 262, 85, 'center');

    doc.text('CORREO', 22, 91, 'center');
    doc.text(requisicion.proveedor_email, 86, 91, 'center');
    doc.text('N° TELEFONICO', 159, 91, 'center');
    doc.text(requisicion.proveedor_telefono, 199, 91, 'center');
    doc.text('MONEDA', 229, 91, 'center');
    doc.text(requisicion.requisicion_formaPago, 262, 91, 'center');

    doc.text('TERMINO DE ENTREGA', 30, 96, 'center');
    doc.text('', 167, 96, 'center');
}

function complementarios(requisicion, obras) {
    console.log('cuarta llamada');
    doc.setFontSize(8);
    doc.setFillColor(155, 168, 162);
    doc.rect(10, 99, 30, 12, 'FD');
    doc.rect(40, 99, 150, 12);
    doc.rect(190, 99, 40, 12, 'FD');
    doc.rect(230, 99, 55, 12);
    doc.rect(10, 113, 150, 4, 'F');
    doc.rect(190, 113, 40, 4, 'FD');
    doc.rect(230, 113, 55, 4);

    doc.setFontStyle('normal');
    doc.text('OBRA', 25, 106, 'center');
    doc.text(obras.obras_nombre + ", " + obras.ciudadesObras_nombre, 115, 106, 'center');
    doc.text('FECHA DE SOLICITUD', 210, 106, 'center');
    doc.text(requisicion.requisicion_fechaSolicitud, 257, 106, 'center');

    doc.text('Sirvase por este medio para suministrar los siguientes articulos', 85, 116, 'center');
    doc.text('FECHA DE ENTREGA', 210, 116, 'center');
    doc.text('', 257, 116, 'center');
}

function itemDeOrden(ArrayString, requisicion, ultimapagina) {
    console.log('quinta llamada');
    console.log(ArrayString);
    var x = 10;
    var y = 125
    var xt = 0;
    var yt = 0;

    doc.setFontSize(8);
    doc.setFillColor(155, 168, 162);
    doc.rect(10, 119, 10, 6, 'FD');
    doc.rect(20, 119, 30, 6, 'FD');
    doc.rect(50, 119, 90, 6, 'FD');
    doc.rect(140, 119, 30, 6, 'FD');
    doc.rect(170, 119, 30, 6, 'FD');
    doc.rect(200, 119, 25, 6, 'FD');
    doc.rect(225, 119, 25, 6, 'FD');
    doc.rect(250, 119, 35, 6, 'FD');

    doc.text('LOTE', 15, 123, 'center');
    doc.text('UNIDAD', 35, 123, 'center');
    doc.text('PRODUCTO', 95, 123, 'center');
    doc.text('CANTIDAD', 155, 123, 'center');
    doc.text('PRECIO UNITARIO', 185, 123, 'center');
    doc.text('IVA', 212, 123, 'center');
    doc.text('RETENCIONES', 237, 123, 'center')
    doc.text('TOTAL', 267, 123, 'center')

    ArrayString.forEach(element => {
        yt = (y + 6) - 2;
        doc.rect(10, y, 10, element.tamaño);
        doc.rect(x + 10, y, 30, element.tamaño);
        doc.rect(x + 40, y, 90, element.tamaño);
        doc.rect(x + 130, y, 30, element.tamaño);
        doc.rect(x + 160, y, 30, element.tamaño);
        doc.rect(x + 190, y, 25, element.tamaño);
        doc.rect(x + 215, y, 25, element.tamaño);
        doc.rect(x + 240, y, 35, element.tamaño);

        doc.text(element.lote.toString(), 15, yt, 'center');
        doc.text(element.unidad, (x + 10) + 15, yt, 'center');
        doc.text(element.producto, (x + 40) + 45, yt, 'center');
        doc.text(element.cantidad.toString(), (x + 130) + 15, yt, 'center');
        doc.text("$ " + addCommas(element.precio), (x + 160) + 15, yt, 'center');
        doc.text("+$ " + addCommas(element.iva), (x + 190) + 17, yt, 'center');
        doc.text("-$ " + addCommas(element.retenciones), (x + 215) + 17, yt, 'center')
        doc.text("$ " + addCommas(element.total), (x + 240) + 15, yt, 'center')

        y = y + element.tamaño;
    });

    if (ultimapagina == true) {
        doc.setFillColor(155, 168, 162);
        doc.rect(225, y, 25, 6, 'FD');
        doc.rect(250, y, 35, 6, 'FD');

        doc.text('TOTAL', 237, (y + 6) - 2, 'center');
        doc.text("$ " + addCommas(requisicion.requisicion_total), 267, (y + 6) - 2, 'center');

        if (requisicion.requisicion_observaciones != "") {
            doc.setFillColor(255, 234, 0);
            doc.rect(10, y + 10, 200, 12, 'F');


            doc.text('NOTA:' + requisicion.requisicion_observaciones, 15, ((y + 10) + 7) - 2, 'left');
        }
    }
}

function creaPieDeOrden(NameUser, requisicion, pagina, paginas) {
   /*  console.log('sexta llamada');
    doc.text('ELABORO', 55, 180, 'center');
    doc.line(20, 190, 100, 190);
    doc.text(NameUser, 55, 193, 'center');
    doc.text('SELLO Y FIRMA DE LA EMPRESA', 230, 180, 'center');
    doc.line(190, 190, 270, 190);
    doc.text(requisicion.emisor_nombre, 230, 193, 'center'); */
    doc.setFontStyle('bold');
    doc.text("Esta orden la elaboro "+NameUser+" el dia "+requisicion.requisicion_fechaSolicitud+" a las 08:49", 180, 200, 'left');
    doc.text("Pagina " + pagina + " de " + paginas, 143, 200, 'center');
}

function convertToArrayStrings(itemsOrdenArray) {
    var ArrayStringItems = [];
    var tamano = 0;

    for (var i = 0; i < itemsOrdenArray.length; i++) {
        if (itemsOrdenArray[i].itemRequisicion_producto.length > 50) {
            itemsOrdenArray[i].itemRequisicion_producto = convertToMultilines(itemsOrdenArray[i].itemRequisicion_producto);
            tamano = 12;
        }
        else {
            tamano = 6;
        }
        var JsonAux = {
            'lote': (i + 1),
            'unidad': itemsOrdenArray[i].itemRequisicion_unidad,
            'producto': itemsOrdenArray[i].itemRequisicion_producto,
            'cantidad': itemsOrdenArray[i].itemRequisicion_cantidad,
            'precio': itemsOrdenArray[i].itemRequisicion_precio,
            'iva': itemsOrdenArray[i].itemRequisicion_iva,
            'retenciones': itemsOrdenArray[i].itemRequisicion_retenciones,
            'total': Number.parseFloat((((Number.parseFloat(itemsOrdenArray[i].itemRequisicion_cantidad) * Number.parseFloat(itemsOrdenArray[i].itemRequisicion_precio)) + Number.parseFloat(itemsOrdenArray[i].itemRequisicion_iva)) - Number.parseFloat(itemsOrdenArray[i].itemRequisicion_retenciones))).toFixed(2),
            'tamaño': tamano
        }
        console.log(JsonAux);
        ArrayStringItems.push(JsonAux);
    }
    return ArrayStringItems;
}

function createPages(ArrayString) {
    console.log(ArrayString);
    var pages = [];
    var page = [];
    var i = 0;
    ArrayString.forEach((item) => {
        i = i + item.tamaño;
        if (i < 42) {
            page.push(item);

        }
        else {
            page.push(item);
            pages.push(page);
            i = 0;
            page = [];
        }
    });
    if (i < 42) {
        pages.push(page);
    }
    console.log("paginas");
    console.log(pages);
    return pages;
}

function convertToMultilines(cadena) {
    var cadenaAuxiliar = "";
    var indexMultilines = 0;

    for (i = 0; i < cadena.length; i++) {
        if (indexMultilines == 51) {
            cadenaAuxiliar = cadenaAuxiliar + "\n";
            indexMultilines = 0;
        }
        cadenaAuxiliar = cadenaAuxiliar + cadena[i];
        indexMultilines++;
    }

    return cadenaAuxiliar;
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function formatString(inputString) {
    const formattedString = inputString.match(/.{1,4}/g).join('-');
    return formattedString;
}





