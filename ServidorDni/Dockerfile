# ETAPA 1: Compilar
FROM gradle:8-jdk21-alpine AS builder
WORKDIR /app
COPY . .
RUN gradle jar --no-daemon

# ETAPA 2: Ejecutar
FROM eclipse-temurin:21-jre-alpine
WORKDIR /app
COPY --from=builder /app/build/libs/*.jar app.jar
RUN mkdir dnis_seguros
EXPOSE 8888
ENTRYPOINT ["java", "-jar", "app.jar"]