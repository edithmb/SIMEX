using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Incoterm
{
    public int Id { get; set; }

    public int IncotermTypeId { get; set; }

    public int TrackingStepId { get; set; }

    public int? OrderNum { get; set; }

    public virtual ICollection<CommercialOffer> CommercialOffers { get; set; } = new List<CommercialOffer>();

    public virtual IncotermType IncotermType { get; set; } = null!;

    public virtual TrackingStep TrackingStep { get; set; } = null!;
}
