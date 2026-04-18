package com.example.simex_movil

import android.content.Context
import android.net.Uri
import android.os.Bundle
import android.provider.OpenableColumns
import android.view.View
import android.widget.EditText
import android.widget.LinearLayout
import android.widget.Toast
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.lifecycle.ViewModelProvider
import com.example.simex_movil.network.DniSocketManager
import com.example.simex_movil.ui.DniRecordRequest
import com.example.simex_movil.ui.PerfilState
import com.example.simex_movil.ui.ProfileViewModel
import com.example.simex_movil.ui.UserProfileRequest
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.button.MaterialButton
import okhttp3.MultipartBody
import java.io.File
import java.io.FileOutputStream

class ProfileActivity: AppCompatActivity() {

    private lateinit var viewModel: ProfileViewModel
    private var idUsuarioActual: Int = 0
    private var uriArchivoSeleccionado: Uri? = null

    private lateinit var dniSocketManager: DniSocketManager //

    //Entrar a la galeria
    private val selectorDeArchivos = registerForActivityResult(ActivityResultContracts.GetContent()) { uri: Uri? ->
        if (uri != null) {
            uriArchivoSeleccionado = uri
            val nombreArchivo = obtenerNombreArchivo(uri)
            val btnSeleccionar = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

            // Cambiamos el texto para que vea que sí se seleccionó
            btnSeleccionar.text = "Subir: $nombreArchivo"
            btnSeleccionar.setBackgroundColor(android.graphics.Color.parseColor("#8AB242"))
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profile)

        val menuInferior = findViewById<BottomNavigationView>(R.id.bottom_navigation)
        menuConfiguration(this, menuInferior)

        viewModel = ViewModelProvider(this).get(ProfileViewModel::class.java)

        // 1. BUSCAMOS LOS ELEMENTOS
        val inputFirstName = findViewById<EditText>(R.id.inputFirstName)
        val inputLastName = findViewById<EditText>(R.id.inputLastName)
        val inputEmail = findViewById<EditText>(R.id.inputEmail)
        val inputTelephone = findViewById<EditText>(R.id.inputTelephoneNumber)

        val inputPassword = findViewById<EditText>(R.id.inputPassword)
        val inputConfirmarPass = findViewById<EditText>(R.id.inputConfirmarPass)
        val seccionPassword = findViewById<LinearLayout>(R.id.seccionPassword)

        val btnEditar = findViewById<android.widget.Button>(R.id.btnEditarPerfil)
        val btnGuardar = findViewById<MaterialButton>(R.id.btn_guardar_cambios)
        val btnSeleccionarArchivo = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

        // 2. DATOS DE SESIÓN
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val rawToken = sharedPref.getString("token", "") ?: ""
        val tokenRetrofit = "Bearer $rawToken"
        idUsuarioActual = sharedPref.getInt("client_id", 0)

        aplicarTemaSegunRol()

        dniSocketManager = DniSocketManager(this, rawToken)

        // 3. CARGAMOS DATOS AL ABRIR
        if (idUsuarioActual != 0) {
            viewModel.obtenerPerfil(tokenRetrofit, idUsuarioActual)
        }

        // 4. BOTÓN: HABILIAR EDICIÓN
        btnEditar.setOnClickListener {
            inputFirstName.isEnabled = true
            inputLastName.isEnabled = true
            inputEmail.isEnabled = true
            inputTelephone.isEnabled = true

            seccionPassword.visibility = View.VISIBLE
            btnGuardar.visibility = View.VISIBLE
            btnEditar.visibility = View.GONE
        }

        // 5. BOTÓN: GUARDAR PERFIL
        btnGuardar.setOnClickListener {
            val pass1 = inputPassword.text.toString()
            val pass2 = inputConfirmarPass.text.toString()

            if (pass1.isNotEmpty() || pass2.isNotEmpty()) {
                if (pass1 != pass2) {
                    Toast.makeText(this, "Las contraseñas no coinciden", Toast.LENGTH_SHORT).show()
                    return@setOnClickListener
                }
            }

            val usuarioActualizado = UserProfileRequest(
                id = idUsuarioActual,
                firstName = inputFirstName.text.toString(),
                lastName = inputLastName.text.toString(),
                email = inputEmail.text.toString(),
                phoneNumber = inputTelephone.text.toString(),
                passwordHash = if (pass1.isNotEmpty()) pass1 else null,
                isActive = true
            )
            viewModel.actualizarPerfil(tokenRetrofit, idUsuarioActual, usuarioActualizado)
        }

        // 6. BOTÓN: ABRIR GALERÍA
        btnSeleccionarArchivo.setOnClickListener {
            if (uriArchivoSeleccionado == null) {
                // Si no hay foto, abrimos la galería
                selectorDeArchivos.launch("*/*")
            } else {
                // Si ya eligió una foto, la enviamos al servidor
                Toast.makeText(this, "Iniciando túnel seguro...", Toast.LENGTH_SHORT).show()
                subirDniConHilosYSockets(uriArchivoSeleccionado!!)
            }
        }

        // 7. OBSERVADOR DE RESPUESTAS DEL SERVIDOR
        viewModel.estado.observe(this) { estado ->
            when (estado) {
                is PerfilState.SuccessLoad -> {
                    val user = estado.user
                    inputFirstName.setText(user.firstName)
                    inputLastName.setText(user.lastName)
                    inputEmail.setText(user.email)
                    inputTelephone.setText(user.phoneNumber ?: "")
                }
                is PerfilState.SuccessUpdate -> {
                    Toast.makeText(this, "Perfil actualizado con éxito", Toast.LENGTH_SHORT).show()
                    // Restauramos la vista
                    seccionPassword.visibility = View.GONE
                    btnGuardar.visibility = View.GONE
                    btnEditar.visibility = View.VISIBLE
                    inputPassword.setText("")
                    inputConfirmarPass.setText("")
                    // Volvemos a bloquear campos
                    inputFirstName.isEnabled = false
                    inputLastName.isEnabled = false
                    inputEmail.isEnabled = false
                    inputTelephone.isEnabled = false
                }
                is PerfilState.SuccessUploadDni -> {
                    Toast.makeText(this, "DNI subido con éxito", Toast.LENGTH_SHORT).show()
                    uriArchivoSeleccionado = null
                    btnSeleccionarArchivo.text = "Subir archivo"
                    btnSeleccionarArchivo.setBackgroundColor(android.graphics.Color.parseColor("#5C82B1"))
                }
                is PerfilState.Error -> {
                    Toast.makeText(this, estado.message, Toast.LENGTH_LONG).show()
                }
                is PerfilState.Loading -> { }
                else -> {}
            }
        }
    }

