using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using System.Security.Claims;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class PruebaSeguridadController : ControllerBase
    {
        // PÚBLICA (Sin protección)
 
        [HttpGet("publico")]
        public IActionResult RutaPublica()
        {
            // Cualquiera puede llamar a esta ruta.
            return Ok(new { mensaje = "¡Hola! Esta ruta es pública. No necesitas el token de Laravel para ver esto." });
        }

    
        // RUTA 2: PRIVADA (Protegida por la clave)
    
        [Authorize] // autorizacion
        [HttpGet("privado")]
        public IActionResult RutaPrivada()
        {
    
            var userId = User.FindFirstValue(ClaimTypes.NameIdentifier) ?? User.FindFirstValue("sub");

            return Ok(new
            {
                mensaje = "¡ÉXITO TOTAL! Has entrado en la zona VIP.",
                tuIdDeUsuarioEs = userId
            });
        }
    }
}
