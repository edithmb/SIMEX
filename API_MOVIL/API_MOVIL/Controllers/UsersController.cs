using API_MOVIL.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using System.Security.Claims;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [Authorize]
    public class UsersController : ControllerBase
    {
        private readonly Simex06Context _context;

        public UsersController(Simex06Context context)
        {
            _context = context;
        }

        //Consulta todos los usuarios

        [HttpGet]
        public async Task<IActionResult> GetUsers()
        {

            var users = await _context.Users
                                      .Where(u => u.DeletedAt == null)
                                      .ToListAsync();

            return Ok(users);
        }

        //Consulta un usuario por ID

        [HttpGet("{id}")]

        public async Task<ActionResult> GetUser(int id)
        {
            var users = await _context.Users
                .FirstOrDefaultAsync(u => u.Id == id && u.DeletedAt == null);

            if (users == null)
            {
                return NotFound("This user doesn't exist");
            }

            return Ok(users);
        }

        //Crea un usuario

        [HttpPost]
        public async Task<IActionResult> CreateUser(User newUser)
        {
            ModelState.Remove("Role");
            ModelState.Remove("Client");

            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            //leer token
             var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("Token inválido.");
            newUser.CreatedBy = int.Parse(userToken);

            //newUser.CreatedBy = 1;


            newUser.CreatedAt = DateTime.UtcNow;
            newUser.PasswordHash = BCrypt.Net.BCrypt.HashPassword(newUser.PasswordHash);

            _context.Users.Add(newUser);
            await _context.SaveChangesAsync();

            return CreatedAtAction(nameof(GetUser), new { id = newUser.Id }, newUser);
        }

        //Edita un usuario

        [HttpPut("{id}")]

        public async Task<IActionResult> UpdateUser(int id, User updateUser)
        {

            if (id != updateUser.Id)
            {
                return BadRequest("User ID mismatch");
            }

            var existingUser = await _context.Users.FindAsync(id);
            if (existingUser == null)
            {
                return NotFound("This user doesn't exist");

            }

            //leer token
            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("Token inválido.");
            existingUser.UpdatedBy = int.Parse(userToken);

            //existingUser.UpdatedBy = 1;

            existingUser.FirstName = updateUser.FirstName;
            existingUser.LastName = updateUser.LastName;
            existingUser.PhoneNumber = updateUser.PhoneNumber;
            existingUser.IsActive = updateUser.IsActive;

            existingUser.UpdatedAt = DateTime.UtcNow;
            if (updateUser.UpdatedBy != null)
            {
                existingUser.UpdatedBy = updateUser.UpdatedBy;
            }

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateException ex)
            {
                return StatusCode(500, $"Error al actualizar la base de datos: {ex.Message}");
            }
            return NoContent();
        }

        //Elimina un usuario (soft delete)

        [HttpDelete("{id}")]

        public async Task<IActionResult> DeleteUser(int id, [FromQuery] int deletedBy)
        {
            var user = await _context.Users.FindAsync(id);
            if (user == null)
            {
                return NotFound("This user doesn't exist");
            }

            //leer token
            var userToken = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userToken)) return Unauthorized("Token inválido.");
            user.DeletedBy = int.Parse(userToken);

            user.DeletedBy = 1;


            user.IsActive = false;
            user.DeletedAt = DateTime.UtcNow;
            user.DeletedBy = deletedBy;

            try
            {
                await _context.SaveChangesAsync();
            }
            catch (DbUpdateException ex)
            {
                return StatusCode(500, $"Error al aplicar el Soft Delete: {ex.Message}");
            }

            return NoContent();

        }


    }
}
