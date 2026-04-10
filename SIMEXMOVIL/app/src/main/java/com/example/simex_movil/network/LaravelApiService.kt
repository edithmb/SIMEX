package com.example.simex_movil.network

import okhttp3.Response
import retrofit2.http.Body
import retrofit2.http.POST

interface LaravelApiService {

    @POST("login")
    suspend fun login(@Body request: LoginRequest): retrofit2.Response<LoginResponse>
}