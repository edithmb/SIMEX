using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class LoginSession
{
    public long Id { get; set; }

    public int UserId { get; set; }

    public string IpAddress { get; set; } = null!;

    public string UserAgent { get; set; } = null!;

    public string DeviceType { get; set; } = null!;

    public DateTime? LoggedInAt { get; set; }

    public DateTime LoggedOutAt { get; set; }

    public DateTime TokenExpiresAt { get; set; }

    public virtual User User { get; set; } = null!;
}
