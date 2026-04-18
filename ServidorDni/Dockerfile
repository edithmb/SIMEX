# 1. Usamos una imagen muy ligerita que ya tiene Java preinstalado
FROM eclipse-temurin:21-jre-alpine

# 2. Le decimos a Docker en qué carpeta de su sistema virtual vamos a trabajar
WORKDIR /app

# 3. Copiamos el archivo .jar
COPY out/artifacts/ServidorDni_jar/ServidorDni.jar app.jar

# 4. Creamos la carpeta de DNIs seguros dentro del contenedor para que no dé error
RUN mkdir dnis_seguros

# 5. Le avisamos a Docker de que vamos a usar el puerto 8888
EXPOSE 8888

# 6. ¡El comando para encender el servidor cuando arranque Docker!
ENTRYPOINT ["java", "-jar", "app.jar"]