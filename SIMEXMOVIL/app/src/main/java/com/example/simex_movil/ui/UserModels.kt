package com.example.simex_movil.ui

//Recibe los datos del servidor (GET)
data class UserResponse(
    val id: Int,
    val firstName: String,
    val lastName: String,
    val email: String,
    val phoneNumber: String?,
    val passwordHash: String?,
    val isActive: Boolean
)

//Envia los datos actualizados (PUT)
data class UserProfileRequest(
    val id: Int,
    val firstName: String,
    val lastName: String,
    val email: String,
    val phoneNumber: String?,
    val passwordHash: String?,
    val isActive: Boolean = true
)