import org.gradle.kotlin.dsl.implementation

plugins {
    alias(libs.plugins.android.application)
    alias(libs.plugins.kotlin.android)
}

android {
    namespace = "com.example.simex_movil"
    compileSdk = 36

    defaultConfig {
        applicationId = "com.example.simex_movil"
        minSdk = 24
        targetSdk = 36
        versionCode = 1
        versionName = "1.0"

        testInstrumentationRunner = "androidx.test.runner.AndroidJUnitRunner"
    }

    buildTypes {
        release {
            isMinifyEnabled = false
            proguardFiles(
                getDefaultProguardFile("proguard-android-optimize.txt"),
                "proguard-rules.pro"
                         )
        }
    }
    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }
    kotlinOptions {
        jvmTarget = "11"
    }
}

dependencies {

    implementation(libs.androidx.core.ktx)
    implementation(libs.androidx.appcompat)
    implementation(libs.material)
    implementation(libs.androidx.activity)
    implementation(libs.androidx.constraintlayout)
    //Hace la peticiona la API
    implementation("com.squareup.retrofit2:retrofit:2.9.0")
    //Gson converter: Traduce los json a kotlin y viceversa
    implementation("com.squareup.retrofit2:converter-gson:2.9.0")
    // Extensiones para el ciclo de vida y viewModelScope
    implementation("androidx.lifecycle:lifecycle-viewmodel-ktx:2.7.0")

    // Corrutinas de Kotlin (necesarias para poder usar el .launch)
    implementation("org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.3")
    //cliente de wbsocket para conectarse a .nt
    implementation ("com.microsoft.signalr:signalr:7.0.14")
    // manejo de hilos
    implementation("org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.3")
    implementation(libs.androidx.ui.test)
    testImplementation(libs.junit)
    androidTestImplementation(libs.androidx.junit)
    androidTestImplementation(libs.androidx.espresso.core)

}