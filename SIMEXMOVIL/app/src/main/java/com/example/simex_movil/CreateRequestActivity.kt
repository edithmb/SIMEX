package com.example.simex_movil

import android.content.Context
import android.os.Bundle
import android.widget.ArrayAdapter
import android.widget.AutoCompleteTextView
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProvider
import com.example.simex_movil.ui.LocationResponse
import com.example.simex_movil.ui.NewRequestViewModel
import com.example.simex_movil.ui.RequestOffer
import com.example.simex_movil.ui.SolicitudState
import com.google.android.material.button.MaterialButton

class CreateRequestActivity: AppCompatActivity() {

    private lateinit var viewModel: NewRequestViewModel

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_create_request)

        viewModel = ViewModelProvider(this).get(NewRequestViewModel::class.java)

        val btnVolver = findViewById<ImageView>(R.id.btnVolver)
        btnVolver.setOnClickListener {
            finish() // Cierra esta pantalla y te devuelve al Home
        }


        val dropdownOrigen = findViewById<AutoCompleteTextView>(R.id.dropdownOrigen)
        val dropdownDestino = findViewById<AutoCompleteTextView>(R.id.dropdownDestino)

        // Simulamos lo que nos devolvería Laravel (Tus datos reales de la BD)
        val ubicacionesDelCliente = listOf(
            LocationResponse(id = 1, name = "Almacén Principal Madrid"),
            LocationResponse(id = 2, name = "Nave Industrial Valencia"),
            LocationResponse(id = 3, name = "Puerto de Barcelona")
        )

        // Extraemos SOLO los nombres para mostrarlos en el desplegable
        val nombresUbicaciones = ubicacionesDelCliente.map { it.name }

        // Creamos el "Adaptador" que le da el diseño a la lista desplegable
        val adapter = ArrayAdapter(this, android.R.layout.simple_list_item_1, nombresUbicaciones)

        // Conectamos el adaptador a ambos menús
        dropdownOrigen.setAdapter(adapter)
        dropdownDestino.setAdapter(adapter)

        // Variables para guardar los IDs elegidos (las usaremos al hacer clic en "Solicitar")
        var origenIdSeleccionado: Int? = null
        var destinoIdSeleccionado: Int? = null

        // Cuando el usuario elige un Origen...
        dropdownOrigen.setOnItemClickListener { parent, view, position, id ->
            // Buscamos en nuestra lista original el ID que corresponde a esa posición
            origenIdSeleccionado = ubicacionesDelCliente[position].id
            Toast.makeText(this, "ID Origen interno: $origenIdSeleccionado", Toast.LENGTH_SHORT).show()
        }

        // Cuando el usuario elige un Destino...
        dropdownDestino.setOnItemClickListener { parent, view, position, id ->
            destinoIdSeleccionado = ubicacionesDelCliente[position].id
            Toast.makeText(this, "ID Destino interno: $destinoIdSeleccionado", Toast.LENGTH_SHORT).show()
        }

        // BUSCAMOS LAS CAJAS DE TEXTO Y EL BOTÓN
        val inputPeso = findViewById<EditText>(R.id.inputPeso)
        val inputVolumen = findViewById<EditText>(R.id.inputVolumen)
        val inputComentarios = findViewById<EditText>(R.id.inputComentarios)
        val btnSolicitar = findViewById<MaterialButton>(R.id.btnSolicitarCotizacion)

        // RECUPERAMOS EL TOKEN DE LA CAJA FUERTE
        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val token = sharedPref.getString("token", "") ?: ""

        //ACCIÓN AL PULSAR EL BOTÓN DE SOLICITAR
        btnSolicitar.setOnClickListener {
            // Recogemos el texto escrito
            val pesoTexto = inputPeso.text.toString()
            val volumenTexto = inputVolumen.text.toString()
            val comentarios = inputComentarios.text.toString()

            // Validación vital: Que no falte nada
            if (origenIdSeleccionado == null || destinoIdSeleccionado == null) {
                Toast.makeText(this, "Por favor, selecciona origen y destino", Toast.LENGTH_SHORT).show()
                return@setOnClickListener // Detiene la ejecución aquí
            }

            if (pesoTexto.isEmpty() || volumenTexto.isEmpty()) {
                Toast.makeText(this, "Por favor, ingresa el peso y el volumen", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }


            // Convertimos los textos a números (Double) tal como los espera la API
            val pesoNum = pesoTexto.toDouble()
            val volumenNum = volumenTexto.toDouble()

            //ATRAPAMOS EL VALOR DEL BOTÓN DE IMPORTACIÓN/EXPORTACIÓN
            val toggleGroup = findViewById<com.google.android.material.button.MaterialButtonToggleGroup>(R.id.toggleTipoOperacion)

            // Traducimos el botón presionado al idioma de Laravel
            val responsabilidadSeleccionada = if (toggleGroup.checkedButtonId == R.id.btnImportacion) {
                "BUYER"  // Si presionó Importación
            } else {
                "SELLER" // Si presionó Exportación
            }

            // Armamos nuestro "Paquete"
            val paquete = RequestOffer(
                origin_id = origenIdSeleccionado!!,
                destination_id = destinoIdSeleccionado!!,
                gross_weight_kg = pesoNum,
                volume_m3 = volumenNum,
                comments = if (comentarios.isEmpty()) null else comentarios,
                responsability = responsabilidadSeleccionada
            )
            val tokenFormateado = "Bearer $token"
            viewModel.crearSolicitudCotizacion(tokenFormateado, paquete)
        }

        // 5. ESCUCHAMOS LA RESPUESTA DEL SERVIDOR
        viewModel.estado.observe(this) { estado ->
            when (estado) {
                is SolicitudState.Loading -> {
                    btnSolicitar.isEnabled = false
                    btnSolicitar.text = "Enviando..."
                }
                is SolicitudState.Success -> {
                    Toast.makeText(this, "¡Solicitud enviada con éxito!", Toast.LENGTH_LONG).show()
                    finish() // Cerramos la pantalla y volvemos al Home mágicamente
                }
                is SolicitudState.Error -> {
                    btnSolicitar.isEnabled = true
                    btnSolicitar.text = "Solicitar Cotización"
                    Toast.makeText(this, estado.message, Toast.LENGTH_LONG).show()
                }
            }
        }
    }
}