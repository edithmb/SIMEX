package com.example.simex_movil

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.Toast
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProvider
import com.example.simex_movil.ui.LoginState
import com.example.simex_movil.ui.LoginViewModel
import com.google.android.material.textfield.TextInputEditText

class SignInActivity : AppCompatActivity() {

    private lateinit var viewModel: LoginViewModel

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_sign_in)

        viewModel = ViewModelProvider(this)[LoginViewModel::class.java]

        val etEmail = findViewById<TextInputEditText>(R.id.et_email)
        val etPassword = findViewById<TextInputEditText>(R.id.et_password)
        val btnLogin = findViewById<Button>(R.id.btn_login)

        btnLogin.setOnClickListener {
            val email = etEmail.text.toString().trim()
            val password = etPassword.text.toString().trim()

            if(email.isEmpty() || password.isEmpty() ) {
                Toast.makeText(this, "Por favor, llena todos los campos", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            viewModel.starLogin(email, password)
        }

        viewModel.loginState.observe(this) { state ->
            when(state) {
                is LoginState.Loading -> {
                    btnLogin.isEnabled = false
                    btnLogin.text = "Cargando..."
                }
                is LoginState.Success -> {
                    btnLogin.isEnabled  = true
                    btnLogin.text = "Iniciar Sesión"

                    Toast.makeText(this, "Bienvenido", Toast.LENGTH_SHORT).show()
                }
                is LoginState.Error -> {
                    btnLogin.isEnabled = true
                    btnLogin.text = "Iniciar Sesión"
                    Toast.makeText(this, state.message, Toast.LENGTH_LONG).show()
                }
            }
        }

    }

}