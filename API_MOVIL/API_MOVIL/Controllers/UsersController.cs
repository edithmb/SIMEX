using API_MOVIL.Models;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UsersController : ControllerBase
    {
        private readonly Simex06Context _context;

        public UsersController(Simex06Context context)
        {
            _context = context;
        }

        [HttpGet]
        public async Task<IActionResult> GetUsers()
        {
            
            var Users = await _context.Users.ToListAsync();

            return Ok(Users);
        }

        [HttpGet("{id}")]

        public async Task<ActionResult> GetUser(int id)
        {
            var users = await _context.Users.FindAsync(id);

            if (users == null)
            {
                return NotFound("This user doesn't exist");
            }

            return Ok(users);
        }


        [HttpPost]
        public async Task<IActionResult> CreateUser(User newUser)
        {
            
            ModelState.Remove("Role");
            ModelState.Remove("Client");

            
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            _context.Users.Add(newUser);
            await _context.SaveChangesAsync();

            return CreatedAtAction(nameof(GetUser), new { id = newUser.Id }, newUser);
        }

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

            existingUser.FirstName = updateUser.FirstName;
            existingUser.LastName = updateUser.LastName;
            existingUser.PhoneNumber = updateUser.PhoneNumber;
            existingUser.IsActive = updateUser.IsActive;

            existingUser.UpdatedAt = DateTime.Now;

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

        [HttpDelete("{id}")]

        public async Task<IActionResult> DeleteUser(int id)
        {
            var user = await _context.Users.FindAsync(id);
            if (user == null)
            {
                return NotFound("This user doesn't exist");
            }

            _context.Users.Remove(user);
            await _context.SaveChangesAsync();

            return NoContent();

        }



    }
}
