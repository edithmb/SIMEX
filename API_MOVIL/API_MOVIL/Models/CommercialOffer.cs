using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class CommercialOffer
{
    public int Id { get; set; }

    public string Reference { get; set; } = null!;

    public int ClientRequestId { get; set; }

    public int ClientId { get; set; }

    public int IncotermId { get; set; }

    public int OriginPortId { get; set; }

    public int DestinationPortId { get; set; }

    public int? ContainerTypeId { get; set; }

    public decimal Price { get; set; }

    public DateOnly ValidUntil { get; set; }

    public string Status { get; set; } = null!;

    public string? RejectionReason { get; set; }

    public string? Comments { get; set; }

    public int? OdooId { get; set; }

    public DateTime CreatedAt { get; set; }

    public int CreatedBy { get; set; }

    public DateTime? UpdatedAt { get; set; }

    public int? UpdatedBy { get; set; }

    public virtual Client Client { get; set; } = null!;

    public virtual ClientRequest ClientRequest { get; set; } = null!;

    public virtual ContainerType? ContainerType { get; set; }

    public virtual User CreatedByNavigation { get; set; } = null!;

    public virtual Port DestinationPort { get; set; } = null!;

    public virtual Incoterm Incoterm { get; set; } = null!;

    public virtual LogisticsOperation? LogisticsOperation { get; set; }

    public virtual Port OriginPort { get; set; } = null!;

    public virtual User? UpdatedByNavigation { get; set; }
}
