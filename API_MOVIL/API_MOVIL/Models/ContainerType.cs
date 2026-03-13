using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class ContainerType
{
    public int Id { get; set; }

    public string TypeName { get; set; } = null!;

    public virtual ICollection<CommercialOffer> CommercialOffers { get; set; } = new List<CommercialOffer>();
}
