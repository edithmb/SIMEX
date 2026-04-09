package com.example.simex_movil

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import com.example.simex_movil.ui.LoginViewModel

class SignInActivity : AppCompatActivity() {

    private val viewModel: LoginViewModel by viewModels()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_sign_in)

        val buttonSign = findViewById<Button>(R.id.btn_login)

        buttonSign.setOnClickListener {
            val email =
            startActivity(Intent(this, HomeClientActivity::class.java))
        }


    }

}