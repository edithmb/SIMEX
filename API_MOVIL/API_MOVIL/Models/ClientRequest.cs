using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class ClientRequest
{
    public int Id { get; set; }

    public int ClientId { get; set; }

    public decimal VolumeM3 { get; set; }

    public decimal GrossWeightKg { get; set; }

    public string Comments { get; set; } = null!;

    public int OriginId { get; set; }

    public int DestinationId { get; set; }

    public DateTime CreatedAt { get; set; }

    public int CreatedBy { get; set; }

    public virtual Client Client { get; set; } = null!;

    public virtual ICollection<CommercialOffer> CommercialOffers { get; set; } = new List<CommercialOffer>();

    public virtual User CreatedByNavigation { get; set; } = null!;

    public virtual Location Destination { get; set; } = null!;

    public virtual Location Origin { get; set; } = null!;
}
