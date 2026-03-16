package com.example.simex_movil

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.Switch
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.app.AppCompatDelegate

class SettingsActivity: AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_settings)

        val documents = findViewById<TextView>(R.id.txv_documentation)
//        val request = findViewById<TextView>(R.id.txv_history)
        val language = findViewById<TextView>(R.id.txv_language)
        val viewDark = findViewById<Switch>(R.id.switch_dark_mode)
        val notifications = findViewById<Switch>(R.id.switch_notificaciones)
        val contact = findViewById<TextView>(R.id.txv_contact)
        val logout = findViewById<TextView>(R.id.txv_logout)


        documents.setOnClickListener {
            val intent = Intent(this, HistoryOffersActivity::class.java)
            startActivity(intent)
        }

//        request.setOnClickListener {
//            val intent = Intent(this, CreateRequestActivity::class.java )
//            startActivity(intent)
//        }

        language.setOnClickListener {
            val languages = arrayOf("Español", "Ingles", "Catalán", "Frances", "Aleman")

            val constructor = AlertDialog.Builder(this)
            constructor.setTitle("Selecciona idioma")
            constructor.setItems(languages){ _, position ->
                val selectedLanguage = languages[position]
                Toast.makeText(this, "You change to $selectedLanguage.", Toast.LENGTH_SHORT).show()
            }
            constructor.show()
        }

        viewDark.setOnCheckedChangeListener { _, isChecked ->
            if (isChecked){
                AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES)
            } else {
                AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO)
            }
        }





    }
}