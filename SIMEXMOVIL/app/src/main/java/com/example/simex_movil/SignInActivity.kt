package com.example.simex_movil

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity

class SignInActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_sign_in)

        val buttonSign = findViewById<Button>(R.id.buttonSignIn)

        buttonSign.setOnClickListener {
            startActivity(Intent(this, HomeActivity::class.java))
        }


    }

}