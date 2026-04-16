package com.example.simex_movil

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.MenuItem
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProvider
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.simex_movil.ui.HomeViewModel
import com.example.simex_movil.ui.OperacionesAdapter
import com.example.simex_movil.ui.TrackingActivity
import com.google.android.material.bottomnavigation.BottomNavigationView

class HomeClientActivity : AppCompatActivity() {

    private lateinit var viewModel: HomeViewModel
    private lateinit var adaptador: OperacionesAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_homeclient)
        val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)

        navButton.selectedItemId = R.id.nav_game

        menuConfiguration(this, navButton)

        val rvOperaciones = findViewById<RecyclerView>(R.id.listaSeguimientos)
        rvOperaciones.layoutManager = LinearLayoutManager(this)

        // 1. INICIALIZAMOS EL ADAPTADOR Y DEFINIMOS EL CLICK
        adaptador = OperacionesAdapter(emptyList()) { operacionClickeada ->
            // Este código se ejecuta SOLO cuando el usuario toca una tarjeta

            // Preparamos el viaje a la pantalla de Tracking
            val intent = Intent(this, TrackingActivity::class.java)

            // Metemos el ID de la operación en la "maleta" del viaje
            intent.putExtra("OPERACION_ID", operacionClickeada.id)

            startActivity(intent)
        }
        rvOperaciones.adapter = adaptador

        // 2. CONFIGURAMOS EL VIEWMODEL (Como lo teníamos antes)
        viewModel = ViewModelProvider(this).get(HomeViewModel::class.java)

        val sharedPref = getSharedPreferences("PreferenciasUsuario", Context.MODE_PRIVATE)
        val token = sharedPref.getString("token", "") ?: ""
        val tokenFormateado = "Bearer $token"

        viewModel.operaciones.observe(this) { listaDeOperaciones ->
            adaptador.actualizarLista(listaDeOperaciones)
        }

        viewModel.error.observe(this) { mensajeError ->
            Toast.makeText(this, mensajeError, Toast.LENGTH_LONG).show()
        }

        if (token.isNotEmpty()) {
            viewModel.cargarOperaciones(tokenFormateado)
        }

        val btnCrearSolicitud = findViewById<com.google.android.material.button.MaterialButton>(R.id.btnCrearSolicitud)

        // 2. Le decimos a Android qué hacer cuando lo toquen
        btnCrearSolicitud.setOnClickListener {
            // Preparamos el viaje a la nueva pantalla del formulario
            // (Asumo que la llamaste CrearSolicitudActivity, si le pusiste otro nombre, cámbialo aquí)
            val intent = Intent(this, CreateRequestActivity::class.java)
            startActivity(intent)
        }
    }
}