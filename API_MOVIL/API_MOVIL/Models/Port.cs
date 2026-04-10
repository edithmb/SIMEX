using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Port
{
    public int Id { get; set; }

    public string Name { get; set; }

    public int CityId { get; set; }

    public virtual City City { get; set; }

    public virtual ICollection<CommercialOffer> CommercialOfferDestinationPorts { get; set; } = new List<CommercialOffer>();

    public virtual ICollection<CommercialOffer> CommercialOfferOriginPorts { get; set; } = new List<CommercialOffer>();
}
