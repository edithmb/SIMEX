using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API_MOVIL.Models;
using Microsoft.AspNetCore.Authorization;
using System.Security.Claims;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize] 
    public class ClientsController : ControllerBase
    {
        private readonly Simex06Context _context;

        // bd
        public ClientsController(Simex06Context context)
        {
            _context = context;
        }

        // lista todos los clientes (Empresas)
        [HttpGet]
        public async Task<ActionResult<IEnumerable<Client>>> GetClients()
        {
            return await _context.Clients.Where(c => c.DeletedAt == null).ToListAsync(); // que no muestre los eliminados

        }

        // consultar un solo cliente mediante id
        [HttpGet("{id}")]
        public async Task<ActionResult<Client>> GetClient(int id)
        {
            var client = await _context.Clients.Where(c => c.DeletedAt == null).FirstOrDefaultAsync(c => c.Id == id);

            if (client == null) return NotFound("Customer not found or has been deleted");

            return client;
        }

        // crear un cliente
        [HttpPost]
        public async Task<ActionResult<Client>> PostClient(Client client)
        {

            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("invalid token");
            client.CreatedBy = int.Parse(userToken);


            //client.CreatedBy = 1; 
            client.CreatedAt = DateTime.UtcNow;

            _context.Clients.Add(client);
            await _context.SaveChangesAsync();

            return CreatedAtAction(nameof(GetClient), new { id = client.Id }, client);
        }

        // editar un cliente 
        [HttpPut("{id}")]
        public async Task<ActionResult<Client>> PutClient(int id, Client client)
        {
            if (id != client.Id) return BadRequest("Id does not match");

            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("invalid token");
            client.UpdatedBy = int.Parse(userToken);

            //client.UpdatedBy = 1; // pruebas

            client.UpdatedAt = DateTime.UtcNow;

            _context.Entry(client).State = EntityState.Modified;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!ClientExists(id)) return NotFound();
                else throw;
            }

            return Ok("Client updated successfully");

        }

        private bool ClientExists(int id)
        {
            return _context.Clients.Any(e => e.Id == id);
        }

        // eliminar cliente
        [HttpDelete("{id}")]
        public async Task<ActionResult<Client>> DeleteClient(int id)
        {
            var client = await _context.Clients.FindAsync(id);
            if (client == null) return NotFound("Client not found");

            // rellenar espacios de datos de "eliminacion"
            client.DeletedAt = DateTime.UtcNow;

            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("invalid token");
            client.DeletedBy = int.Parse(userToken);


            //client.DeletedBy = 1;

            // avisar al orm que se han actualizado cositas
            _context.Entry(client).State = EntityState.Modified;
            await _context.SaveChangesAsync();

            return Ok("Client successfully sent to the trash");
           
        }


        //consultar clientes por nombre de empresa
        [HttpGet("{id}/users")]
        public async Task<ActionResult<IEnumerable<User>>> GetUsersByClientId(int id)
        {

            var clientExists = await _context.Clients.AnyAsync(c => c.Id == id);

            if (!clientExists)
            {
                return NotFound("Cliente no encontrado");
            }

            var users = await _context.Users
                .Where(u => u.ClientId == id)
                .ToListAsync();

            return Ok(users);
        }
    }
}
