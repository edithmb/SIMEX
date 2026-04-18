package com.example.simex_movil.ui

import android.content.Context
import android.os.Bundle
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProvider
import com.example.simex_movil.R // Ajusta a tu paquete

class TrackingActivity : AppCompatActivity() {

    private lateinit var viewModel: TrackingViewModel

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        // Asegúrate de que el nombre coincide con tu XML del tracking
        setContentView(R.layout.activity_tracking)

        // 1. ABRIMOS LA MALETA (Recibimos el ID que mandó el Home)
        // Si por algún error no viene el ID, ponemos -1 por defecto
        val idOperacion = intent.getIntExtra("OPERACION_ID", -1)

        if (idOperacion == -1) {
            Toast.makeText(this, "Error al abrir la orden", Toast.LENGTH_SHORT).show()
            finish() // Cerramos la pantalla y volvemos atrás
            return
        }

        // 2. BUSCAMOS LOS ELEMENTOS DE TU DISEÑO XML
        val btnVolver = findViewById<ImageView>(R.id.btnVolver)
        val tvOrderId = findViewById<TextView>(R.id.tvOrderId)
        val tvBadgeEstado = findViewById<TextView>(R.id.tvBadgeEstadoGeneral)
        val tvOrigenPrincipal = findViewById<TextView>(R.id.tvOrigenPrincipal)
        val tvDestinoPrincipal = findViewById<TextView>(R.id.tvDestinoPrincipal)
        val tvPesoInfo = findViewById<TextView>(R.id.tvPesoInfo)
        val tvVolumenInfo = findViewById<TextView>(R.id.tvVolumenInfo)
        val tvEtaInfo = findViewById<TextView>(R.id.tvEtaInfo)

        // Botón para volver atrás
        btnVolver.setOnClickListener {
            finish() // Destruye esta pantalla y vuelve al Home automáticamente
        }

        // 3. PREPARAMOS EL MESERO (ViewModel)
        viewModel = ViewModelProvider(this).get(TrackingViewModel::class.java)

        val sharedPref = getSharedPreferences("Mis Preferencias", Context.MODE_PRIVATE)
        val token = "Bearer " + (sharedPref.getString("token", "") ?: "")

        // 4. ESCUCHAMOS CUANDO EL MESERO TRAIGA EL PLATO
        viewModel.operacion.observe(this) { op ->
            if (op != null) {
                // Extraemos los datos navegando por el JSON
                val origen = op.commercialOffer?.clientRequest?.origin?.name ?: "Desconocido"
                val destino = op.commercialOffer?.clientRequest?.destination?.name ?: "Desconocido"
                val peso = op.commercialOffer?.clientRequest?.gross_weight_kg
                val volumen = op.commercialOffer?.clientRequest?.volume_m3

                // Llenamos tu hermoso diseño
                tvOrderId.text = op.reference
                tvBadgeEstado.text = op.status.uppercase()
                tvOrigenPrincipal.text = origen
                tvDestinoPrincipal.text = destino
                tvEtaInfo.text = op.eta ?: "Por confirmar"

                // Formateamos el peso y volumen (Si es nulo, ponemos "N/A")
                tvPesoInfo.text = if (peso != null) "$peso kg" else "N/A"
                tvVolumenInfo.text = if (volumen != null) "$volumen m³" else "N/A"
            }
        }

        viewModel.error.observe(this) { errorMsg ->
            Toast.makeText(this, errorMsg, Toast.LENGTH_LONG).show()
        }

        // 5. ¡A TRABAJAR! Le pedimos al ViewModel que busque la operación
        viewModel.cargarDetalleOperacion(token, idOperacion)
    }
}