    private fun subirDniConHilosYSockets(uri: Uri) {
        val fileName = obtenerNombreArchivo(uri)
        val btnSeleccionarArchivo = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

        dniSocketManager.connectAndUpload(
            uri = uri,
            fileName = fileName,
            onStatusUpdate = { mensajeDelServidor ->
                // Actualizamos los toast del proceso
                runOnUiThread {
                    Toast.makeText(this, mensajeDelServidor, Toast.LENGTH_LONG).show()
                }
            },
            onSucces = {
                fileName, claveAES ->
                // archivo guardado y encriptado entonces avisamos a la api
                runOnUiThread {
                    Toast.makeText(this, "DNI Seguro. Guardando registro en BD...", Toast.LENGTH_LONG).show()


                    // texto para api .net
                    val requestAnotacionBD = DniRecordRequest(
                        entityId = idUsuarioActual,
                        entityType = "Client",
                        fileName = fileName,
                        filePath = "/dnis_seguros/$fileName",
                        encryptionKey = claveAES // La llave que generó el móvil en Base64
                    )

                    // Llamamos al ViewModel para que Retrofit se lo envíe a .NET
                    val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
                    val tokenRetrofit = "Bearer " + (sharedPref.getString("token", "") ?: "")

                    viewModel.guardarDniEnBD(tokenRetrofit, requestAnotacionBD)
                    // resetear boton
                    uriArchivoSeleccionado = null
                    btnSeleccionarArchivo.text = "Subir archivo"
                    btnSeleccionarArchivo.setBackgroundColor(android.graphics.Color.parseColor("#5C82B1"))
                }
            }
        )
    }

    private fun obtenerNombreArchivo(uri: Uri): String {
        var nombre = "archivo_desconocido"
        contentResolver.query(uri, null, null, null, null)?.use { cursor ->
            if (cursor.moveToFirst()) {
                val index = cursor.getColumnIndex(OpenableColumns.DISPLAY_NAME)
                if (index != -1) {
                    nombre = cursor.getString(index)
                }
            }
        }
        return nombre
    }

    private fun aplicarTemaSegunRol() {
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val rol = sharedPref.getString("rol", "")?.lowercase() ?: ""

        if (rol == "agente comercial" || rol == "agente") {



            val colorAzulPrincipal = ContextCompat.getColor(this, R.color.blue)
            val colorBlanco = ContextCompat.getColor(this, R.color.white)

            // Pintamos los botones principales
            val btnGuardar = findViewById<MaterialButton>(R.id.btn_guardar_cambios)
            val btnEditar = findViewById<android.widget.Button>(R.id.btnEditarPerfil)
            val btnSeleccionarArchivo = findViewById<MaterialButton>(R.id.btn_seleccionar_archivo)

            btnGuardar?.setBackgroundColor(colorAzulPrincipal)
            btnEditar?.setBackgroundColor(colorAzulPrincipal)

            if (uriArchivoSeleccionado == null) {
                btnSeleccionarArchivo?.setBackgroundColor(colorAzulPrincipal)
            }

            // PINTAMOS EL MENÚ INFERIOR
            val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)
            if (navButton != null) {
                // Cambiamos el fondo del menú a Azul
                navButton.setBackgroundColor(colorAzulPrincipal)

                //Cambiamos el color de los iconos y el texto a blanco
                navButton.itemIconTintList = android.content.res.ColorStateList.valueOf(colorBlanco)
                navButton.itemTextColor = android.content.res.ColorStateList.valueOf(colorBlanco)
            }
        }
    }
}
