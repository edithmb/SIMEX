using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using API_MOVIL.Models;
using Microsoft.AspNetCore.Authorization;

namespace API_MOVIL.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    // [Authorize]
    public class ClientsController : Controller
    {
        private readonly Simex06Context _context;

        // bd
        public ClientsController(Simex06Context context)
        {
            _context = context;
        }
    }
}
