plugins {
    kotlin("jvm") version "1.9.23"
    application
}

application {
    mainClass.set("ServidorDniKt")
}

repositories {
    mavenCentral()
}

dependencies {
    implementation(kotlin("stdlib"))
}

sourceSets {
    main {
        kotlin {
            srcDirs("src")
        }
    }
}

tasks.jar {
    manifest {
        attributes["Main-Class"] = "ServidorDniKt"
    }
    from(configurations.runtimeClasspath.get().map {
        if (it.isDirectory) it else zipTree(it)
    })
    duplicatesStrategy = DuplicatesStrategy.EXCLUDE
}