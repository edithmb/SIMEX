using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Client
{
    public int Id { get; set; }

    public string CompanyName { get; set; } = null!;

    public string? VatNumber { get; set; }

    public string Address { get; set; } = null!;

    public string Country { get; set; } = null!;

    public string PostalCode { get; set; } = null!;

    public string ContactName { get; set; } = null!;

    public string? Email { get; set; }

    public string Phone { get; set; } = null!;

    public int? OdooInt { get; set; }

    public DateTime CreatedAt { get; set; }

    public int CreatedBy { get; set; }

    public DateTime? UpdatedAt { get; set; }

    public int? UpdatedBy { get; set; }

    public DateTime? DeletedAt { get; set; }

    public int? DeletedBy { get; set; }

    public virtual ICollection<ClientRequest> ClientRequests { get; set; } = new List<ClientRequest>();

    public virtual ICollection<CommercialOffer> CommercialOffers { get; set; } = new List<CommercialOffer>();

    public virtual User CreatedByNavigation { get; set; } = null!;

    public virtual User? DeletedByNavigation { get; set; }

    public virtual ICollection<Location> Locations { get; set; } = new List<Location>();

    public virtual ICollection<LogisticsOperation> LogisticsOperations { get; set; } = new List<LogisticsOperation>();

    public virtual User? UpdatedByNavigation { get; set; }

    public virtual ICollection<User> Users { get; set; } = new List<User>();
}
