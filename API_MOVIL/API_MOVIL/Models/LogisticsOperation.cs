using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class LogisticsOperation
{
    public int Id { get; set; }

    public string Reference { get; set; } = null!;

    public int CommercialOfferId { get; set; }

    public int ClientId { get; set; }

    public string Status { get; set; } = null!;

    public DateOnly? Etd { get; set; }

    public DateOnly? Eta { get; set; }

    public DateOnly? Atd { get; set; }

    public DateOnly? Ata { get; set; }

    public int? OdooId { get; set; }

    public DateTime CreatedAt { get; set; }

    public DateTime UpdatedAt { get; set; }

    public DateTime? CompletedAt { get; set; }

    public virtual Client Client { get; set; } = null!;

    public virtual CommercialOffer CommercialOffer { get; set; } = null!;
}
