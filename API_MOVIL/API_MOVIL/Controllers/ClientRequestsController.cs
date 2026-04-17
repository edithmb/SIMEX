using API_MOVIL.Models;
using Microsoft.AspNetCore.Mvc;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ClientRequestsController : ControllerBase
    {

        private readonly Simex06Context _context;

        public ClientRequestsController(Simex06Context context)
        {
            _context = context;
        }

        //Obtener solicitud
        [HttpGet("{id}")]
        public async Task<IActionResult> GetClientRequest(int id)
        {
            var clientRequest = await _context.ClientRequests.FindAsync(id);

            if (clientRequest == null)
            {
                return NotFound("Client request not found");
            }

            return Ok(clientRequest);
        }

        //Crear solicitud
        [HttpPost]
        public async Task<IActionResult> CreateClientRequest(ClientRequest newRequest)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            newRequest.CreatedAt = DateTime.Now;

            _context.ClientRequests.Add(newRequest);
            await _context.SaveChangesAsync();

            return CreatedAtAction(nameof(GetClientRequest), new { id = newRequest.Id }, newRequest);
        }
    }
}
