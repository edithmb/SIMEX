package com.example.simex_movil.network

import com.google.gson.annotations.SerializedName

data class LoginResponse(
    val token: String,
    @SerializedName("token_type")
    val tokenType: String,
    @SerializedName("expires_in")
    val expiresIn: Int,
    val user: UserDto
)

data class UserDto (
    val id: Int,
    val first_name: String,
    val last_name: String,
    val email: String,
    val role: RoleDto
)

data class RoleDto (
    val id: Int,
    val name: String
    )

