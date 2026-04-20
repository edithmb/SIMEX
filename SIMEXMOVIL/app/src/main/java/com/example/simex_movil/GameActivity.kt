package com.example.simex_movil

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity
import com.google.android.material.bottomnavigation.BottomNavigationView
import kotlin.jvm.java

class GameActivity: AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_game)

        val navButton = findViewById<BottomNavigationView>(R.id.bottom_navigation)

        navButton.selectedItemId = R.id.nav_game

        menuConfiguration(this, navButton)

        val btnSimulador = findViewById<Button>(R.id.btnAbrirSimulador)

//        btnSimulador.setOnClickListener {
//            val intent = Intent(this, com.unity3d.player.UnityPlayerGameActivity::class.java)
//            startActivity(intent)
//        }
    }
}