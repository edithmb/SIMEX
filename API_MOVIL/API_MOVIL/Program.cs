using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.IdentityModel.Tokens;
using System.Text;
using Microsoft.EntityFrameworkCore;
using API_MOVIL.Models;
using API_MOVIL.Services;


var builder = WebApplication.CreateBuilder(args);

// Add services to the container.

builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowFrontend", policy =>
    {
        policy.WithOrigins(
                "http://localhost:5173",
                "http://localhost:5174",
                "http://127.0.0.1:5173",
                "http://127.0.0.1:5174"
              )
              .AllowAnyHeader()
              .AllowAnyMethod()
              .AllowCredentials();
    });
});

builder.Services.AddControllers();
builder.Services.AddDbContext<Simex06Context>();
builder.Services.AddSingleton<EncryptionService>(); // archivo deencriptaci�n
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();
builder.Services.AddSignalR(options =>
{
    // Ensanchamos el t�nel para aceptar archivos de hasta 10 Megabytes
    options.MaximumReceiveMessageSize = 10 * 1024 * 1024;
});

// lectura de llave
var secretKey = builder.Configuration["JwtConfig:Secret"];
var keyBytes = Encoding.UTF8.GetBytes(secretKey);

builder.Services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme).AddJwtBearer(
    options =>
    {
        options.TokenValidationParameters = new TokenValidationParameters
        {
            ValidateIssuerSigningKey = true, // firma v�lida
            IssuerSigningKey = new SymmetricSecurityKey(keyBytes), // clave secreta
            ValidateIssuer = false,   
            ValidateAudience = false,
            ValidateLifetime = true, 
            ClockSkew = TimeSpan.Zero
        };
    });

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

//app.UseHttpsRedirection();

app.UseCors("AllowFrontend");

app.UseAuthentication(); // lee token
app.UseAuthorization(); // lee permisos

app.UseStaticFiles(); // para ver webs
app.MapControllers();
app.MapHub<API_MOVIL.Hubs.DocumentsPersonHub>("/Hubs/DocumentsPersonHub"); // ruta de web
app.Run();
