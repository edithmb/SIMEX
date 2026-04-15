package com.example.simex_movil

import android.content.Intent
import android.os.Bundle
import android.view.MenuItem
import androidx.appcompat.app.AppCompatActivity
import com.google.android.material.bottomnavigation.BottomNavigationView

class HomeClientActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_homeclient)

        val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)

        navButton.selectedItemId = R.id.nav_home

        menuConfiguration(this, navButton)

        val btnCrearSolicitud = findViewById<com.google.android.material.button.MaterialButton>(R.id.btnCrearSolicitud)

        btnCrearSolicitud.setOnClickListener {
            // Nos envia a la pantalla de crear solicitud
            val intent = Intent(this, CreateRequestActivity::class.java)
            startActivity(intent)
        }
    }
}