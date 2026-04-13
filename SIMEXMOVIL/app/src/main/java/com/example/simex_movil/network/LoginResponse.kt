package com.example.simex_movil.network

import com.google.gson.annotations.SerializedName

data class LoginResponse(
    val token: String,
    @SerializedName("token_type")
    val tokenType: String,
    @SerializedName("expires_in")
    val expiresIn: Int
)
