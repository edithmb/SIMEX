using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Notification
{
    public int Id { get; set; }

    public int UserId { get; set; }

    public string Title { get; set; }

    public string Message { get; set; }

    public bool IsRead { get; set; }

    public int? ReferenceId { get; set; }

    public DateTime CreatedAt { get; set; }

    public virtual CommercialOffer Reference { get; set; }

    public virtual User User { get; set; }
}
