let contador = 0;

function mayus(e) {
  e.value = e.value.toUpperCase();
}

/* Loader */
$(document).ready(function () {
  setTimeout(() => {
    document
      .getElementById("cont-loader")
      .setAttribute("style", "display:none;");
  }, "1000");
});

/* -------------- Modulo Usuario ------------------ */

/*----------------- Listar Usuarios -------------*/
$(document).ready(function () {
  $("#tablaUsuario").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarUsuarios",
    pageLength: 4,
    createdRow: function (row, data, dataIndex) {
      if (data[9] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass('table-success');
      }
    },
    columnDefs: [
      {
        orderable: false,
        targets: 8,
        render: function (data, type, row, meta) {
          if (row[9] == 1) {
            let botones =
              `
                    <button type="button" class="btn btn-primary btn-sm" onclick="verUsuario(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionUsuario(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-danger btn-sm" onclick="inactivarUsuario(` +
              row[0] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                <button type="button" class="btn btn-primary btn-sm" onclick="verUsuario(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;

               <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionUsuario(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;

               <button type="button" class="btn btn-success btn-sm" onclick="inactivarUsuario(` +
              row[0] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});

/* -------------- Agregar Usuarios ------------------ */

/* -------------- Agregar Usuario ------------------ */
$("#formRegistrarUsuario")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let cedula = document.getElementById("cedula").value;
    let nombre = document.getElementById("nombre").value;
    let apellido = document.getElementById("apellido").value;
    let correo = document.getElementById("correo").value;
    let contrasena = document.getElementById("contrasena").value;
    let confirmar_contrasena = document.getElementById(
      "confirmar_contrasena"
    ).value;
    let usuario = document.getElementById("usuario").value;
    let rol = document.getElementById("rol").value;
    let estatus = document.getElementById("estatus").value;

    /* comprobar campos vacios */
    if (
      cedula == "" ||
      nombre == "" ||
      apellido == "" ||
      correo == "" ||
      contrasena == "" ||
      confirmar_contrasena == "" ||
      usuario == "" ||
      estatus == ""
    ) {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    if (contrasena != confirmar_contrasena) {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Las contraseñas no coinciden.",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarUsuario",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          $("#tablaUsuario").DataTable().ajax.reload();

          document.getElementById("formRegistrarUsuario").reset();
          //$("#radiosfoto").click();

          $("#modalAgregarUsuario").modal("hide");
        } else {
          Swal.fire({
            icon: "warning",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });

/* -------------- Ver Usuario ------------------ */
function verUsuario(id) {
  let id_usuario = id;

  $.ajax({
    url: "index.php?page=verUsuario",
    type: "post",
    dataType: "json",
    data: {
      id_usuario: id_usuario,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("cedula_usuario").innerHTML =
          "Cedula: " + response.data.cedula;
        document.getElementById("nombre_usuario").innerHTML =
          response.data.nombre + " " + response.data.apellido;
        document.getElementById("foto_usuario").innerHTML =
          "Foto: " + response.data.foto;
        document.getElementById("usuario_usuario").innerHTML =
          "Usuario: " + response.data.usuario;
        document.getElementById("apellido_usuario").innerHTML =
          "Apellido: " + response.data.apellido;
        document.getElementById("correo_usuario").innerHTML =
          "Correo: " + response.data.correo;

        document.getElementById("fecha_usuario").innerHTML =
          "Fecha: " + response.data.fecha;

        let ruta_img = "foto_usuario/" + response.data.foto;

        document.getElementById("foto_usuario").setAttribute("src", ruta_img);

        if (response.data.estatus == 1) {
          document.getElementById("estatus_usuario").innerHTML =
            "Estado: <button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_usuario").innerHTML =
            "Estado: <button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarUsuario").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/*Listar datos para actualizacion de usuario*/
function listarActualizacionUsuario(id) {
  let id_usuario = id;

  let id_usuario_update = document.getElementById("id_usuario_update").value;
  let cedula = document.getElementById("cedula_update").value;
  let nombre = document.getElementById("nombre_update").value;
  let apellido = document.getElementById("apellido_update").value;
  let usuario = document.getElementById("usuario_update").value;
  let contrasena = document.getElementById("contrasena_update").value;
  let correo = document.getElementById("correo_update").value;
  let estatus = document.getElementById("estatus_update").value;
  let input_id_usuario = document.getElementById("id_usuario");

  let listar = "listar";

  $.ajax({
    url: "index.php?page=verUsuario",
    type: "post",
    dataType: "json",
    data: {
      id_usuario: id_usuario,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_usuario_update").value = response.data.id;
        document.getElementById("cedula_update").value = response.data.cedula;
        document.getElementById("nombre_update").value = response.data.nombre;
        document.getElementById("apellido_update").value =
          response.data.apellido;
        document.getElementById("usuario_update").value = response.data.usuario;
        document.getElementById("correo_update").value = response.data.correo;
        document.getElementById("estatus_update").value = response.data.estatus;
        document.getElementById("rol_update").value = response.data.rol;
        document
          .getElementById("img_update_preview")
          .setAttribute("src", "foto_usuario/" + response.data.foto);

        $("#modalActualizarUsuarios").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

$(document).ready(function () {
  $("#check_foto").change(function () {
    if ($(this).is(":checked")) {
      console.log("El checkbox ha sido seleccionado");
      document.getElementById("cont_input_file").removeAttribute("style");
      // Agrega aquí el código que deseas ejecutar cuando el checkbox es seleccionado
    } else {
      //console.log("El checkbox ha sido deseleccionado");
      document
        .getElementById("cont_input_file")
        .setAttribute("style", "display:none;");
      // Agrega aquí el código que deseas ejecutar cuando el checkbox es deseleccionado
    }
  });
});

$("#check_foto").is(":checked");
/* -------------- Modificar Usuario ------------------ */

$("#formActualizarUsuario")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let id_usuario_update = document.getElementById("id_usuario_update").value;
    let cedula = document.getElementById("cedula_update").value;
    let nombre = document.getElementById("nombre_update").value;
    let apellido = document.getElementById("apellido_update").value;
    let usuario = document.getElementById("usuario_update").value;
    let contrasena = document.getElementById("contrasena_update").value;
    let correo = document.getElementById("correo_update").value;
    let estatus = document.getElementById("estatus_update").value;
    let rol_update = document.getElementById("rol_update").value;
    let confirmar_contrasena_update = document.getElementById(
      "confirmar_contrasena_update"
    ).value;

    /* comprobar campos vacios */
    if (
      cedula == "" ||
      nombre == "" ||
      apellido == "" ||
      usuario == "" ||
      contrasena == "" ||
      correo == "" ||
      estatus == "" ||
      rol_update == ""
    ) {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    if (contrasena != confirmar_contrasena_update) {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Las constraseñas no coinciden.",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=modificarUsuario",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          console.log(respuesta.data);
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          document.getElementById("formActualizarUsuario").reset();

          $("#modalActualizarUsuarios").modal("hide");

          $("#tablaUsuario").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });

/* -------------- Activar e Inactivar Usuario ------------------ */
function inactivarUsuario(id) {
  var id_usuario = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado del usuario?",
    // text: "El paciente sera dado de alta y el registro quedara guardado en la traza.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarUsuario",
        type: "post",
        dataType: "json",
        data: {
          id_usuario: id_usuario,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaUsuario").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/*-----------------Listar Roles-------------*/
$(document).ready(function () {
  $("#tablaRoles").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarRoles",
    pageLength: 4,
    createdRow: function (row, data, dataIndex) {
      if (data[4] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass('table-success');
      }
    },
    columnDefs: [
      {
        orderable: false,
        targets: 3,
        render: function (data, type, row, meta) {
          if (row[4] == 1) {
            let botones =
              `
                      <button type="button" class="btn btn-primary btn-sm" onclick="verRoles(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
     
                     <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionRoles(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
     
                     <button type="button" class="btn btn-danger btn-sm" onclick="inactivarRoles(` +
              row[0] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                  <button type="button" class="btn btn-primary btn-sm" onclick="VerRoles(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
 
                 <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionRoles(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
 
                 <button type="button" class="btn btn-success btn-sm" onclick="inactivarRoles(` +
              row[0] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});

/* -------------- Agregar Roles------------------ */
// var agregar_roles;
var agregar_roles;
if ((agregar_roles = document.getElementById("agregar_roles"))) {
  agregar_roles.addEventListener("click", agregarRoles, false);

  function agregarRoles() {
    let rol = document.getElementById("rol").value;

    let estatus = document.getElementById("estatus").value;
    /* comprobar campos vacios */
    if (rol == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarRoles",
      type: "post",
      dataType: "json",
      data: {
        rol: rol,

        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formRegistrarRoles").reset();

          $("#modalAgregarRoles").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaRoles").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
$("#formRegistrarRoles")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let rol = document.getElementById("rol").value;
    let estatus = document.getElementById("estatus").value;

    /* comprobar campos vacios */
    if (rol == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarRoles",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          $("#tablaRoles").DataTable().ajax.reload();

          document.getElementById("formRegistrarRoles").reset();
          //$("#radiosfoto").click();

          $("#modalAgregarRoles").modal("hide");
        } else {
          Swal.fire({
            icon: "warning",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });

/* -------------- Ver roles ------------------ */
function verRoles(id) {
  let id_roles = id;

  $.ajax({
    url: "index.php?page=verRoles",
    type: "post",
    dataType: "json",
    data: {
      id_roles: id_roles,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("rol_roles").innerHTML =
          "Nombre: " + response.data.rol;

        document.getElementById("fecha_roles").innerHTML =
          "Fecha: " + response.data.fecha;

        if (response.data.estatus == 1) {
          document.getElementById("estatus_roles").innerHTML =
            "Estado: <button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_roles").innerHTML =
            "Estado: <button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarRoles").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Modificar Roles ------------------ */

var modificar_roles;
if ((modificar_roles = document.getElementById("modificar_roles"))) {
  modificar_roles.addEventListener("click", modificarRoles, false);

  function modificarRoles() {
    let id_roles = document.getElementById("id_roles_update").value;

    let rol = document.getElementById("rol_update").value;

    let estatus = document.getElementById("estatus_update").value;
    /* comprobar campos vacios */
    if (rol == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarRoles",
      type: "post",
      dataType: "json",
      data: {
        id_roles: id_roles,

        rol: rol,

        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formActualizarRoles").reset();

          $("#modalActualizarRoles").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaRoles").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
$("#formActualizarRoles")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let id_roles_update = document.getElementById("id_roles_update").value;

    let rol = document.getElementById("rol_update").value;

    let estatus = document.getElementById("estatus_update").value;

    /* comprobar campos vacios */
    if (rol == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarRoles",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          console.log(respuesta.data);
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          document.getElementById("formActualizarRoles").reset();

          $("#formActualizarRoles").modal("hide");

          $("#tablaRoles").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });

/* --------------listarActualizacionRoles ------------------ */
function listarActualizacionRoles(id) {
  let id_roles = id;
  let id_roles_update = document.getElementById("id_roles_update").value;
  let rol = document.getElementById("rol_update").value;
  let estatus = document.getElementById("estatus_update").value;

  let listar = "listar";

  $.ajax({
    url: "index.php?page=verRoles",
    type: "post",
    dataType: "json",
    data: {
      id_roles: id_roles,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_roles_update").value = response.data.id;
        document.getElementById("rol_update").value = response.data.rol;
        document.getElementById("estatus_update").value = response.data.estatus;

        $("#modalActualizarRoles").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Activar e Inactivar Roles ------------------ */
function inactivarRoles(id) {
  var id_roles = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado del rol?",
    //  text: "El paciente sera dado de alta y el registro quedara guardado en la traza.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarRoles",
        type: "post",
        dataType: "json",
        data: {
          id_roles: id_roles,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaRoles").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/* -------------- Modulo Especies ------------------ */

/*----------------- Listar Especies -------------*/
$(document).ready(function () {
  $("#tablaEspecies").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarEspecies",
    pageLength: 10,
    createdRow: function (row, data, dataIndex) {
      if (data[5] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass("table-success");
      }
    },
    columnDefs: [
      {
        orderable: true,
        targets: 4,
        render: function (data, type, row, meta) {
          if (row[5] == 1) {
            let botones =
              `
                    <button type="button" class="btn btn-primary btn-sm" onclick="verEspecies(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionEspecies(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-danger btn-sm" onclick="inactivarEspecies(` +
              row[0] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                <button type="button" class="btn btn-primary btn-sm" onclick="verEspecies(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;

               <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionEspecies(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;

               <button type="button" class="btn btn-success btn-sm" onclick="inactivarEspecies(` +
              row[0] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});

/* -------------- Agregar Especies ------------------ */
var agregar_especies;
if ((agregar_especies = document.getElementById("agregar_especies"))) {
  agregar_especies.addEventListener("click", agregarEspecies, false);

  function agregarEspecies(e) {
    e.preventDefault();

    let especies = document.getElementById("especies").value;

    let estatus = document.getElementById("estatus").value;
    /* comprobar campos vacios */
    if (especies == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarEspecies",
      type: "post",
      dataType: "json",
      data: {
        especies: especies,
        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formRegistrarEspecies").reset();

          $("#modalAgregarEspecies").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaEspecies").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
/* -------------- Ver Especies ------------------ */
function verEspecies(id) {
  let id_especies = id;

  $.ajax({
    url: "index.php?page=verEspecies",
    type: "post",
    dataType: "json",
    data: {
      id_especies: id_especies,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("especie_especies").innerHTML =
          "" + response.data.especie;

        document.getElementById("fecha_especies").innerHTML =
          "" + response.data.fecha_registro;

        if (response.data.estatus == 1) {
          document.getElementById("estatus_especies").innerHTML =
            "<button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_especies").innerHTML =
            "<button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarEspecies").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}
/* --------------listarActualizacionEspecies ------------------ */
function listarActualizacionEspecies(id) {
  let id_especies = id;

  let id_especies_update = document.getElementById("id_especies_update").value;
  let especie = document.getElementById("especies_update").value;
  let estatus = document.getElementById("estatus_update").value;

  let listar = "listar";

  $.ajax({
    url: "index.php?page=verEspecies",
    type: "post",
    dataType: "json",
    data: {
      id_especies: id_especies,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_especies_update").value = response.data.id;
        document.getElementById("especies_update").value =
          response.data.especie;

        document.getElementById("estatus_update").value = response.data.estatus;

        $("#modalActualizarEspecies").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}
/* -------------- Modificar Especies ------------------ */
var modificar_especies;
if ((modificar_especies = document.getElementById("modificar_especies"))) {
  modificar_especies.addEventListener("click", modificarEspecies, false);

  function modificarEspecies() {
    let id_especies = document.getElementById("id_especies_update").value;

    let especie = document.getElementById("especies_update").value;

    let estatus = document.getElementById("estatus_update").value;
    /* comprobar campos vacios */
    if (especie == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarEspecies",
      type: "post",
      dataType: "json",
      data: {
        id_especies: id_especies,

        especie: especie,

        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formActualizarEspecies").reset();

          $("#modalActualizarEspecies").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaEspecies").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
$("#formActualizarEspecies")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let id_especies_update =
      document.getElementById("id_especies_update").value;

    let especie = document.getElementById("especies_update").value;

    let estatus = document.getElementById("estatus_update").value;

    /* comprobar campos vacios */
    if (especie == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarEspecies",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          console.log(respuesta.data);
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          document.getElementById("formActualizarEspecies").reset();

          $("#formActualizarEspecies").modal("hide");

          $("#tablaEspecies").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });
/* -------------- Activar e Inactivar Especies ------------------ */
function inactivarEspecies(id) {
  var id_especies = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado de la especie?",
    //  text: "El paciente sera dado de alta y el registro quedara guardado en la traza.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarEspecies",
        type: "post",
        dataType: "json",
        data: {
          id_especies: id_especies,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaEspecies").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}
/* Modulo Presentacion */
/*-----------------Listar Presentacion-------------*/
$(document).ready(function () {
  $("#tablaPresentacion").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarPresentacion",
    pageLength: 4,
    createdRow: function (row, data, dataIndex) {
      if (data[4] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass('table-success');
      }
    },
    columnDefs: [
      {
        orderable: false,
        targets: 3,
        render: function (data, type, row, meta) {
          if (row[4] == 1) {
            let botones =
              `
                      <button type="button" class="btn btn-primary btn-sm" onclick="verPresentacion(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
     
                     <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionPresentacion(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
     
                     <button type="button" class="btn btn-danger btn-sm" onclick="inactivarPresentacion(` +
              row[0] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                  <button type="button" class="btn btn-primary btn-sm" onclick="VerPresentacion(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
 
                 <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionPresentacion(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
 
                 <button type="button" class="btn btn-success btn-sm" onclick="inactivarPresentacion(` +
              row[0] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});
/* -------------- Agregar Presentacion ------------------ */
var agregar_presentacion;
if ((agregar_presentacion = document.getElementById("agregar_presentacion"))) {
  agregar_presentacion.addEventListener("click", agregarPresentacion, false);

  function agregarPresentacion(e) {
    e.preventDefault();

    let descripcion = document.getElementById("descripcion").value;

    let estatus = document.getElementById("estatus").value;
    /* comprobar campos vacios */
    if (descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarPresentacion",
      type: "post",
      dataType: "json",
      data: {
        descripcion: descripcion,
        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formRegistrarPresentacion").reset();

          $("#modalAgregarPresentacion").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaPresentacion").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
/* -------------- Ver Presentacion ------------------ */

function verPresentacion(id) {
  let id_presentacion = id;

  $.ajax({
    url: "index.php?page=verPresentacion",
    type: "post",
    dataType: "json",
    data: {
      id_presentacion: id_presentacion,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("descripcion_presentacion").innerHTML =
          "" + response.data.descripcion;

        if (response.data.estatus == 1) {
          document.getElementById("estatus_presentacion").innerHTML =
            "<button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_presentacion").innerHTML =
            "<button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarPresentacion").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Modificar Presentacion ------------------ */
var modificar_presentacion;
if (
  (modificar_presentacion = document.getElementById("modificar_presentacion"))
) {
  modificar_presentacion.addEventListener(
    "click",
    modificarPresentacion,
    false
  );

  function modificarPresentacion() {
    let id_presentacion = document.getElementById(
      "id_presentacion_update"
    ).value;

    let descripcion = document.getElementById("descripcion_update").value;

    let estatus = document.getElementById("estatus_update").value;
    /* comprobar campos vacios */
    if (descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarPresentacion",
      type: "post",
      dataType: "json",
      data: {
        id_presentacion: id_presentacion,

        descripcion: descripcion,

        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formActualizarPresentacion").reset();

          $("#modalActualizarPresentacion").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaPresentacion").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
$("#formActualizarPresentacion")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let id_presentacion_update = document.getElementById(
      "id_presentacion_update"
    ).value;

    let descripcion = document.getElementById("descripcion_update").value;

    let estatus = document.getElementById("estatus_update").value;

    /* comprobar campos vacios */
    if (descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarPresentacion",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          console.log(respuesta.data);
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          document.getElementById("formActualizarPresentacion").reset();

          $("#formActualizarPresentacion").modal("hide");

          $("#tablaPresentacion").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });

/* --------------listarActualizacionPresentacion ------------------ */
function listarActualizacionPresentacion(id) {
  let id_presentacion = id;

  let id_presentacion_update = document.getElementById(
    "id_presentacion_update"
  ).value;
  let descripcion = document.getElementById("descripcion_update").value;
  let estatus = document.getElementById("estatus_update").value;

  let listar = "listar";

  $.ajax({
    url: "index.php?page=verPresentacion",
    type: "post",
    dataType: "json",
    data: {
      id_presentacion: id_presentacion,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_presentacion_update").value =
          response.data.id;
        document.getElementById("descripcion_update").value =
          response.data.descripcion;

        document.getElementById("estatus_update").value = response.data.estatus;

        $("#modalActualizarPresentacion").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Activar e Inactivar Presentacion ------------------ */
function inactivarPresentacion(id) {
  var id_presentacion = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado de la descripcion?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarPresentacion",
        type: "post",
        dataType: "json",
        data: {
          id_presentacion: id_presentacion,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaPresentacion").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/* Modulo de tipos de personas */
/*-----------------Listar Tipos de personas-------------*/
$(document).ready(function () {
  $("#tablaTipopersona").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarTipopersona",
    pageLength: 4,
    createdRow: function (row, data, dataIndex) {
      if (data[4] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass('table-success');
      }
    },
    columnDefs: [
      {
        orderable: false,
        targets: 3,
        render: function (data, type, row, meta) {
          if (row[4] == 1) {
            let botones =
              `
                      <button type="button" class="btn btn-primary btn-sm" onclick="verTipopersona(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
     
                     <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionTipopersona(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
     
                     <button type="button" class="btn btn-danger btn-sm" onclick="inactivarTipopersona(` +
              row[0] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                  <button type="button" class="btn btn-primary btn-sm" onclick="VerTipopersona(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
 
                 <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionTipopersona(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
 
                 <button type="button" class="btn btn-success btn-sm" onclick="inactivarTipopersona(` +
              row[0] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});
/* -------------- Agregar Tipos de personas ------------------ */
var agregar_Tipopersona;
if ((agregar_Tipopersona = document.getElementById("agregar_Tipopersona"))) {
  agregar_Tipopersona.addEventListener("click", agregarTipopersona, false);

  function agregarTipopersona(e) {
    e.preventDefault();

    let descripcion = document.getElementById("descripcion").value;

    let estatus = document.getElementById("estatus").value;
    /* comprobar campos vacios */
    if (descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarTipopersona",
      type: "post",
      dataType: "json",
      data: {
        descripcion: descripcion,
        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formRegistrarTipopersona").reset();

          $("#modalAgregarTipopersona").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaTipopersona").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
/* -------------- Ver El tipo de personas ------------------ */

function verTipopersona(id) {
  let id_tipo_persona = id;

  $.ajax({
    url: "index.php?page=verTipopersona",
    type: "post",
    dataType: "json",
    data: {
      id_tipo_persona: id_tipo_persona,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("tipo_persona_descripcion").innerHTML =
          "" + response.data.descripcion;

        if (response.data.estatus == 1) {
          document.getElementById("estatus_tipo_persona").innerHTML =
            "<button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_tipo_persona").innerHTML =
            "<button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarTipopersona").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Modificar el tipo de personas ------------------ */
var modificar_Tipopersona;
if (
  (modificar_Tipopersona = document.getElementById("modificar_Tipopersona"))
) {
  modificar_Tipopersona.addEventListener("click", modificarTipopersona, false);

  function modificarTipopersona() {
    let id_tipo_persona = document.getElementById(
      "id_tipo_persona_update"
    ).value;

    let descripcion = document.getElementById("descripcion_update").value;

    let estatus = document.getElementById("estatus_update").value;
    /* comprobar campos vacios */
    if (descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarTipopersona",
      type: "post",
      dataType: "json",
      data: {
        id_tipo_persona: id_tipo_persona,

        descripcion: descripcion,

        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formActualizarTipopersona").reset();

          $("#modalActualizarTipopersona").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaTipopersona").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
$("#formActualizarTipopersona")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let id_tipo_persona_update = document.getElementById(
      "id_tipo_persona_update"
    ).value;

    let descripcion = document.getElementById("descripcion_update").value;

    let estatus = document.getElementById("estatus_update").value;

    /* comprobar campos vacios */
    if (descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarTipopersona",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          console.log(respuesta.data);
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          document.getElementById("formActualizarTipopersona").reset();

          $("#formActualizarTipopersona").modal("hide");

          $("#tablaTipopersona").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });
/* --------------listarActualizacionTipopersona ------------------ */
function listarActualizacionTipopersona(id) {
  let id_tipo_persona = id;

  let id_tipo_persona_update = document.getElementById(
    "id_tipo_persona_update"
  ).value;
  let descripcion = document.getElementById("descripcion_update").value;
  let estatus = document.getElementById("estatus_update").value;

  let listar = "listar";

  $.ajax({
    url: "index.php?page=verTipopersona",
    type: "post",
    dataType: "json",
    data: {
      id_tipo_persona: id_tipo_persona,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_tipo_persona_update").value =
          response.data.id;
        document.getElementById("descripcion_update").value =
          response.data.descripcion;

        document.getElementById("estatus_update").value = response.data.estatus;

        $("#modalActualizarTipopersona").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}
/* -------------- Activar e Inactivar Tipopersona ------------------ */
function inactivarTipopersona(id) {
  var id_tipo_persona = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado de la descripción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarTipopersona",
        type: "post",
        dataType: "json",
        data: {
          id_tipo_persona: id_tipo_persona,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaTipopersona").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/* Modulo de Beneficiarios */
/*-----------------Listar Beneficiarios-------------*/
$(document).ready(function () {
  $("#tablaBeneficiarios").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarBeneficiarios",
    pageLength: 4,
    createdRow: function (row, data, dataIndex) {
      if (data[5] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass("table-success");
      }
    },
    columnDefs: [
      {
        orderable: true,
        targets: 4,
        render: function (data, type, row, meta) {
          if (row[5] == 1) {
            let botones =
              `
                    <button type="button" class="btn btn-primary btn-sm" onclick="verBeneficiarios(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionBeneficiarios(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-danger btn-sm" onclick="inactivarBeneficiarios(` +
              row[0] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                <button type="button" class="btn btn-primary btn-sm" onclick="verBeneficiarios(` +
              row[0] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;

               <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionBeneficiarios(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;

               <button type="button" class="btn btn-success btn-sm" onclick="inactivarBeneficiarios(` +
              row[0] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});
/*----------------- Agregar Beneficiarios -------------*/
var agregar_beneficiarios;
if (
  (agregar_beneficiarios = document.getElementById("agregar_beneficiarios"))
) {
  agregar_beneficiarios.addEventListener("click", agregarBeneficiarios, false);

  function agregarBeneficiarios(e) {
    e.preventDefault();

    let id_parroquia = document.getElementById("parroquia").value;
    let descripcion = document.getElementById("descripcion").value;

    let estatus = document.getElementById("estatus").value;
    /* comprobar campos vacios */
    if (id_parroquia == "" || descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarBeneficiarios",
      type: "post",
      dataType: "json",
      data: {
        id_parroquia: id_parroquia,
        descripcion: descripcion,
        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formRegistrarBeneficiarios").reset();

          $("#modalAgregarBeneficiarios").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaBeneficiarios").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
function verBeneficiarios(id) {
  let id_beneficiarios = id;

  $.ajax({
    url: "index.php?page=verBeneficiarios",
    type: "post",
    dataType: "json",
    data: {
      id_beneficiarios: id_beneficiarios,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_parroquia_beneficiarios").innerHTML =
          "" + response.data.parroquia.toUpperCase();

        document.getElementById("descripcion_beneficiarios").innerHTML =
          "" + response.data.descripcion;

        if (response.data.estatus == 1) {
          document.getElementById("estatus_beneficiarios").innerHTML =
            "<button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_beneficiarios").innerHTML =
            "<button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarBeneficiarios").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Modificar el Beneficiarios------------------ */
var modificar_beneficiarios;
if (
  (modificar_beneficiarios = document.getElementById("modificar_beneficiarios"))
) {
  modificar_beneficiarios.addEventListener(
    "click",
    modificarBeneficiarios,
    false
  );
  function modificarBeneficiarios() {
    let id_beneficiarios = document.getElementById(
      "id_beneficiarios_update"
    ).value;

    let estado = document.getElementById("estado_update").value;
    let descripcion = document.getElementById("descripcion_update").value;

    let estatus = document.getElementById("estatus_update").value;
    /* comprobar campos vacios */
    if (estado == "" || descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarBeneficiarios",
      type: "post",
      dataType: "json",
      data: {
        id_beneficiarios: id_beneficiarios,
        estado: estado,
        descripcion: descripcion,

        estatus: estatus,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formActualizarBeneficiarios").reset();

          $("#modalActualizarBeneficiarios").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaBeneficiarios").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
$("#formActualizarBeneficiarios")
  .unbind("submit")
  .bind("submit", function (e) {
    e.preventDefault();

    let id_beneficiarios_update = document.getElementById(
      "id_beneficiarios_update"
    ).value;

    let estado = document.getElementById("estado_update").value;
    let descripcion = document.getElementById("descripcion_update").value;

    let estatus = document.getElementById("estatus_update").value;

    /* comprobar campos vacios */
    if (estado == "" || descripcion == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarBeneficiarios",
      type: "POST",
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        //btnSaveLoad();
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.data.success == true) {
          console.log(respuesta.data);
          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });

          document.getElementById("formActualizarBeneficiarios").reset();

          $("#formActualizarBeneficiarios").modal("hide");

          $("#tablaBeneficiarios").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: respuesta.data.message,
            text: respuesta.data.info,
          });
        }
      },
    });
  });

/* --------------listarActualizacionBeneficiarios------------------ */

function listarActualizacionBeneficiarios(id) {
  let id_beneficiarios = id;

  let id_beneficiarios_update = document.getElementById(
    "id_beneficiarios_update"
  ).value;
  let estado = document.getElementById("estado_update").value;
  let descripcion = document.getElementById("descripcion_update").value;
  let estatus = document.getElementById("estatus_update").value;

  let listar = "listar";

  $.ajax({
    url: "index.php?page=verBeneficiarios",
    type: "post",
    dataType: "json",
    data: {
      id_beneficiarios: id_beneficiarios,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_beneficiarios_update").value =
          response.data.id;

        document.getElementById("estado_update").value =
          response.data.id_parroquia;
        document.getElementById("descripcion_update").value =
          response.data.descripcion;

        document.getElementById("estatus_update").value = response.data.estatus;

        $("#modalActualizarBeneficiarios").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}
/* -------------- Activar e Inactivar Beneficiarios ------------------ */
function inactivarBeneficiarios(id) {
  var id_beneficiarios = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado  del beneficiario?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarBeneficiarios",
        type: "post",
        dataType: "json",
        data: {
          id_beneficiarios: id_beneficiarios,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaBeneficiarios").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/* Modulo de Personas */
/*----------------- Listar Personas -------------*/
$(document).ready(function () {
  $("#tablaPersonas").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarPersonas",
    pageLength: 5,
    createdRow: function (row, data, dataIndex) {
      if (data[6] == 0) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass("table-success");
      }
    },
    columnDefs: [
      {
        orderable: true,
        targets: 6,
        render: function (data, type, row, meta) {
          if (row[7] == 1) {
            let botones =
              `
                    <button type="button" class="btn btn-primary btn-sm" onclick="verPersonas(` +
              row[6] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;
        
                   <button type="button" class="btn btn-danger btn-sm" onclick="inactivarPersonas(` +
              row[6] +
              `)"><i class="fas fa-trash"></i></button>  `;
            return botones;
          } else {
            let botones =
              `
                <button type="button" class="btn btn-primary btn-sm" onclick="verPersonas(` +
              row[6] +
              `)"><i class="fas fa-eye"></i></button>&nbsp;


               <button type="button" class="btn btn-success btn-sm" onclick="inactivarPersonas(` +
              row[6] +
              `)"><i class="fas fa-fas fa-retweet"></i></button>  `;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});
/*----------------- Agregar Personas -------------*/
var agregar_personas;
if ((agregar_personas = document.getElementById("agregar_personas"))) {
  agregar_personas.addEventListener("click", agregarPersonas, false);

  function agregarPersonas(e) {
    e.preventDefault();

    let nombre_apellidos = document.getElementById("nombre_apellidos").value;
    let telefono = document.getElementById("telefono").value;
    let id_tipo_persona = document.getElementById("id_tipo_persona").value;
    let estatus = document.getElementById("estatus").value;

    /* comprobar campos vacios */
    if (nombre_apellidos == "" || telefono == "" || estatus == "") {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarPersonas",
      type: "post",
      dataType: "json",
      data: {
        nombre_apellidos: nombre_apellidos,
        telefono: telefono,
        estatus: estatus,
        id_tipo_persona: id_tipo_persona,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formRegistrarPersonas").reset();

          $("#modalAgregarPersonas").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaPersonas").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}
/* * -------------- Ver personas ------------------ */
function verPersonas(id) {
  let id_personas = id;

  $.ajax({
    url: "index.php?page=verPersonas",
    type: "post",
    dataType: "json",
    data: {
      id_personas: id_personas,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("nombre_apellidos_personas").innerHTML =
          "" + response.data.nombre_apellidos;

        document.getElementById("telefono_personas").innerHTML =
          "" + response.data.telefono;

        document.getElementById("fecha_registro_personas").innerHTML =
          "" + response.data.fecha_registro;

        document.getElementById("id_tipo_persona_personas").innerHTML =
          "" + response.data.id_tipo_persona;

        if (response.data.estatus == 1) {
          document.getElementById("estatus_personas").innerHTML =
            "<button class='btn btn-success'>Activo</button>";
        } else {
          document.getElementById("estatus_personas").innerHTML =
            "<button class='btn btn-danger'>inactivo</button>";
        }

        $("#modalVisualizarPersonas").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}
/* -------------- Activar e Inactivar Personas ------------------ */
function inactivarPersonas(id) {
  var id_personas = id;

  Swal.fire({
    title: "¿Está seguro de moficar el estado de la persona?",
    //  text: "El paciente sera dado de alta y el registro quedara guardado en la traza.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=inactivarPersonas",
        type: "post",
        dataType: "json",
        data: {
          id_personas: id_personas,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            $("#tablaRoles").DataTable().ajax.reload();
          } else {
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

$(document).ready(function () {
  $("#estado").on("change", function () {
    $("#estado option:selected").each(function () {
      elegido = $(this).val();
      $.ajax({
        url: "index.php?page=llenarSelectEstado",
        type: "post",
        dataType: "json",
        data: {
          elegido: elegido,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            //Limpiar select de municipios
            var estado_municipio = (document.getElementById(
              "municipio"
            ).innerHTML = '<option value="">Seleccione</option>');

            for (es = 0; es < response.data.data.length; es++) {
              //Crea el elemento <option> dentro del select municipio
              var itemOption = document.createElement("option");

              //Contenido de los <option> del select municipios
              var municipio = document.createTextNode(
                response.data.data[es].municipio
              );
              var id_municipio = document.createTextNode(
                response.data.data[es].id_municipio
              );

              //Crear atributo value para los elemento option
              var attValue = document.createAttribute("value");
              attValue.value = response.data.data[es].id_municipio;
              itemOption.setAttributeNode(attValue);

              //Añadir contenido a los <option> creados
              itemOption.appendChild(municipio);

              document.getElementById("municipio").appendChild(itemOption);
            }
          }
        })
        .fail(function () {
          console.log("error");
        });
    });
  });
});

$(document).ready(function () {
  $("#municipio").on("change", function () {
    $("#municipio option:selected").each(function () {
      municipio = $(this).val();
      $.ajax({
        url: "index.php?page=llenarSelectParroquia",
        type: "post",
        dataType: "json",
        data: {
          municipio: municipio,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            //Limpiar select de municipios
            document.getElementById("parroquia").innerHTML =
              '<option value="">Seleccione</option>';

            for (es = 0; es < response.data.data.length; es++) {
              //Crea el elemento <option> dentro del select municipio
              var itemOption = document.createElement("option");

              //Contenido de los <option> del select municipios
              var parroquia = document.createTextNode(
                response.data.data[es].parroquia
              );
              var id_parroquia = document.createTextNode(
                response.data.data[es].id_parroquia
              );

              //Crear atributo value para los elemento option
              var attValue = document.createAttribute("value");
              attValue.value = response.data.data[es].id_parroquia;
              itemOption.setAttributeNode(attValue);

              //Añadir contenido a los <option> creados
              itemOption.appendChild(parroquia);

              document.getElementById("parroquia").appendChild(itemOption);
            }
          }
        })
        .fail(function () {
          console.log("error");
        });
    });
  });
});

$(document).ready(function () {
  $("#estado_update").on("change", function () {
    $("#estado_update option:selected").each(function () {
      elegido = $(this).val();
      $.ajax({
        url: "index.php?page=llenarSelectEstado",
        type: "post",
        dataType: "json",
        data: {
          elegido: elegido,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            //Limpiar select de municipios
            var estado_municipio = (document.getElementById(
              "municipio_update"
            ).innerHTML = '<option value="">Seleccione</option>');

            for (es = 0; es < response.data.data.length; es++) {
              //Crea el elemento <option> dentro del select municipio
              var itemOption = document.createElement("option");

              //Contenido de los <option> del select municipios
              var municipio = document.createTextNode(
                response.data.data[es].municipio
              );
              var id_municipio = document.createTextNode(
                response.data.data[es].id_municipio
              );

              //Crear atributo value para los elemento option
              var attValue = document.createAttribute("value");
              attValue.value = response.data.data[es].id_municipio;
              itemOption.setAttributeNode(attValue);

              //Añadir contenido a los <option> creados
              itemOption.appendChild(municipio);

              document
                .getElementById("municipio_update")
                .appendChild(itemOption);
            }
          }
        })
        .fail(function () {
          console.log("error");
        });
    });
  });
});
/* municipio_update */
$(document).ready(function () {
  $("#municipio_update").on("change", function () {
    $("#municipio_update option:selected").each(function () {
      municipio = $(this).val();
      $.ajax({
        url: "index.php?page=llenarSelectParroquia",
        type: "post",
        dataType: "json",
        data: {
          municipio: municipio,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            //Limpiar select de municipios
            document.getElementById("parroquia_update").innerHTML =
              '<option value="">Seleccione</option>';

            for (es = 0; es < response.data.data.length; es++) {
              //Crea el elemento <option> dentro del select municipio
              var itemOption = document.createElement("option");

              //Contenido de los <option> del select municipios
              var parroquia = document.createTextNode(
                response.data.data[es].parroquia
              );
              var id_parroquia = document.createTextNode(
                response.data.data[es].id_parroquia
              );

              //Crear atributo value para los elemento option
              var attValue = document.createAttribute("value");
              attValue.value = response.data.data[es].id_parroquia;
              itemOption.setAttributeNode(attValue);

              //Añadir contenido a los <option> creados
              itemOption.appendChild(parroquia);

              document
                .getElementById("parroquia_update")
                .appendChild(itemOption);
            }
          }
        })
        .fail(function () {
          console.log("error");
        });
    });
  });
});

// var agregar items temporal
var agregar_especie_temporal;
if (
  (agregar_especie_temporal = document.getElementById(
    "agregar_especie_temporal"
  ))
) {
  agregar_especie_temporal.addEventListener(
    "click",
    agregarEspecieTemporal,
    false
  );

  function agregarEspecieTemporal() {
    document.getElementById("cont-loader").removeAttribute("style");

    let id_especie = document.getElementById("id_especie").value;
    let id_presentacion = document.getElementById("id_presentacion").value;
    let precio_bs = document.getElementById("precio_bs").value;
    let disponibilidad_kl = document.getElementById("disponibilidad_kl").value;
    let tasa_bcv = document.getElementById("tasa_bcv").value;

    /* comprobar campos vacios */
    if (id_especie == "") {
      document
        .getElementById("cont-loader")
        .setAttribute("style", "display:none;");

      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Campo ítems vacío",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    if (tasa_bcv == "") {
      document
        .getElementById("cont-loader")
        .setAttribute("style", "display:none;");

      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "A la hora de agregar una especie se quiere que agregue primero el valor de la tasa BCV para realizar el calculo correspondiente en $",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarEspecieTemporal",
      type: "post",
      dataType: "json",
      data: {
        id_especie: id_especie,
        id_presentacion: id_presentacion,
        precio_bs: precio_bs,
        disponibilidad_kl: disponibilidad_kl,
        tasa_bcv: tasa_bcv,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          contador = contador + 1;

          console.log("Add", contador);

          document
            .getElementById("contenedor_datos_especies_multiples")
            .removeAttribute("style");

          let id_contenedor = "contenedor_" + response.data.id_especie;
          let id_accion = "id_accion_" + response.data.id_especie;

          let id_contar_especie = "id_contar_item" + response.data.id_especie;
          let id_nombre_especie = "id_nombre_item" + response.data.id_especie;
          let id_presentacion = "id_presentacion" + response.data.id_especie;
          let id_precio_bs = "id_precio_bs" + response.data.id_especie;
          let id_precio_dolares =
            "id_precio_dolares" + response.data.precio_dolares;
          let id_disponibilidad_kl =
            "id_disponibilidad_kl" + response.data.id_especie;

          //Contenedor de los items
          var cont_elemento = document.createElement("tr");
          cont_elemento.setAttribute("id", id_contenedor);
          cont_elemento.setAttribute(
            "style",
            "border: solid 1px #ccc; padding: 10px;"
          );
          document
            .getElementById("multiples_especies")
            .appendChild(cont_elemento);

          //td que almacena el nombre de la especie
          var td_nombre_especie = document.createElement("td");
          td_nombre_especie.setAttribute("id", id_nombre_especie);
          td_nombre_especie.setAttribute("class", "nombre_item");
          td_nombre_especie.setAttribute(
            "style",
            "border: solid 1px #ccc; padding: 10px;"
          );
          cont_elemento.appendChild(td_nombre_especie);

          //td que almacena la presentación
          var td_presentacion = document.createElement("td");
          td_presentacion.setAttribute("id", id_presentacion);
          td_presentacion.setAttribute("class", "presentacion");
          td_presentacion.setAttribute(
            "style",
            "border: solid 1px #ccc; padding: 10px;"
          );
          cont_elemento.appendChild(td_presentacion);

          //td que almacena el precio en bs
          var td_precio_bs = document.createElement("td");
          td_precio_bs.setAttribute("id", id_precio_bs);
          td_precio_bs.setAttribute("class", "precio_bs");
          td_precio_bs.setAttribute(
            "style",
            "border: solid 1px #ccc; padding: 10px;"
          );
          cont_elemento.appendChild(td_precio_bs);

          //td que almacena el precio en bs
          var td_precio_dolares = document.createElement("td");
          td_precio_dolares.setAttribute("id", id_precio_dolares);
          td_precio_dolares.setAttribute("class", "precio_dolares");
          td_precio_dolares.setAttribute(
            "style",
            "border: solid 1px #ccc; padding: 10px;"
          );
          cont_elemento.appendChild(td_precio_dolares);

          //td que almacena la disponibilidad en kilos
          var td_disponibilidad_kl = document.createElement("td");
          td_disponibilidad_kl.setAttribute("id", id_disponibilidad_kl);
          td_disponibilidad_kl.setAttribute("class", "precio_bs");
          td_disponibilidad_kl.setAttribute(
            "style",
            "border: solid 1px #ccc; padding: 10px;"
          );
          cont_elemento.appendChild(td_disponibilidad_kl);

          //Columna que almacena el boton borrar
          var td_accion_borrar = document.createElement("td");
          td_accion_borrar.setAttribute("id", id_accion);
          td_accion_borrar.setAttribute("class", "acciones");
          td_accion_borrar.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_accion_borrar);

          //Boton borrar
          var btn_delete = document.createElement("button");
          btn_delete.setAttribute("class", "btn btn-danger btn-sm");
          btn_delete.setAttribute("title", "Remover");
          btn_delete.setAttribute("type", "button");
          btn_delete.setAttribute(
            "onclick",
            "eliminarEspecieTemporal(" + response.data.id_especie + ")"
          );
          btn_delete.setAttribute("style", "background:#dc3545; color: #FFF;");
          td_accion_borrar.appendChild(btn_delete);

          //Icono del boton borrar
          var icono_btn_delete = document.createElement("i");
          icono_btn_delete.setAttribute("class", "fas fa-trash");
          icono_btn_delete.setAttribute("data-id", "");
          btn_delete.appendChild(icono_btn_delete);

          document.getElementById(id_nombre_especie).innerHTML =
            response.data.especie;
          document.getElementById(id_presentacion).innerHTML =
            response.data.presentacion;
          document.getElementById(id_precio_bs).innerHTML =
            "<button type='button' class='btn btn-sm bg-success text-white'>" +
            response.data.precio_bs +
            " Bs </button>";
          document.getElementById(id_disponibilidad_kl).innerHTML =
            "<button type='button' class='btn btn-sm bg-secondary text-white'> <i class='fas fa-weight'></i> " +
            response.data.disponibilidad_kl +
            " Kg </button>";
          document.getElementById(id_precio_dolares).innerHTML =
            "<button type='button' class='btn btn-sm bg-success text-white'> </i> " +
            response.data.precio_dolares +
            " $ </button>";

          document.getElementById("precio_bs").value = "";
          document.getElementById("disponibilidad_kl").value = "";
        } else {
          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");

          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        document
          .getElementById("cont-loader")
          .setAttribute("style", "display:none;");

        console.log("error");
      });
  }
}

/* Agregar Especie en el modal modificar */
var agregar_especie_update;
if (
  (agregar_especie_update = document.getElementById("agregar_especie_update"))
) {
  agregar_especie_update.addEventListener("click", agregarEspecieUpdate, false);

  function agregarEspecieUpdate() {
    document.getElementById("cont-loader").removeAttribute("style");

    let id_especie_update = document.getElementById("id_especie_update").value;
    let id_presentacion_update = document.getElementById(
      "id_presentacion_update"
    ).value;
    let precio_bs_update = document.getElementById("precio_bs_update").value;
    let disponibilidad_kl_update = document.getElementById(
      "disponibilidad_kl_update"
    ).value;
    let tasa_bcv_update = document.getElementById("tasa_bcv_update").value;
    let vendidos_kl_update =
      document.getElementById("vendidos_kl_update").value;
    let id_jornada_update = document.getElementById("id_jornada_update").value;

    /* comprobar campos vacios */
    if (
      id_especie_update == "" ||
      id_presentacion_update == "" ||
      precio_bs_update == "" ||
      disponibilidad_kl_update == "" ||
      tasa_bcv_update == "" ||
      vendidos_kl_update == ""
    ) {
      document
        .getElementById("cont-loader")
        .setAttribute("style", "display:none;");

      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Campo ítems vacío",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    if (tasa_bcv_update == "") {
      document
        .getElementById("cont-loader")
        .setAttribute("style", "display:none;");

      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "A la hora de agregar una especie se quiere que agregue primero el valor de la tasa BCV para realizar el calculo correspondiente en $",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=registrarEspecieUpdate",
      type: "post",
      dataType: "json",
      data: {
        id_especie_update: id_especie_update,
        id_presentacion_update: id_presentacion_update,
        precio_bs_update: precio_bs_update,
        disponibilidad_kl_update: disponibilidad_kl_update,
        tasa_bcv_update: tasa_bcv_update,
        vendidos_kl_update: vendidos_kl_update,
        id_jornada_update: id_jornada_update,
      },
    })

      .done(function (response) {
        if (response.data.success == true) {
          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          document.getElementById("multiples_especies_update").innerHTML = "";

          document.getElementById("multiples_especies_update").innerHTML =
            "<tr><th>Especies</th><th>Presentación</th><th>Bolivares</th><th>Dolares</th><th>Disponibilidad</th><th>Kilos vendidos</th><th>Acciones</th></tr>";

          response.data.especies_pescado.forEach(function (especies, index) {
            contador = contador + 1;

            //7document.getElementById("total_beneficiarios_view").innerHTML = contador;

            let class_contenedor = "row contenedor_" + especies.id;
            let id_contenedor = "contenedor_" + especies.id;

            let id_especie = "id_especie_" + especies.id;
            let id_presentacion = "id_presentacion_" + especies.id;
            let id_precio_bs = "id_precio_bs_" + especies.id;
            let id_precio_dolares = "id_precio_dolares" + especies.id;
            let id_disponibilidad_kl = "id_disponibilidad_kl_" + especies.id;
            let id_vendidos_kl = "id_vendidos_kl_" + especies.id;
            let id_boton_borrar = "id_boton_borrar_" + especies.id;

            //Contenedor de los detalles agregados
            var cont_elemento = document.createElement("tr");
            cont_elemento.setAttribute("id", id_contenedor);
            cont_elemento.setAttribute("class", "contenedor_especie");
            cont_elemento.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            document
              .getElementById("multiples_especies_update")
              .appendChild(cont_elemento);

            //td que almacena los nombres de las especies
            var td_especies = document.createElement("td");
            td_especies.setAttribute("id", id_especie);
            td_especies.setAttribute("class", "ente");
            td_especies.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_especies);

            //td que almacena que almacena las presentaciones de las especies
            var td_presentacion = document.createElement("td");
            td_presentacion.setAttribute("id", id_presentacion);
            td_presentacion.setAttribute("class", "cuenta_movimiento");
            td_presentacion.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_presentacion);

            //td que almacena el precio de las especies
            var td_precio_bs = document.createElement("td");
            td_precio_bs.setAttribute("id", id_precio_bs);
            td_precio_bs.setAttribute("class", "debito_credito");
            td_precio_bs.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_precio_bs);

            //td que almacena el precio de las especies
            var td_precio_dolares = document.createElement("td");
            td_precio_dolares.setAttribute("id", id_precio_dolares);
            td_precio_dolares.setAttribute("class", "debito_credito");
            td_precio_dolares.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_precio_dolares);

            //td que almacena el precio de las especies
            var td_disponibilidad_kl = document.createElement("td");
            td_disponibilidad_kl.setAttribute("id", id_disponibilidad_kl);
            td_disponibilidad_kl.setAttribute("class", "debito_credito");
            td_disponibilidad_kl.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_disponibilidad_kl);

            //td que almacena el precio de las especies
            var td_vendidos_kl = document.createElement("td");
            td_vendidos_kl.setAttribute("id", id_vendidos_kl);
            td_vendidos_kl.setAttribute("class", "debito_credito");
            td_vendidos_kl.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_vendidos_kl);

            //Columna que almacena el boton borrar
            var td_accion_borrar = document.createElement("td");
            td_accion_borrar.setAttribute("id", id_boton_borrar);
            td_accion_borrar.setAttribute("class", "acciones");
            td_accion_borrar.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_accion_borrar);

            //Boton borrar
            var btn_delete = document.createElement("button");
            btn_delete.setAttribute("class", "btn btn-danger btn-sm");
            btn_delete.setAttribute("title", "Remover");
            btn_delete.setAttribute("type", "button");
            btn_delete.setAttribute(
              "onclick",
              "eliminarEspecieUpdate(" + especies.id + ")"
            );
            btn_delete.setAttribute(
              "style",
              "background:#dc3545; color: #FFF;"
            );
            td_accion_borrar.appendChild(btn_delete);

            //Boton Modificar
            var btn_update = document.createElement("button");
            btn_update.setAttribute("class", "btn btn-warning btn-sm");
            btn_update.setAttribute("title", "Modificar");
            btn_update.setAttribute("type", "button");
            btn_update.setAttribute(
              "onclick",
              "ObtenerDatosModificarEspecieJornada(" + especies.id + ")"
            );
            btn_update.setAttribute(
              "style",
              "background:#ffc107; color: #FFF; margin-left: 10px;"
            );
            td_accion_borrar.appendChild(btn_update);

            //Icono del boton borrar
            var icono_btn_delete = document.createElement("i");
            icono_btn_delete.setAttribute("class", "fas fa-trash");
            icono_btn_delete.setAttribute("data-id", "");
            btn_delete.appendChild(icono_btn_delete);

            //Icono del boton modificar
            var icono_btn_update = document.createElement("i");
            icono_btn_update.setAttribute("class", "fas fa-edit");
            icono_btn_update.setAttribute("data-id", "");
            btn_update.appendChild(icono_btn_update);

            document.getElementById(id_especie).innerHTML = especies.especie;
            document.getElementById(id_presentacion).innerHTML =
              especies.presentacion;
            document.getElementById(id_precio_bs).innerHTML =
              especies.precio_bs;
            document.getElementById(id_precio_dolares).innerHTML =
              especies.precio_dolares;
            document.getElementById(id_disponibilidad_kl).innerHTML =
              especies.disponibilidad_kl;
            document.getElementById(id_vendidos_kl).innerHTML =
              especies.vendidos_kl;

            document
              .getElementById("contenedor_datos_especies_multiples_update")
              .removeAttribute("style");
          });
        } else {
          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");

          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        document
          .getElementById("cont-loader")
          .setAttribute("style", "display:none;");

        console.log("error");
      });
  }
}

/*----------------- Agregar tasa BCV -------------*/
var agregar_tasa_bcv;
if ((agregar_tasa_bcv = document.getElementById("agregar_tasa_bcv"))) {
  agregar_tasa_bcv.addEventListener("click", agregarTasaBcv, false);

  function agregarTasaBcv() {
    let valor_tasa_bcv = document.getElementById("tasa_bcv").value;

    if (valor_tasa_bcv == "") {
      Swal.fire({
        icon: "error",
        confirmButtonColor: "#3085d6",
        title: "Atención",
        text: "El campo tasa bcv esta en blanco",
      });

      return;
    }

    Swal.fire({
      icon: "success",
      confirmButtonColor: "#3085d6",
      title: "Tasa BCV agregada exitosamente",
      text: "",
    });

    document.getElementById("tasa_bcv").setAttribute("disabled", "true");
  }
}

/*----------------- Agregar tasa BCV Update -------------*/
var agregar_tasa_bcv_update;
if (
  (agregar_tasa_bcv_update = document.getElementById("agregar_tasa_bcv_update"))
) {
  agregar_tasa_bcv_update.addEventListener(
    "click",
    agregarTasaBcvUpdate,
    false
  );

  function agregarTasaBcvUpdate() {
    let valor_tasa_bcv = document.getElementById("tasa_bcv_update").value;

    if (valor_tasa_bcv == "") {
      Swal.fire({
        icon: "error",
        confirmButtonColor: "#3085d6",
        title: "Atención",
        text: "El campo tasa bcv esta en blanco",
      });

      return;
    }

    Swal.fire({
      icon: "success",
      confirmButtonColor: "#3085d6",
      title: "Tasa BCV agregada exitosamente",
      text: "",
    });

    document.getElementById("tasa_bcv_update").setAttribute("disabled", "true");
  }
}

/*----------------- Eliminar tasa BCV -------------*/
var eliminar_tasa_bcv;
if ((eliminar_tasa_bcv = document.getElementById("eliminar_tasa_bcv"))) {
  eliminar_tasa_bcv.addEventListener("click", eliminarTasaBcv, false);

  function eliminarTasaBcv() {
    let valor_tasa_bcv = document.getElementById("tasa_bcv").value;

    if (valor_tasa_bcv == "") {
      Swal.fire({
        icon: "error",
        confirmButtonColor: "#3085d6",
        title: "Atención",
        text: "El campo tasa bcv esta en blanco",
      });

      return;
    }

    Swal.fire({
      icon: "success",
      confirmButtonColor: "#3085d6",
      title: "Tasa BCV eliminada exitosamente",
      text: "",
    });

    document.getElementById("tasa_bcv").removeAttribute("disabled");
    document.getElementById("tasa_bcv").value = "";
  }
}

/*----------------- Eliminar tasa BCV update -------------*/
var eliminar_tasa_bcv_update;
if (
  (eliminar_tasa_bcv_update = document.getElementById(
    "eliminar_tasa_bcv_update"
  ))
) {
  eliminar_tasa_bcv_update.addEventListener(
    "click",
    eliminarTasaBcvUpdate,
    false
  );

  function eliminarTasaBcvUpdate() {
    let valor_tasa_bcv = document.getElementById("tasa_bcv_update").value;

    if (valor_tasa_bcv == "") {
      Swal.fire({
        icon: "error",
        confirmButtonColor: "#3085d6",
        title: "Atención",
        text: "El campo tasa bcv esta en blanco",
      });

      return;
    }

    Swal.fire({
      icon: "success",
      confirmButtonColor: "#3085d6",
      title: "Tasa BCV eliminada exitosamente",
      text: "",
    });

    document.getElementById("tasa_bcv_update").removeAttribute("disabled");
    document.getElementById("tasa_bcv_update").value = "";
  }
}

/* Eliminar Especie Temporal */

function eliminarEspecieTemporal(id) {
  let id_especie = id;

  Swal.fire({
    title: "¿Deseas remover la especie?",
    text: "Luego de remover la especie podrás agregar una nueva",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=eliminarEspecieTemporal",
        type: "post",
        dataType: "json",
        data: {
          id_especie: id_especie,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            contador = response.data.contador;

            let id_contenedor = "contenedor_" + id_especie;

            Swal.fire({
              icon: "success",
              confirmButtonColor: "#3085d6",
              title: response.data.message,
              text: response.data.info,
            });

            let contenedor_padre = document.getElementById(
              "" + id_contenedor + ""
            );

            contenedor_padre.remove();

            console.log("Delete", contador);

            if (contador == 0) {
              document
                .getElementById("contenedor_datos_especies_multiples")
                .setAttribute("style", "display:none;");
            }

            //contador - 1;
            //console.log("Delete", contador);
          } else {
            Swal.fire({
              icon: "error",
              confirmButtonColor: "#3085d6",
              title: response.data.message,
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/* eliminar Especie Update */
function eliminarEspecieUpdate(id) {
  let id_especie = id;

  Swal.fire({
    title: "¿Deseas remover la especie?",
    text: "Luego de remover la especie podrás agregar una nueva",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?page=eliminarEspecieUpdate",
        type: "post",
        dataType: "json",
        data: {
          id_especie: id_especie,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            contador = response.data.contador;

            let id_contenedor = "contenedor_" + id_especie;

            Swal.fire({
              icon: "success",
              confirmButtonColor: "#3085d6",
              title: response.data.message,
              text: response.data.info,
            });

            let contenedor_padre = document.getElementById(
              "" + id_contenedor + ""
            );

            contenedor_padre.remove();

            console.log("Delete", contador);

            if (contador == 0) {
              document
                .getElementById("contenedor_datos_especies_multiples")
                .setAttribute("style", "display:none;");
            }

            //contador - 1;
            //console.log("Delete", contador);
          } else {
            Swal.fire({
              icon: "error",
              confirmButtonColor: "#3085d6",
              title: response.data.message,
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");
        });
    }
  });
}

/* Modulo Jornadas */

/*----------------- Listar Jornada -------------*/
$(document).ready(function () {
  $("#tablaJornadas").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarJornadas",
    pageLength: 4,
    createdRow: function (row, data, dataIndex) {
      if (data[8] == 2) {
        $(row).addClass("table-danger");
      } else {
        //$(row).addClass('table-success');
      }
    },
    columnDefs: [
      {
        orderable: false,
        targets: 7,
        render: function (data, type, row, meta) {
          if (row[8] == 1) {
            let botones =
              `
                    <a class="btn btn-primary btn-sm" title="Ver jornada" href="?page=verJornada&amp;id=` +
              row[0] +
              `"><i class="fas fa-eye"></i></a>
                    &nbsp;
    
                   <button type="button" class="btn btn-warning btn-sm"  onclick="listarActualizacionJornadas(` +
              row[0] +
              `)"><i class="fas fa-edit"></i></button>&nbsp;
    
                   <button type="button" class="btn btn-danger btn-sm"  onclick="finalizarJornada(` +
              row[0] +
              `)"><i class="fas fa-power-off"></i></button>&nbsp;`;
            return botones;
          } else if (row[8] == 2) {
            let botones =
              `
                <a class="btn btn-primary btn-sm" title="Ver jornada" href="?page=verJornada&amp;id=` +
              row[0] +
              `"><i class="fas fa-eye"></i></a>
                    &nbsp;`;
            return botones;
          }
        },
      },
    ],
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});

/*----------------- Crear Jornada -------------*/
var agregar_jornada;
if ((agregar_jornada = document.getElementById("agregar_jornada"))) {
  agregar_jornada.addEventListener("click", agregarJornada, false);

  function agregarJornada(e) {
    e.preventDefault();

    let id_ma = document.getElementById("id_ma").value;
    let id_sa = document.getElementById("id_sa").value;
    let id_vocero = document.getElementById("id_vocero").value;
    let id_doca = document.getElementById("id_doca").value;

    let nro_familias_atender = document.getElementById(
      "nro_familias_atender"
    ).value;
    let id_beneficiario = document.getElementById("id_beneficiario").value;
    let kl_ofrecer = document.getElementById("kl_ofrecer").value;
    let nro_placa_caravana =
      document.getElementById("nro_placa_caravana").value;
    let id_tipo_distribucion = document.getElementById(
      "id_tipo_distribucion"
    ).value;

    let id_origen = document.getElementById("id_origen").value;
    let id_destino = document.getElementById("id_destino").value;
    let fecha = document.getElementById("fecha").value;
    let direccion = document.getElementById("direccion").value;
    let parroquia = document.getElementById("parroquia").value;

    let tasa_bcv = document.getElementById("tasa_bcv").value;
    let observacion = document.getElementById("observacion").value;

    /* comprobar campos vacios */
    if (
      id_ma == "" ||
      id_sa == "" ||
      id_vocero == "" ||
      id_doca == "" ||
      nro_familias_atender == "" ||
      id_beneficiario == "" ||
      kl_ofrecer == "" ||
      nro_placa_caravana == "" ||
      id_tipo_distribucion == "" ||
      id_origen == "" ||
      id_destino == "" ||
      id_origen == "" ||
      fecha == "" ||
      direccion == "" ||
      parroquia == "" ||
      tasa_bcv == "" ||
      observacion == ""
    ) {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    Swal.fire({
      title: "¿Estas seguro registrar la jornada?",
      text: "",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "index.php?page=registrarJornada",
          type: "post",
          dataType: "json",
          data: {
            id_ma: id_ma,
            id_sa: id_sa,
            id_vocero: id_vocero,
            id_doca: id_doca,
            nro_familias_atender: nro_familias_atender,
            id_beneficiario: id_beneficiario,
            kl_ofrecer: kl_ofrecer,
            nro_placa_caravana: nro_placa_caravana,
            id_tipo_distribucion: id_tipo_distribucion,
            id_origen: id_origen,
            id_destino: id_destino,
            fecha: fecha,
            direccion: direccion,
            parroquia: parroquia,
            tasa_bcv: tasa_bcv,
            observacion: observacion,
          },
        })
          .done(function (response) {
            if (response.data.success == true) {
              document.getElementById("formRegistrarJornada").reset();

              $("#modalCrearJornada").modal("hide");

              let multiples_especies =
                document.getElementById("multiples_especies");
              let contenedor_datos_especies_multiples = document.getElementById(
                "contenedor_datos_especies_multiples"
              );

              contenedor_datos_especies_multiples.setAttribute(
                "style",
                "display: none;"
              );
              multiples_especies.innerHTML =
                "<tr><th>Especies</th><th>Presentación</th><th>Bolivares</th><th>Dolares</th><th>Disponibilidad</th><th>Acciones</th></tr>";

              Swal.fire({
                icon: "success",
                confirmButtonColor: "#3085d6",
                title: response.data.message,
                text: response.data.info,
              });

              $("#tablaJornadas").DataTable().ajax.reload();
            } else {
              Swal.fire({
                icon: "error",
                confirmButtonColor: "#3085d6",
                title: response.data.message,
                text: response.data.info,
              });
            }
          })
          .fail(function () {
            console.log("error");
          });
      }
    });
  }
}

/* -------------- Modificar Jornada ------------------ */

var modificar_jornadas;
if ((modificar_jornadas = document.getElementById("modificar_jornadas"))) {
  modificar_jornadas.addEventListener("click", modificarJornadas, false);

  function modificarJornadas(e) {
    e.preventDefault();

    let id_jornada = document.getElementById("id_jornada_update").value;

    let id_ma = document.getElementById("id_ma_update").value;
    let id_sa = document.getElementById("id_sa_update").value;
    let id_vocero = document.getElementById("id_vocero_update").value;
    let id_doca = document.getElementById("id_doca_update").value;
    let nro_familias_atender = document.getElementById(
      "nro_familias_atender_update"
    ).value;
    let id_beneficiario = document.getElementById(
      "id_beneficiario_update"
    ).value;
    let kl_ofrecer = document.getElementById("kl_ofrecer_update").value;

    let nro_placa_caravana = document.getElementById(
      "nro_placa_caravana_update"
    ).value;

    let id_tipo_distribucion = document.getElementById(
      "id_tipo_distribucion_update"
    ).value;
    let id_origen = document.getElementById("id_origen_update").value;
    let id_destino = document.getElementById("id_destino_update").value;
    let fecha = document.getElementById("fecha_update").value;
    let direccion = document.getElementById("direccion_update").value;
    let parroquia = document.getElementById("parroquia_update").value;
    let tasa_bcv = document.getElementById("tasa_bcv_update").value;
    let observacion = document.getElementById("observacion_update").value;

    /* comprobar campos vacios */
    if (
      id_ma == "" ||
      id_sa == "" ||
      id_vocero == "" ||
      id_doca == "" ||
      nro_familias_atender == "" ||
      id_beneficiario == "" ||
      kl_ofrecer == "" ||
      nro_placa_caravana == "" ||
      id_tipo_distribucion == "" ||
      id_origen == "" ||
      id_destino == "" ||
      id_origen == "" ||
      fecha == "" ||
      direccion == "" ||
      parroquia == "" ||
      tasa_bcv == "" ||
      observacion == ""
    ) {
      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Todos los campos son obligatorios",
        confirmButtonColor: "#3085d6",
      });
      return;
    }
    $.ajax({
      url: "index.php?page=modificarJornadas",
      type: "post",
      dataType: "json",
      data: {
        id_jornada: id_jornada,
        id_ma: id_ma,
        id_sa: id_sa,
        id_vocero: id_vocero,
        id_doca: id_doca,
        nro_familias_atender: nro_familias_atender,
        id_beneficiario: id_beneficiario,
        kl_ofrecer: kl_ofrecer,
        nro_placa_caravana: nro_placa_caravana,
        id_tipo_distribucion: id_tipo_distribucion,
        id_origen: id_origen,
        id_destino: id_destino,
        fecha: fecha,
        direccion: direccion,
        parroquia: parroquia,
        tasa_bcv: tasa_bcv,
        observacion: observacion,
      },
    })
      .done(function (response) {
        if (response.data.success == true) {
          document.getElementById("formActualizarJornadas").reset();

          $("#modalActualizarJornadas").modal("hide");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          $("#tablaJornadas").DataTable().ajax.reload();
        } else {
          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        console.log("error");
      });
  }
}

/* --------------listarActualizacionJornada ------------------ */
function listarActualizacionJornadas(id) {
  let id_jornada = id;

  $.ajax({
    url: "index.php?page=listarActualizarcionJornada",
    type: "post",
    dataType: "json",
    data: {
      id_jornada: id_jornada,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_jornada_update").value =
          response.data.id_jornada;
        document.getElementById("id_ma_update").value = response.data.id_ma;
        document.getElementById("id_sa_update").value = response.data.id_sa;
        document.getElementById("id_vocero_update").value =
          response.data.id_vocero;
        document.getElementById("id_doca_update").value = response.data.id_doca;
        document.getElementById("nro_familias_atender_update").value =
          response.data.nro_familias_atender;
        document.getElementById("id_beneficiario_update").value =
          response.data.id_beneficiario;
        document.getElementById("kl_ofrecer_update").value =
          response.data.kl_ofrecer;
        document.getElementById("nro_placa_caravana_update").value =
          response.data.nro_placa_caravana;
        document.getElementById("id_tipo_distribucion_update").value =
          response.data.id_tipo_distribucion;
        document.getElementById("id_origen_update").value =
          response.data.id_origen;
        document.getElementById("id_destino_update").value =
          response.data.id_destino;
        document.getElementById("fecha_update").value = response.data.fecha;
        document.getElementById("direccion_update").value =
          response.data.direccion;
        document.getElementById("parroquia_update").value =
          response.data.id_parroquia;
        document.getElementById("tasa_bcv_update").value =
          response.data.tasa_bcv;
        document.getElementById("observacion_update").value =
          response.data.observaciones;

        document
          .getElementById("tasa_bcv_update")
          .setAttribute("disabled", "true");

        //document.getElementById("observacion_update").value = response.data.observacion;

        /* Mostrar las especies de pescado para la actualizacion */

        document.getElementById("multiples_especies_update").innerHTML =
          "<tr><th>Especies</th><th>Presentación</th><th>Bolivares</th><th>Dolares</th><th>Disponibilidad</th><th>Kilos vendidos</th><th>Acciones</th></tr>";

        response.data.especies_pescado.forEach(function (especies, index) {
          contador = contador + 1;

          //7document.getElementById("total_beneficiarios_view").innerHTML = contador;

          let class_contenedor = "row contenedor_" + especies.id;
          let id_contenedor = "contenedor_" + especies.id;

          let id_especie = "id_especie_" + especies.id;
          let id_presentacion = "id_presentacion_" + especies.id;
          let id_precio_bs = "id_precio_bs_" + especies.id;
          let id_precio_dolares = "id_precio_dolares" + especies.id;
          let id_disponibilidad_kl = "id_disponibilidad_kl_" + especies.id;
          let id_vendidos_kl = "id_vendidos_kl_" + especies.id;
          let id_boton_borrar = "id_boton_borrar_" + especies.id;

          //Contenedor de los detalles agregados
          var cont_elemento = document.createElement("tr");
          cont_elemento.setAttribute("id", id_contenedor);
          cont_elemento.setAttribute("class", "contenedor_especie");
          cont_elemento.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          document
            .getElementById("multiples_especies_update")
            .appendChild(cont_elemento);

          //td que almacena los nombres de las especies
          var td_especies = document.createElement("td");
          td_especies.setAttribute("id", id_especie);
          td_especies.setAttribute("class", "ente");
          td_especies.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_especies);

          //td que almacena que almacena las presentaciones de las especies
          var td_presentacion = document.createElement("td");
          td_presentacion.setAttribute("id", id_presentacion);
          td_presentacion.setAttribute("class", "cuenta_movimiento");
          td_presentacion.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_presentacion);

          //td que almacena el precio de las especies
          var td_precio_bs = document.createElement("td");
          td_precio_bs.setAttribute("id", id_precio_bs);
          td_precio_bs.setAttribute("class", "debito_credito");
          td_precio_bs.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_precio_bs);

          //td que almacena el precio de las especies
          var td_precio_dolares = document.createElement("td");
          td_precio_dolares.setAttribute("id", id_precio_dolares);
          td_precio_dolares.setAttribute("class", "debito_credito");
          td_precio_dolares.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_precio_dolares);

          //td que almacena el precio de las especies
          var td_disponibilidad_kl = document.createElement("td");
          td_disponibilidad_kl.setAttribute("id", id_disponibilidad_kl);
          td_disponibilidad_kl.setAttribute("class", "debito_credito");
          td_disponibilidad_kl.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_disponibilidad_kl);

          //td que almacena el precio de las especies
          var td_vendidos_kl = document.createElement("td");
          td_vendidos_kl.setAttribute("id", id_vendidos_kl);
          td_vendidos_kl.setAttribute("class", "debito_credito");
          td_vendidos_kl.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_vendidos_kl);

          //Columna que almacena el boton borrar
          var td_accion_borrar = document.createElement("td");
          td_accion_borrar.setAttribute("id", id_boton_borrar);
          td_accion_borrar.setAttribute("class", "acciones");
          td_accion_borrar.setAttribute(
            "style",
            "border: solid 1px #ccc; text-align: center; padding: 10px;"
          );
          cont_elemento.appendChild(td_accion_borrar);

          //Boton borrar
          var btn_delete = document.createElement("button");
          btn_delete.setAttribute("class", "btn btn-danger btn-sm");
          btn_delete.setAttribute("title", "Remover");
          btn_delete.setAttribute("type", "button");
          btn_delete.setAttribute(
            "onclick",
            "eliminarEspecieUpdate(" + especies.id + ")"
          );
          btn_delete.setAttribute("style", "background:#dc3545; color: #FFF;");
          td_accion_borrar.appendChild(btn_delete);

          //Boton Modificar
          var btn_update = document.createElement("button");
          btn_update.setAttribute("class", "btn btn-warning btn-sm");
          btn_update.setAttribute("title", "Modificar");
          btn_update.setAttribute("type", "button");
          btn_update.setAttribute(
            "onclick",
            "ObtenerDatosModificarEspecieJornada(" + especies.id + ")"
          );
          btn_update.setAttribute(
            "style",
            "background:#ffc107; color: #FFF; margin-left: 10px;"
          );
          td_accion_borrar.appendChild(btn_update);

          //Icono del boton borrar
          var icono_btn_delete = document.createElement("i");
          icono_btn_delete.setAttribute("class", "fas fa-trash");
          icono_btn_delete.setAttribute("data-id", "");
          btn_delete.appendChild(icono_btn_delete);

          //Icono del boton modificar
          var icono_btn_update = document.createElement("i");
          icono_btn_update.setAttribute("class", "fas fa-edit");
          icono_btn_update.setAttribute("data-id", "");
          btn_update.appendChild(icono_btn_update);

          document.getElementById(id_especie).innerHTML = especies.especie;
          document.getElementById(id_presentacion).innerHTML =
            especies.presentacion;
          document.getElementById(id_precio_bs).innerHTML = especies.precio_bs;
          document.getElementById(id_precio_dolares).innerHTML =
            especies.precio_dolares;
          document.getElementById(id_disponibilidad_kl).innerHTML =
            especies.disponibilidad_kl;
          document.getElementById(id_vendidos_kl).innerHTML =
            especies.vendidos_kl;

          document
            .getElementById("contenedor_datos_especies_multiples_update")
            .removeAttribute("style");
        });

        /* Fin mostrar las especies de pescado para la actualizacion */

        $("#modalActualizarJornadas").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* -------------- Obtener datos para actualizar la especie de la jornada ------------------ */
function ObtenerDatosModificarEspecieJornada(id) {
  let id_especie = id;

  document.getElementById("id_modificar_especie").value = id;
  document.getElementById("modificar_especie_jornada").removeAttribute("style");
  document.getElementById("cancelar_especie_update").removeAttribute("style");

  //resetear los elementos tr
  const filas = document.querySelectorAll(".contenedor_especie");

  filas.forEach((fila) => {
    fila.style.backgroundColor = "#FFFFFF";
  });

  //Fin resetear los elementos tr

  let contenedor;

  contenedor = "contenedor" + "_" + id;

  document.getElementById(contenedor).style.backgroundColor = "#fff3cd";

  $.ajax({
    url: "index.php?page=ObtenerDatosModificarEspecieJornada",
    type: "post",
    dataType: "json",
    data: {
      id_especie: id_especie,
    },
  })
    .done(function (response) {
      if (response.data.success == true) {
        document.getElementById("id_especie_update").value =
          response.data.id_especie;
        document.getElementById("id_presentacion_update").value =
          response.data.id_presentacion;
        document.getElementById("precio_bs_update").value =
          response.data.precio_bs;
        document.getElementById("disponibilidad_kl_update").value =
          response.data.disponibilidad_kl;
        document.getElementById("vendidos_kl_update").value =
          response.data.vendidos_kl;

        $("#modalActualizarEspecies").modal("show");
      } else {
      }
    })
    .fail(function () {
      console.log("error");
    });
}

/* cancelar Especie Update */
function cancelarEspecieUpdate() {
  document
    .getElementById("modificar_especie_jornada")
    .setAttribute("style", "display:none;");
  document
    .getElementById("cancelar_especie_update")
    .setAttribute("style", "display:none;");

  document.getElementById("id_especie_update").value = "";
  document.getElementById("id_presentacion_update").value = "";
  document.getElementById("precio_bs_update").value = "";
  document.getElementById("disponibilidad_kl_update").value = "";
  document.getElementById("vendidos_kl_update").value = "";
}

/* Agregar Especie en el modal modificar */
var modificar_especie_jornada;
if (
  (modificar_especie_jornada = document.getElementById(
    "modificar_especie_jornada"
  ))
) {
  modificar_especie_jornada.addEventListener(
    "click",
    modificarEspecieJornada,
    false
  );

  function modificarEspecieJornada() {
    document.getElementById("cont-loader").removeAttribute("style");

    let id_especie_update = document.getElementById("id_especie_update").value;
    let id_modificar_especie = document.getElementById(
      "id_modificar_especie"
    ).value;
    let id_presentacion_update = document.getElementById(
      "id_presentacion_update"
    ).value;
    let precio_bs_update = document.getElementById("precio_bs_update").value;
    let disponibilidad_kl_update = document.getElementById(
      "disponibilidad_kl_update"
    ).value;
    //let tasa_bcv_update           = document.getElementById("tasa_bcv_update").value;
    let vendidos_kl_update =
      document.getElementById("vendidos_kl_update").value;
    let id_jornada_update = document.getElementById("id_jornada_update").value;

    /* comprobar campos vacios */
    if (
      id_especie_update == "" ||
      id_modificar_especie == "" ||
      id_presentacion_update == "" ||
      precio_bs_update == "" ||
      disponibilidad_kl_update == "" ||
      //tasa_bcv_update == "" ||
      vendidos_kl_update == ""
    ) {
      document
        .getElementById("cont-loader")
        .setAttribute("style", "display:none;");

      Swal.fire({
        icon: "error",
        title: "Campos vacíos",
        text: "Campo ítems vacío",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    if (tasa_bcv_update == "") {
      document
        .getElementById("cont-loader")
        .setAttribute("style", "display:none;");

      Swal.fire({
        icon: "error",
        title: "Atención",
        text: "A la hora de agregar una especie se quiere que agregue primero el valor de la tasa BCV para realizar el calculo correspondiente en $",
        confirmButtonColor: "#3085d6",
      });
      return;
    }

    $.ajax({
      url: "index.php?page=modificarEspecieJornada",
      type: "post",
      dataType: "json",
      data: {
        id_especie_update: id_especie_update,
        id_modificar_especie: id_modificar_especie,
        id_presentacion_update: id_presentacion_update,
        precio_bs_update: precio_bs_update,
        disponibilidad_kl_update: disponibilidad_kl_update,
        //tasa_bcv_update: tasa_bcv_update,
        vendidos_kl_update: vendidos_kl_update,
        id_jornada_update: id_jornada_update,
      },
    })

      .done(function (response) {
        if (response.data.success == true) {
          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");

          Swal.fire({
            icon: "success",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });

          document.getElementById("multiples_especies_update").innerHTML = "";

          document.getElementById("multiples_especies_update").innerHTML =
            "<tr><th>Especies</th><th>Presentación</th><th>Bolivares</th><th>Dolares</th><th>Disponibilidad</th><th>Kilos vendidos</th><th>Acciones</th></tr>";

          response.data.especies_pescado.forEach(function (especies, index) {
            contador = contador + 1;

            //7document.getElementById("total_beneficiarios_view").innerHTML = contador;

            let class_contenedor = "row contenedor_" + especies.id;
            let id_contenedor = "contenedor_" + especies.id;

            let id_especie = "id_especie_" + especies.id;
            let id_presentacion = "id_presentacion_" + especies.id;
            let id_precio_bs = "id_precio_bs_" + especies.id;
            let id_precio_dolares = "id_precio_dolares" + especies.id;
            let id_disponibilidad_kl = "id_disponibilidad_kl_" + especies.id;
            let id_vendidos_kl = "id_vendidos_kl_" + especies.id;
            let id_boton_borrar = "id_boton_borrar_" + especies.id;

            //Contenedor de los detalles agregados
            var cont_elemento = document.createElement("tr");
            cont_elemento.setAttribute("id", id_contenedor);
            cont_elemento.setAttribute("class", "contenedor_especie");
            cont_elemento.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            document
              .getElementById("multiples_especies_update")
              .appendChild(cont_elemento);

            //td que almacena los nombres de las especies
            var td_especies = document.createElement("td");
            td_especies.setAttribute("id", id_especie);
            td_especies.setAttribute("class", "ente");
            td_especies.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_especies);

            //td que almacena que almacena las presentaciones de las especies
            var td_presentacion = document.createElement("td");
            td_presentacion.setAttribute("id", id_presentacion);
            td_presentacion.setAttribute("class", "cuenta_movimiento");
            td_presentacion.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_presentacion);

            //td que almacena el precio de las especies
            var td_precio_bs = document.createElement("td");
            td_precio_bs.setAttribute("id", id_precio_bs);
            td_precio_bs.setAttribute("class", "debito_credito");
            td_precio_bs.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_precio_bs);

            //td que almacena el precio de las especies
            var td_precio_dolares = document.createElement("td");
            td_precio_dolares.setAttribute("id", id_precio_dolares);
            td_precio_dolares.setAttribute("class", "debito_credito");
            td_precio_dolares.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_precio_dolares);

            //td que almacena el precio de las especies
            var td_disponibilidad_kl = document.createElement("td");
            td_disponibilidad_kl.setAttribute("id", id_disponibilidad_kl);
            td_disponibilidad_kl.setAttribute("class", "debito_credito");
            td_disponibilidad_kl.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_disponibilidad_kl);

            //td que almacena el precio de las especies
            var td_vendidos_kl = document.createElement("td");
            td_vendidos_kl.setAttribute("id", id_vendidos_kl);
            td_vendidos_kl.setAttribute("class", "debito_credito");
            td_vendidos_kl.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_vendidos_kl);

            //Columna que almacena el boton borrar
            var td_accion_borrar = document.createElement("td");
            td_accion_borrar.setAttribute("id", id_boton_borrar);
            td_accion_borrar.setAttribute("class", "acciones");
            td_accion_borrar.setAttribute(
              "style",
              "border: solid 1px #ccc; text-align: center; padding: 10px;"
            );
            cont_elemento.appendChild(td_accion_borrar);

            //Boton borrar
            var btn_delete = document.createElement("button");
            btn_delete.setAttribute("class", "btn btn-danger btn-sm");
            btn_delete.setAttribute("title", "Remover");
            btn_delete.setAttribute("type", "button");
            btn_delete.setAttribute(
              "onclick",
              "eliminarEspecieUpdate(" + especies.id + ")"
            );
            btn_delete.setAttribute(
              "style",
              "background:#dc3545; color: #FFF;"
            );
            td_accion_borrar.appendChild(btn_delete);

            //Boton Modificar
            var btn_update = document.createElement("button");
            btn_update.setAttribute("class", "btn btn-warning btn-sm");
            btn_update.setAttribute("title", "Modificar");
            btn_update.setAttribute("type", "button");
            btn_update.setAttribute(
              "onclick",
              "ObtenerDatosModificarEspecieJornada(" + especies.id + ")"
            );
            btn_update.setAttribute(
              "style",
              "background:#ffc107; color: #FFF; margin-left: 10px;"
            );
            td_accion_borrar.appendChild(btn_update);

            //Icono del boton borrar
            var icono_btn_delete = document.createElement("i");
            icono_btn_delete.setAttribute("class", "fas fa-trash");
            icono_btn_delete.setAttribute("data-id", "");
            btn_delete.appendChild(icono_btn_delete);

            //Icono del boton modificar
            var icono_btn_update = document.createElement("i");
            icono_btn_update.setAttribute("class", "fas fa-edit");
            icono_btn_update.setAttribute("data-id", "");
            btn_update.appendChild(icono_btn_update);

            document.getElementById(id_especie).innerHTML = especies.especie;
            document.getElementById(id_presentacion).innerHTML =
              especies.presentacion;
            document.getElementById(id_precio_bs).innerHTML =
              especies.precio_bs;
            document.getElementById(id_precio_dolares).innerHTML =
              especies.precio_dolares;
            document.getElementById(id_disponibilidad_kl).innerHTML =
              especies.disponibilidad_kl;
            document.getElementById(id_vendidos_kl).innerHTML =
              especies.vendidos_kl;

            document
              .getElementById("contenedor_datos_especies_multiples_update")
              .removeAttribute("style");
          });

          /* Fin mostrar las especies de pescado para la actualizacion */
        } else {
          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");

          Swal.fire({
            icon: "error",
            confirmButtonColor: "#3085d6",
            title: response.data.message,
            text: response.data.info,
          });
        }
      })
      .fail(function () {
        document
          .getElementById("cont-loader")
          .setAttribute("style", "display:none;");

        console.log("error");
      });
  }
}

/* Finalizar Jornada */
function finalizarJornada(id) {
  var id_jornada = id;

  Swal.fire({
    title: "¿Desea finalizar la Jornada?",
    text: "Una vez finalizada, no podrá realizar más modificaciones en ella.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById("cont-loader").removeAttribute("style");

      $.ajax({
        url: "index.php?page=finalizarjornada",
        type: "post",
        dataType: "json",
        data: {
          id_jornada: id_jornada,
        },
      })
        .done(function (response) {
          if (response.data.success == true) {
            Swal.fire({
              icon: "success",
              confirmButtonColor: "#3085d6",
              title: response.data.message,
              text: response.data.info,
            });

            document
              .getElementById("cont-loader")
              .setAttribute("style", "display:none;");

            setTimeout(function () {
              location.reload();
            }, 3000); // 3000 milisegundos = 3 segundos
          } else {
            document
              .getElementById("cont-loader")
              .setAttribute("style", "display:none;");
            Swal.fire({
              icon: "error",
              title: response.data.message,
              confirmButtonColor: "#0d6efd",
              text: response.data.info,
            });
          }
        })
        .fail(function () {
          console.log("error");

          document
            .getElementById("cont-loader")
            .setAttribute("style", "display:none;");
        });
    }
  });
}

/*----------------- Listar Jornada -------------*/
$(document).ready(function () {
  $("#tablaMatriz").DataTable({
    order: [[0, "DESC"]],
    procesing: true,
    serverSide: true,
    ajax: "index.php?page=listarMatriz",
    pageLength: 10,
    createdRow: function (row, data, dataIndex) {},
    dom: "Bfrtip",
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
  });
});
